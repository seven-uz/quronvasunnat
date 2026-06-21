<?
require 'blocks/brain.php';
require 'functions.php';

$page = "search";
$pageTitle = word('Qidiruv');
$headerImg = "koran.webp";
$headerColor = "linear-gradient(109.6deg, rgba(9,15,33,1) 16%, #233E67 91.1%);";

require 'blocks/head.php';
require 'blocks/header.php';

// Qidiruv so'zi GET (?q=) yoki POST orqali kelishi mumkin.
$q = trim($_REQUEST['q'] ?? ($_REQUEST['term'] ?? ''));
$qSafe = htmlspecialchars($q, ENT_QUOTES, 'UTF-8');

/**
 * Bitta jadval ustida xavfsiz (prepared) LIKE qidiruv.
 * $cols — qidiriladigan ustunlar; barchasi bitta `$like` qiymatga bog'lanadi.
 */
function searchTable($db, $table, $cols, $like){
	$where = implode(' OR ', array_map(fn($c) => "`$c` LIKE ?", $cols));
	$sql = "SELECT * FROM `$table` WHERE $where LIMIT 30";
	$rows = array();
	if($stmt = mysqli_prepare($db, $sql)){
		$types = str_repeat('s', count($cols));
		$params = array_fill(0, count($cols), $like);
		mysqli_stmt_bind_param($stmt, $types, ...$params);
		if(mysqli_stmt_execute($stmt)){
			$res = mysqli_stmt_get_result($stmt);
			while($r = mysqli_fetch_assoc($res)) $rows[] = $r;
		}
		mysqli_stmt_close($stmt);
	}
	return $rows;
}

$groups = array();
$total = 0;
if(mb_strlen($q) >= 2){
	$like = '%'.$q.'%';
	$groups = array(
		array('label' => word('Suralar / oyatlar'), 'rows' => searchTable($db, 'suralar', array('mano','textar','title'), $like),
			'link' => fn($r) => 'quron?sura='.intval($r['ns']).'#'.intval($r['no']),
			'title' => fn($r) => word(($r['title'] ?? '').' surasi '.($r['no'] ?? '').'-oyat'),
			'text'  => fn($r) => word($r['mano'] ?? '')),
		array('label' => word('Duolar'), 'rows' => searchTable($db, 'duolar', array('title','text','mano'), $like),
			'link' => fn($r) => 'duo?id='.intval($r['id']),
			'title' => fn($r) => word($r['title'] ?? ''),
			'text'  => fn($r) => word($r['mano'] ?? '')),
		array('label' => word('Hadislar'), 'rows' => searchTable($db, 'hadislar', array('title','text','mano'), $like),
			'link' => fn($r) => 'hadis?id='.intval($r['id']),
			'title' => fn($r) => word($r['title'] ?? ''),
			'text'  => fn($r) => word($r['text'] ?? '')),
		array('label' => word('Sunnatlar'), 'rows' => searchTable($db, 'sunnatlar', array('title','text'), $like),
			'link' => fn($r) => 'sunnat?id='.intval($r['id']),
			'title' => fn($r) => word($r['title'] ?? ''),
			'text'  => fn($r) => word($r['text'] ?? '')),
		array('label' => word('Asma ul-Husna'), 'rows' => searchTable($db, 'asmaulhusna', array('title','text'), $like),
			'link' => fn($r) => 'asmaulhusna#'.rawurlencode($r['title'] ?? ''),
			'title' => fn($r) => word($r['title'] ?? ''),
			'text'  => fn($r) => word($r['text'] ?? '')),
	);
	foreach($groups as $g) $total += count($g['rows']);
}
?>
<main id="main-content" tabindex="-1">
	<section class="section">
		<div class="container">
			<form action="search" method="get" class="d-flex mb-4" role="search">
				<input type="search" name="q" class="form-control mr-2" value="<?= $qSafe ?>" placeholder="<?php echo word('Qidirish...') ?>" aria-label="<?php echo word('Qidiruv') ?>" autofocus>
				<button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
			</form>

			<?php if(mb_strlen($q) < 2): ?>
				<div class="empty-state">
					<i class="fas fa-search"></i>
					<h2><?php echo word('Qidiruv') ?></h2>
					<p><?php echo word('Qidirish uchun kamida 2 ta belgi kiriting.') ?></p>
				</div>
			<?php elseif($total === 0): ?>
				<div class="empty-state">
					<i class="fas fa-search"></i>
					<h2><?php echo word('Natija topilmadi') ?></h2>
					<p>«<strong><?= $qSafe ?></strong>» <?php echo word('bo‘yicha hech narsa topilmadi.') ?></p>
				</div>
			<?php else: ?>
				<p class="mano mb-4">«<strong><?= $qSafe ?></strong>» <?php echo word('bo‘yicha') ?> <strong><?= $total ?></strong> <?php echo word('ta natija') ?>.</p>
				<?php foreach($groups as $g): if(!count($g['rows'])) continue; ?>
					<h4 class="h5 mt-4 mb-3"><?= $g['label'] ?> <span class="mano">(<?= count($g['rows']) ?>)</span></h4>
					<div class="row">
						<?php foreach($g['rows'] as $r): ?>
							<div class="col-lg-6 mb-3 d-flex align-items-stretch">
								<div class="card w-100">
									<div class="card-body">
										<h5 class="card-title"><a href="<?= htmlspecialchars($g['link']($r), ENT_QUOTES, 'UTF-8') ?>"><?= $g['title']($r) ?></a></h5>
										<p class="card-text mb-0"><?= mb_strimwidth($g['text']($r), 0, 140, '...') ?></p>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</section>
</main>
<?include 'blocks/footer.php'?>
</body>

</html>
