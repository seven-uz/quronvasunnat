<?
include 'functions.php';

$page = "index";
$pageTitle = "Duo";
$headerImg = "dua.jpg";

$duoDaily = mysqli_query($db, "SELECT * FROM duolar WHERE type=1");

include 'blocks/head.php';
include 'blocks/header2.php';

?>
		<main>
			<section class="section">
				<div class="container">
					<div class="row">
						<div class="col-4 p-2">
							<div class="card p-2">
								<img src="assets/images/ok.jpg" class="img-fluid" alt="">
								<h4>Lorem ipsum dolor.</h4>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Expedita, voluptatum!</p>
							</div>
						</div>
						<div class="col-4 p-2">
							<div class="card p-2">
								<img src="assets/images/ok.jpg" class="img-fluid" alt="">
								<h4>Lorem ipsum dolor.</h4>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Expedita, voluptatum!</p>
							</div>
						</div>
						<div class="col-4 p-2">
							<div class="card p-2">
								<img src="assets/images/ok.jpg" class="img-fluid" alt="">
								<h4>Lorem ipsum dolor.</h4>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Expedita, voluptatum!</p>
							</div>
						</div>
					</div>
				</div>
			</section>
		</main>
		<?include 'blocks/footer.php'?>
	</body>
</html>