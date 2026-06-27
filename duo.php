<?
require 'blocks/brain.php';
include 'functions.php';

$page = "index";
$pageTitle = "Duo";
$headerImg = "dua.webp";
$headerColor = "linear-gradient(143deg, rgba(0,0,0,1) 0%, rgba(91,65,40,1) 100%);";

if(isset($_GET['id'])){
	$duoId = intval($_GET['id']);
	$duoDaily = mysqli_query($db, "SELECT * FROM duolar WHERE id = $duoId");
	$rowDuo = $duoDaily->fetch_assoc();
}else{
	$duoDaily = mysqli_query($db, "SELECT * FROM duolar WHERE type=1");
}

include 'blocks/head.php';
include 'blocks/header.php';
?>
<main id="main-content" tabindex="-1">
	<?php if(isset($_GET['id'])): ?>
		<section class="section">
			<div class="container">
				<div class="d-flex justify-content-between">
					<h4><?php echo word($rowDuo['title']) ?></h4>
					<h4 style="text-align: right;"><?php echo $rowDuo['titlear'] ?></h4>
				</div>
				<p style="text-align: right;"><?php echo $rowDuo['textar'] ?></p>
				<p><?php echo word($rowDuo['text']) ?></p>
				<p><?php echo word($rowDuo['mano']) ?></p>
				<div class="d-flex flex-wrap align-items-center mt-3">
					<button class="btn btn-outline-danger fav-btn mr-2 mb-2"
						data-type="duo"
						data-id="<?= intval($rowDuo['id']) ?>"
						data-title="<?= htmlspecialchars(word($rowDuo['title']), ENT_QUOTES, 'UTF-8') ?>"
						data-text="<?= htmlspecialchars(mb_substr(word($rowDuo['mano']), 0, 150), ENT_QUOTES, 'UTF-8') ?>"
						aria-pressed="false">
						<i class="far fa-heart" aria-hidden="true"></i> <?= word('Sevimliga qo\'shish') ?>
					</button>
					<button class="btn btn-outline-secondary share-btn mb-2"
						data-title="<?= htmlspecialchars(word($rowDuo['title']), ENT_QUOTES, 'UTF-8') ?>"
						data-text="<?= htmlspecialchars($rowDuo['textar'] . ' — ' . mb_substr(word($rowDuo['mano']), 0, 200), ENT_QUOTES, 'UTF-8') ?>"
						aria-label="<?= word('Ulashish') ?>">
						<i class="fas fa-share-alt" aria-hidden="true"></i> <?= word('Ulashish') ?>
					</button>
				</div>
			</div>
		</section>
	<?php else: ?>
		<section class="section" id="lastDuo">
			<?
				$result5 = mysqli_query($db, getAllOrderLimit('duolar', 'id', 9));
				$row5 = mysqli_fetch_assoc($result5);
			?>
			<div class="container">
				<h4 class="font-rubik font-size-20"><?php echo word('So'ngi duolar') ?></h4>
				<hr>
				<div class="grid">
					<?php if($row5): do{ ?>
						<div class="grid-item">
							<div class="card">
								<div class="card-header"><?= word($row5['title']) ?></div>
								<div class="card-body">
									<p class="arabicText"><?= $row5['textar'] ?></p>
									<p><?= word($row5['mano']) ?></p>
								</div>
								<div class="card-footer">
									<i class="fas fa-copy"></i>
								</div>
							</div>
						</div>
					<?php }while($row5 = mysqli_fetch_assoc($result5)); endif; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>
</main>
<?include 'blocks/footer.php'?>
</body>

</html>
