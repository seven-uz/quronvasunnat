<?php
require __DIR__ . '/inc/bootstrap.php';
require_login();

$key = $_GET['t'] ?? '';
$cfg = table_config($key);
if ($cfg === null) {
	http_response_code(404);
	exit('Bunday bo\'lim yo\'q.');
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$isEdit = ($id > 0);

$cols = table_columns($db, $key);
if (empty($cols)) {
	http_response_code(500);
	exit('Jadval ustunlarini o\'qib bo\'lmadi.');
}

// Tahrirlanadigan ustunlar: auto_increment (id) tashqari hammasi.
$editable = array_values(array_filter($cols, function ($c) {
	return stripos($c['extra'], 'auto_increment') === false;
}));

$error = '';
// Forma qiymatlari (POST'da xato bo'lsa qaytib ko'rsatish uchun).
$values = [];

// ---- Saqlash ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	verify_csrf();

	$names = [];
	$binds = [];
	foreach ($editable as $c) {
		$name = $c['name'];
		$raw  = (string)($_POST['f_' . $name] ?? '');
		$values[$name] = $raw;
		$names[] = $name;
		// Bo'sh + NULL ruxsat etilgan bo'lsa NULL saqlaymiz.
		$binds[] = ($raw === '' && $c['null']) ? null : $raw;
	}

	if (empty($names)) {
		$error = 'Saqlash uchun ustun yo\'q.';
	} else {
		if ($isEdit) {
			$set = '`' . implode('` = ?, `', $names) . '` = ?';
			$sql = 'UPDATE `' . $key . '` SET ' . $set . ' WHERE `id` = ?';
			$params = $binds;
			$params[] = $id;
		} else {
			$place = rtrim(str_repeat('?, ', count($names)), ', ');
			$sql = 'INSERT INTO `' . $key . '` (`' . implode('`, `', $names) . '`) VALUES (' . $place . ')';
			$params = $binds;
		}

		$stmt = mysqli_prepare($db, $sql);
		if (!$stmt) {
			error_log('admin save prepare failed: ' . mysqli_error($db));
			$error = 'Saqlashda xatolik yuz berdi.';
		} else {
			$types = str_repeat('s', count($params));
			mysqli_stmt_bind_param($stmt, $types, ...$params);
			if (mysqli_stmt_execute($stmt)) {
				mysqli_stmt_close($stmt);
				redirect('list.php?t=' . urlencode($key) . '&ok=saved');
			}
			error_log('admin save execute failed: ' . mysqli_error($db));
			$error = 'Saqlashda xatolik yuz berdi.';
			mysqli_stmt_close($stmt);
		}
	}
}

// ---- Mavjud yozuvni yuklash (tahrir, POST emas) ----
if ($isEdit && $_SERVER['REQUEST_METHOD'] !== 'POST') {
	$stmt = mysqli_prepare($db, 'SELECT * FROM `' . $key . '` WHERE `id` = ? LIMIT 1');
	if ($stmt) {
		mysqli_stmt_bind_param($stmt, 'i', $id);
		mysqli_stmt_execute($stmt);
		$res = mysqli_stmt_get_result($stmt);
		$row = $res ? mysqli_fetch_assoc($res) : null;
		mysqli_stmt_close($stmt);
		if (!$row) {
			http_response_code(404);
			exit('Yozuv topilmadi.');
		}
		$values = $row;
	}
}

$pageTitle = ($isEdit ? 'Tahrirlash' : 'Yangi') . ' — ' . $cfg['label'];
require __DIR__ . '/inc/header.php';
?>
<a class="adm-back" href="list.php?t=<?= e(urlencode($key)) ?>">&larr; <?= e($cfg['label']) ?></a>
<h1 class="adm-h1"><?= $isEdit ? 'Tahrirlash' : 'Yangi yozuv' ?></h1>

<?php if ($error !== ''): ?>
	<div class="adm-alert adm-alert-error"><?= e($error) ?></div>
<?php endif; ?>

<form class="adm-form" method="post" action="edit.php?t=<?= e(urlencode($key)) ?><?= $isEdit ? '&id=' . e($id) : '' ?>">
	<?= csrf_field() ?>
	<?php foreach ($editable as $c): ?>
		<?php
			$name = $c['name'];
			$val = (string)($values[$name] ?? '');
			$rtl = (substr($name, -2) === 'ar'); // ...ar => arab matni, o'ngdan chapga
		?>
		<div class="adm-field">
			<label for="f_<?= e($name) ?>"><?= e($name) ?> <small><?= e($c['type']) ?></small></label>
			<?php if (is_textarea_col($c['type'])): ?>
				<textarea id="f_<?= e($name) ?>" name="f_<?= e($name) ?>" rows="5"<?= $rtl ? ' dir="rtl"' : '' ?>><?= e($val) ?></textarea>
			<?php else: ?>
				<input type="text" id="f_<?= e($name) ?>" name="f_<?= e($name) ?>" value="<?= e($val) ?>"<?= $rtl ? ' dir="rtl"' : '' ?>>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
	<div class="adm-form-actions">
		<button type="submit" class="adm-btn adm-btn-primary">Saqlash</button>
		<a class="adm-btn adm-btn-ghost" href="list.php?t=<?= e(urlencode($key)) ?>">Bekor qilish</a>
	</div>
</form>
<?php require __DIR__ . '/inc/footer.php'; ?>
