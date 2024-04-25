<?
require 'blocks/brain.php';
require 'functions.php';

$page = "hadis";
$pageTitle = word('Hadis');
$headerImg = "zakat.webp";
$headerColor = "linear-gradient(to right top, #a69c96, #b19d8d, #b89f84, #bda379, #bea76f, #bdaa65, #bbae5b, #b6b252, #b0b646, #a7ba39, #9cbe2d, #8fc31f);";

require 'blocks/head.php';
require 'blocks/header.php';

?>
<main>
	<section class="section" id="lastHadis">
		<div class="container">
			<?php if(isset($_GET['id'])):
				$lastHadis = mysqli_query($db, "SELECT h.*, rv.name as roviy, rvch.name as rivoyatchi, k.title as kunya FROM hadislar h LEFT JOIN roviylar rv ON h.id_roviy = rv.id LEFT JOIN rivoyatchilar rvch ON h.id_rivoyatchi = rvch.id LEFT JOIN kunyalar k ON rvch.id_kunya = k.id WHERE h.id = $_GET[id]");
				$row = $lastHadis->fetch_assoc();
			?>
				<h3><?php echo word($row['title']) ?></h3>
			<?php else: ?>
				<h4 class="h2 d-flex justify-md-content-center justify-content-between align-items-center"><?php echo word('Hadislar') ?></h4>
			<?php endif; ?>
				<?php
				if(isset($_GET['id'])){
					echo '<div>
					<p>'.word($row['rivoyatchi'].' '.$row['kunya']) .word('dan rivoyat qilinadi').':</p>
					<p class="card-text mb-4">'.word($row['titlear']).'</p>
					<p class="card-text mb-4">'.word($row['textar']).'</p>
					<p class="card-text mb-4">'.word($row['text']).'</p>
					<p class="card-text mb-4">'.word($row['mano']).'</p>
					</div>';
				}else{
					echo '<div class="row">';
					$lastHadis = mysqli_query($db, "SELECT * FROM hadislar ORDER BY id DESC LIMIT 12");
					while($row = $lastHadis->fetch_assoc()){
					echo '
						<div class="col-lg-4 mb-3 d-flex align-items-stretch">
							<div class="card">
								<div class="card-body d-flex flex-column">
									<h5 class="card-title">'.word($row['title']).'</h5>
									<p class="card-text mb-4">'.mb_strimwidth(word($row['text']), 0, 80, '...').'</p>
									<a href="hadis?id='.$row['id'].'" class="btn btn-primary mt-auto align-self-start">'.word('To‘liq ko‘rish').'</a>
								</div>
							</div>
							</div>';
						}
					echo '</div>';
				}
				?>
		</div>
	</section>
</main>

<?include 'blocks/footer.php'?>
</body>

</html>