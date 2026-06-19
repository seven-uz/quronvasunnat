<?php
require __DIR__ . '/inc/bootstrap.php';
require_login();

$key = $_GET['t'] ?? '';
$cfg = table_config($key);
if ($cfg === null) {
	http_response_code(404);
	exit('Bunday bo\'lim yo\'q.');
}

$pageTitle = $cfg['label'];
$listCols = $cfg['list']; // ishonchli (allow-list konfiguratsiyasidan)

// Qidiruv ustuni: 'title' bo'lsa o'shani, aks holda id'dan boshqa birinchi ustun.
$searchCol = null;
if (in_array('title', $listCols, true)) {
	$searchCol = 'title';
} else {
	foreach ($listCols as $c) {
		if ($c !== 'id') { $searchCol = $c; break; }
	}
}

$q = trim($_GET['q'] ?? '');
$page = max(1, (int)($_GET['p'] ?? 1));
$perPage = 30;
$offset = ($page - 1) * $perPage;

$hasId = in_array('id', $listCols, true);
$selectCols = '`' . implode('`, `', $listCols) . '`';

// WHERE (faqat qidiruv bo'lsa, tayyorlangan so'rov bilan).
$where = '';
$useSearch = ($q !== '' && $searchCol !== null);
if ($useSearch) {
	$where = ' WHERE `' . $searchCol . '` LIKE ?';
}

// Umumiy son.
$total = 0;
$countSql = 'SELECT COUNT(*) AS c FROM `' . $key . '`' . $where;
$stmt = mysqli_prepare($db, $countSql);
if ($stmt) {
	if ($useSearch) {
		$like = '%' . $q . '%';
		mysqli_stmt_bind_param($stmt, 's', $like);
	}
	mysqli_stmt_execute($stmt);
	$r = mysqli_stmt_get_result($stmt);
	if ($r && ($row = mysqli_fetch_assoc($r))) {
		$total = (int)$row['c'];
	}
	mysqli_stmt_close($stmt);
}
$pages = max(1, (int)ceil($total / $perPage));

// Yozuvlar.
$rows = [];
$order = $hasId ? ' ORDER BY `id` DESC' : '';
$sql = 'SELECT ' . $selectCols . ' FROM `' . $key . '`' . $where . $order . ' LIMIT ? OFFSET ?';
$stmt = mysqli_prepare($db, $sql);
if ($stmt) {
	if ($useSearch) {
		$like = '%' . $q . '%';
		mysqli_stmt_bind_param($stmt, 'sii', $like, $perPage, $offset);
	} else {
		mysqli_stmt_bind_param($stmt, 'ii', $perPage, $offset);
	}
	mysqli_stmt_execute($stmt);
	$res = mysqli_stmt_get_result($stmt);
	while ($res && ($row = mysqli_fetch_assoc($res))) {
		$rows[] = $row;
	}
	mysqli_stmt_close($stmt);
} else {
	error_log('admin list query failed: ' . mysqli_error($db));
}

$flash = $_GET['ok'] ?? '';

require __DIR__ . '/inc/header.php';
?>
<div class="adm-head-row">
	<div>
		<a class="adm-back" href="index.php">&larr; Bosh sahifa</a>
		<h1 class="adm-h1"><?= e($cfg['label']) ?></h1>
	</div>
	<a class="adm-btn adm-btn-primary" href="edit.php?t=<?= e(urlencode($key)) ?>">+ Yangi qo'shish</a>
</div>

<?php if ($flash === 'saved'): ?>
	<div class="adm-alert adm-alert-ok">Saqlandi.</div>
<?php elseif ($flash === 'deleted'): ?>
	<div class="adm-alert adm-alert-ok">O'chirildi.</div>
<?php endif; ?>

<form class="adm-search" method="get" action="list.php">
	<input type="hidden" name="t" value="<?= e($key) ?>">
	<input type="text" name="q" value="<?= e($q) ?>" placeholder="Qidirish...">
	<button type="submit" class="adm-btn">Qidirish</button>
	<?php if ($q !== ''): ?>
		<a class="adm-btn adm-btn-ghost" href="list.php?t=<?= e(urlencode($key)) ?>">Tozalash</a>
	<?php endif; ?>
</form>

<p class="adm-muted"><?= e($total) ?> ta yozuv</p>

<div class="adm-table-wrap">
	<table class="adm-table">
		<thead>
			<tr>
				<?php foreach ($listCols as $c): ?>
					<th><?= e($c) ?></th>
				<?php endforeach; ?>
				<th class="adm-actions-col">Amallar</th>
			</tr>
		</thead>
		<tbody>
			<?php if (empty($rows)): ?>
				<tr><td colspan="<?= count($listCols) + 1 ?>" class="adm-empty">Yozuvlar topilmadi.</td></tr>
			<?php else: ?>
				<?php foreach ($rows as $row): ?>
					<tr>
						<?php foreach ($listCols as $c): ?>
							<td><?php
								$v = (string)($row[$c] ?? '');
								if (mb_strlen($v) > 90) { $v = mb_substr($v, 0, 90) . '…'; }
								echo e($v);
							?></td>
						<?php endforeach; ?>
						<td class="adm-actions-col">
							<?php if ($hasId): ?>
								<a class="adm-link" href="edit.php?t=<?= e(urlencode($key)) ?>&id=<?= e((int)$row['id']) ?>">Tahrir</a>
								<a class="adm-link adm-link-danger" href="delete.php?t=<?= e(urlencode($key)) ?>&id=<?= e((int)$row['id']) ?>">O'chirish</a>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
</div>

<?php if ($pages > 1): ?>
	<nav class="adm-pager">
		<?php for ($i = 1; $i <= $pages; $i++): ?>
			<?php if ($i === $page): ?>
				<span class="adm-page adm-page-active"><?= e($i) ?></span>
			<?php else: ?>
				<a class="adm-page" href="list.php?t=<?= e(urlencode($key)) ?>&p=<?= e($i) ?><?= $q !== '' ? '&q=' . e(urlencode($q)) : '' ?>"><?= e($i) ?></a>
			<?php endif; ?>
		<?php endfor; ?>
	</nav>
<?php endif; ?>
<?php require __DIR__ . '/inc/footer.php'; ?>
