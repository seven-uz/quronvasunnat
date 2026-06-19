<?php
require __DIR__ . '/inc/bootstrap.php';
require_login();

$pageTitle = 'Bosh sahifa';

// Har jadval uchun yozuvlar soni (kalit allow-list'dan kelgani uchun xavfsiz).
$counts = [];
foreach ($ADMIN_TABLES as $key => $cfg) {
	$res = mysqli_query($db, 'SELECT COUNT(*) AS c FROM `' . $key . '`');
	if ($res && ($row = mysqli_fetch_assoc($res))) {
		$counts[$key] = (int)$row['c'];
	} else {
		$counts[$key] = null; // jadval yo'q yoki xato
	}
}

require __DIR__ . '/inc/header.php';
?>
<h1 class="adm-h1">Kontent boshqaruvi</h1>
<p class="adm-lead">Quyidagi bo'limlarni tahrirlashingiz mumkin.</p>

<div class="adm-cards">
	<?php foreach ($ADMIN_TABLES as $key => $cfg): ?>
		<a class="adm-card" href="list.php?t=<?= e(urlencode($key)) ?>">
			<span class="adm-card-title"><?= e($cfg['label']) ?></span>
			<span class="adm-card-count">
				<?= $counts[$key] === null ? '—' : e($counts[$key]) ?>
				<small>yozuv</small>
			</span>
		</a>
	<?php endforeach; ?>
</div>
<?php require __DIR__ . '/inc/footer.php'; ?>
