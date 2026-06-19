<?php
require __DIR__ . '/inc/bootstrap.php';
require_login();

$key = $_GET['t'] ?? '';
$cfg = table_config($key);
if ($cfg === null) {
	http_response_code(404);
	exit('Bunday bo\'lim yo\'q.');
}

$id = (int)($_GET['id'] ?? ($_POST['id'] ?? 0));
if ($id <= 0) {
	http_response_code(400);
	exit('Yaroqsiz id.');
}

// ---- O'chirish (faqat POST + CSRF) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	verify_csrf();
	$stmt = mysqli_prepare($db, 'DELETE FROM `' . $key . '` WHERE `id` = ?');
	if ($stmt) {
		mysqli_stmt_bind_param($stmt, 'i', $id);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		redirect('list.php?t=' . urlencode($key) . '&ok=deleted');
	}
	error_log('admin delete failed: ' . mysqli_error($db));
	http_response_code(500);
	exit('O\'chirishda xatolik.');
}

// ---- Tasdiqlash sahifasi (GET) ----
$label = '#' . $id;
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
	if (!empty($row['title'])) {
		$label = $row['title'];
	}
}

$pageTitle = 'O\'chirish — ' . $cfg['label'];
require __DIR__ . '/inc/header.php';
?>
<h1 class="adm-h1">O'chirishni tasdiqlang</h1>
<p class="adm-lead">
	Quyidagi yozuv butunlay o'chiriladi:
	<strong><?= e(mb_strlen($label) > 120 ? mb_substr($label, 0, 120) . '…' : $label) ?></strong>
	(<?= e($cfg['label']) ?>, id <?= e($id) ?>).
</p>
<p class="adm-muted">Bu amalni ortga qaytarib bo'lmaydi.</p>

<form method="post" action="delete.php?t=<?= e(urlencode($key)) ?>">
	<?= csrf_field() ?>
	<input type="hidden" name="id" value="<?= e($id) ?>">
	<div class="adm-form-actions">
		<button type="submit" class="adm-btn adm-btn-danger">Ha, o'chirish</button>
		<a class="adm-btn adm-btn-ghost" href="list.php?t=<?= e(urlencode($key)) ?>">Bekor qilish</a>
	</div>
</form>
<?php require __DIR__ . '/inc/footer.php'; ?>
