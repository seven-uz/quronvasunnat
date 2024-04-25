<?
require 'blocks/brain.php';
require 'functions.php';

$page = "iymon";
$pageTitle = word('Iymon');
$headerImg = "iymon.webp";
$headerColor = "linear-gradient(to right top, #000000, #392123, #6f3d37, #a45f42, #d28948, #de984c, #e9a951, #f3b956, #e8ab5c, #dc9e60, #cd9264, #bc8767);";

$duoDaily = mysqli_query($db, "SELECT * FROM duolar WHERE type=1");

require 'blocks/head.php';
require 'blocks/header.php';

?>
<main>
	<section>
		<div class="container">
			<div class="row sameHeight">
				<div class="col-md-6">
					<div class="card w-100">
						<?
								$result1 = mysqli_query($db, "SELECT count(*) FROM suralar");
								$row1 = mysqli_fetch_row($result1);
								$rand = mt_rand(0,$row1[0] - 1);

								$result101 = mysqli_query($db, "SELECT * FROM suralar LIMIT $rand, 1");
								$row101 = mysqli_fetch_assoc($result101);
								?>
						<div class="card-header d-flex justify-content-between">
							<span class="btn btn-transparent disabled mr-5">Tasodifiy oyat</span>
							<div class="randBtns">
								<i class="fas fa-sync-alt" id="randDuoBtn"></i>
							</div>
							<div class="randBtns">
								<i class="fas fa-share-alt" id="shareAyah"></i>
								<i class="fas fa-copy" id="copyAyah"></i>
								<a href="quron?translate=<?= $row101['ns']; ?>#<?= $row101['no']; ?>"><i class="fas fa-link" id="randAyahBtn"></i></a>
							</div>
						</div>
						<div class="card-body" id="reloadableContent">

						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="card w-100">
						<?
								$result2 = mysqli_query($db, "SELECT count(*) FROM duolar");
								$row2 = mysqli_fetch_row($result2);
								$rand2 = mt_rand(0,$row2[0] - 1);

								$result201 = mysqli_query($db, "SELECT * FROM duolar LIMIT $rand2, 1");
								$row201 = mysqli_fetch_assoc($result201);
								?>
						<div class="card-header d-flex justify-content-between">
							<span class="btn btn-transparent disabled mr-5">Tasodifiy oyat</span>
							<div class="randBtns">
								<i class="fas fa-sync-alt" id="randDuoBtn"></i>
							</div>
							<div class="randBtns">
								<i class="fas fa-share-alt" id="shareDuo"></i>
								<i class="fas fa-copy" id="copyDuo"></i>
								<a href="quron?translate=<?= $row101['ns']; ?>#<?= $row101['no']; ?>"><i class="fas fa-link" id="randAyahBtn"></i></a>
							</div>
						</div>
						<div class="card-body">
							<h5 class="card-title"><?= $row201['titlear'] ?></h5>
							<p id="randDuo" class="py-4"><?= $row201['textar']; ?></p>
							<p class="mano"><b>
									<? echo word($row201['mano']) ?></b></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>
<?include 'blocks/footer.php'?>
<script src="assets/js/pages/index.js"></script>
</body>

</html>