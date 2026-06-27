<?
require 'blocks/brain.php';
require 'functions.php';

$page = "sunnat";
$pageTitle = word('Sunnat');
$headerImg = "koran.webp";
$headerColor = "linear-gradient(109.6deg, rgba(9,15,33,1) 16%, #233E67 91.1%);";

require 'blocks/head.php';
require 'blocks/header.php';
?>
<main id="main-content" tabindex="-1">
	<section class="section" id="lastSunnat">
		<div class="container">
			<?php if(isset($_GET['id'])):
				$sid = intval($_GET['id']);
				$res = mysqli_query($db, "SELECT * FROM sunnatlar WHERE id = $sid");
				$row = $res ? $res->fetch_assoc() : null;
				if($row): ?>
					<h3 class="mb-4"><?php echo word($row['title']) ?></h3>
					<div class="content-item">
						<div class="body-item">
							<p><?php echo word($row['text']) ?></p>
						</div>
					</div>
					<div class="mt-4 d-flex flex-wrap align-items-center">
						<a href="sunnat" class="btn btn-outline-primary mr-2 mb-2"><i class="fas fa-arrow-left mr-2"></i><?php echo word('Barcha sunnatlar') ?></a>
						<button class="btn btn-outline-danger fav-btn mr-2 mb-2"
							data-type="sunnat"
							data-id="<?= $sid ?>"
							data-title="<?= htmlspecialchars(word($row['title']), ENT_QUOTES, 'UTF-8') ?>"
							data-text="<?= htmlspecialchars(mb_substr(word($row['text']), 0, 150), ENT_QUOTES, 'UTF-8') ?>"
							aria-pressed="false">
							<i class="far fa-heart" aria-hidden="true"></i> <?= word('Sevimliga qo\'shish') ?>
						</button>
						<button class="btn btn-outline-secondary share-btn mb-2"
							data-title="<?= htmlspecialchars(word($row['title']), ENT_QUOTES, 'UTF-8') ?>"
							data-text="<?= htmlspecialchars(mb_substr(word($row['text']), 0, 200), ENT_QUOTES, 'UTF-8') ?>"
							aria-label="<?= word('Ulashish') ?>">
							<i class="fas fa-share-alt" aria-hidden="true"></i> <?= word('Ulashish') ?>
						</button>
					</div>
				<?php else: ?>
					<div class="empty-state">
						<i class="fas fa-mosque"></i>
						<h2><?php echo word('Sunnat topilmadi') ?></h2>
						<p><?php echo word('Bunday yozuv mavjud emas yoki o'chirilgan.') ?></p>
						<a href="sunnat" class="btn btn-primary"><?php echo word('Barcha sunnatlar') ?></a>
					</div>
				<?php endif;
			else: ?>
				<h4 class="h2 d-flex justify-md-content-center justify-content-between align-items-center"><?php echo word('Sunnatlar') ?></h4>
				<?php
				$list = mysqli_query($db, "SELECT * FROM sunnatlar ORDER BY id DESC LIMIT 60");
				if($list && mysqli_num_rows($list) > 0){
					echo '<div class="row">';
					while($row = $list->fetch_assoc()){
						echo '<div class="col-lg-4 mb-3 d-flex align-items-stretch">
							<div class="card">
								<div class="card-body d-flex flex-column">
									<h5 class="card-title">'.word($row['title']).'</h5>
									<p class="card-text mb-4">'.mb_strimwidth(word($row['text']), 0, 90, '...').'</p>
									<a href="sunnat?id='.$row['id'].'" class="btn btn-primary mt-auto align-self-start">'.word('To'liq ko'rish').'</a>
								</div>
							</div>
						</div>';
					}
					echo '</div>';
				}else{
					echo '<div class="empty-state">
						<i class="fas fa-mosque"></i>
						<h2>'.word('Ma'lumot yo'q').'</h2>
						<p>'.word('Bu bo'lim hozircha tayyorlanmoqda. Tez orada ma'lumotlar bilan to'ldiriladi, insha'Alloh.').'</p>
						<a href="/" class="btn btn-primary">'.word('Bosh sahifaga qaytish').'</a>
					</div>';
				}
				?>
			<?php endif; ?>
		</div>
	</section>
</main>
<?include 'blocks/footer.php'?>
</body>

</html>
