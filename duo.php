<?
require 'blocks/brain.php';
include 'functions.php';

$page = "index";
$pageTitle = "Duo";
$headerImg = "dua.webp";
$headerColor = "linear-gradient(143deg, rgba(0,0,0,1) 0%, rgba(91,65,40,1) 100%);";

$duoDaily = mysqli_query($db, "SELECT * FROM duolar WHERE type=1");

include 'blocks/head.php';
include 'blocks/nav.php';
include 'blocks/header.php';
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
									<?word($row201['mano'])?></b></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section id="top-sale">
		<div class="container py-5">
			<h4 class="font-rubik font-size-20">Top Sale</h4>
			<hr>
			<!-- owl carousel -->
			<div class="owl-carousel" id="first-owl">
				<div class="item py-2">
					<div class="product font-rale">
						<a href="product.php'"><img src="assets/images/ok.jpg" alt="product1" class="img-fluid"></a>
						<div class="text-center">
							<h6>Lorem ipsum dolor sit.</h6>
							<div class="rating text-warning font-size-12">
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="far fa-star"></i></span>
							</div>
							<div class="price py-2">
								<span>$ 433.21</span>
							</div>
							<form method="post">
								<button type="submit" disabled class="btn btn-success font-size-12">In the Cart</button>
							</form>
						</div>
					</div>
				</div>
				<div class="item py-2">
					<div class="product font-rale">
						<a href="product.php'"><img src="assets/images/ok.jpg" alt="product1" class="img-fluid"></a>
						<div class="text-center">
							<h6>Lorem ipsum dolor sit.</h6>
							<div class="rating text-warning font-size-12">
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="far fa-star"></i></span>
							</div>
							<div class="price py-2">
								<span>$ 433.21</span>
							</div>
							<form method="post">
								<button type="submit" disabled class="btn btn-success font-size-12">In the Cart</button>
							</form>
						</div>
					</div>
				</div>
				<div class="item py-2">
					<div class="product font-rale">
						<a href="product.php'"><img src="assets/images/ok.jpg" alt="product1" class="img-fluid"></a>
						<div class="text-center">
							<h6>Lorem ipsum dolor sit.</h6>
							<div class="rating text-warning font-size-12">
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="far fa-star"></i></span>
							</div>
							<div class="price py-2">
								<span>$ 433.21</span>
							</div>
							<form method="post">
								<button type="submit" disabled class="btn btn-success font-size-12">In the Cart</button>
							</form>
						</div>
					</div>
				</div>
				<div class="item py-2">
					<div class="product font-rale">
						<a href="product.php'"><img src="assets/images/ok.jpg" alt="product1" class="img-fluid"></a>
						<div class="text-center">
							<h6>Lorem ipsum dolor sit.</h6>
							<div class="rating text-warning font-size-12">
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="far fa-star"></i></span>
							</div>
							<div class="price py-2">
								<span>$ 433.21</span>
							</div>
							<form method="post">
								<button type="submit" disabled class="btn btn-success font-size-12">In the Cart</button>
							</form>
						</div>
					</div>
				</div>
				<div class="item py-2">
					<div class="product font-rale">
						<a href="product.php'"><img src="assets/images/ok.jpg" alt="product1" class="img-fluid"></a>
						<div class="text-center">
							<h6>Lorem ipsum dolor sit.</h6>
							<div class="rating text-warning font-size-12">
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="far fa-star"></i></span>
							</div>
							<div class="price py-2">
								<span>$ 433.21</span>
							</div>
							<form method="post">
								<button type="submit" disabled class="btn btn-success font-size-12">In the Cart</button>
							</form>
						</div>
					</div>
				</div>
				<div class="item py-2">
					<div class="product font-rale">
						<a href="product.php'"><img src="assets/images/ok.jpg" alt="product1" class="img-fluid"></a>
						<div class="text-center">
							<h6>Lorem ipsum dolor sit.</h6>
							<div class="rating text-warning font-size-12">
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="far fa-star"></i></span>
							</div>
							<div class="price py-2">
								<span>$ 433.21</span>
							</div>
							<form method="post">
								<button type="submit" disabled class="btn btn-success font-size-12">In the Cart</button>
							</form>
						</div>
					</div>
				</div>
				<div class="item py-2">
					<div class="product font-rale">
						<a href="product.php'"><img src="assets/images/ok.jpg" alt="product1" class="img-fluid"></a>
						<div class="text-center">
							<h6>Lorem ipsum dolor sit.</h6>
							<div class="rating text-warning font-size-12">
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="far fa-star"></i></span>
							</div>
							<div class="price py-2">
								<span>$ 433.21</span>
							</div>
							<form method="post">
								<button type="submit" disabled class="btn btn-success font-size-12">In the Cart</button>
							</form>
						</div>
					</div>
				</div>
				<div class="item py-2">
					<div class="product font-rale">
						<a href="product.php'"><img src="assets/images/ok.jpg" alt="product1" class="img-fluid"></a>
						<div class="text-center">
							<h6>Lorem ipsum dolor sit.</h6>
							<div class="rating text-warning font-size-12">
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="far fa-star"></i></span>
							</div>
							<div class="price py-2">
								<span>$ 433.21</span>
							</div>
							<form method="post">
								<button type="submit" disabled class="btn btn-success font-size-12">In the Cart</button>
							</form>
						</div>
					</div>
				</div>
				<div class="item py-2">
					<div class="product font-rale">
						<a href="product.php'"><img src="assets/images/ok.jpg" alt="product1" class="img-fluid"></a>
						<div class="text-center">
							<h6>Lorem ipsum dolor sit.</h6>
							<div class="rating text-warning font-size-12">
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="far fa-star"></i></span>
							</div>
							<div class="price py-2">
								<span>$ 433.21</span>
							</div>
							<form method="post">
								<button type="submit" disabled class="btn btn-success font-size-12">In the Cart</button>
							</form>
						</div>
					</div>
				</div>
				<div class="item py-2">
					<div class="product font-rale">
						<a href="product.php'"><img src="assets/images/ok.jpg" alt="product1" class="img-fluid"></a>
						<div class="text-center">
							<h6>Lorem ipsum dolor sit.</h6>
							<div class="rating text-warning font-size-12">
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="far fa-star"></i></span>
							</div>
							<div class="price py-2">
								<span>$ 433.21</span>
							</div>
							<form method="post">
								<button type="submit" disabled class="btn btn-success font-size-12">In the Cart</button>
							</form>
						</div>
					</div>
				</div>
				<div class="item py-2">
					<div class="product font-rale">
						<a href="product.php'"><img src="assets/images/ok.jpg" alt="product1" class="img-fluid"></a>
						<div class="text-center">
							<h6>Lorem ipsum dolor sit.</h6>
							<div class="rating text-warning font-size-12">
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="fas fa-star"></i></span>
								<span><i class="far fa-star"></i></span>
							</div>
							<div class="price py-2">
								<span>$ 433.21</span>
							</div>
							<form method="post">
								<button type="submit" disabled class="btn btn-success font-size-12">In the Cart</button>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- !owl carousel -->
		</div>
	</section>
	<section class="section">
		<div class="container">
			<div class="row owl-carousel" id="second-owl">
				<div class="p-2">
					<div class="card p-2">
						<img src="assets/images/ok.jpg" class="img-fluid" alt="">
						<h4>Loresssssm ipsum dolor.</h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Expedita, voluptatum!</p>
					</div>
				</div>
				<div class="p-2">
					<div class="card p-2">
						<img src="assets/images/ok.jpg" class="img-fluid" alt="">
						<h4>Lorem ipsum dolor.</h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Expedita, voluptatum!</p>
					</div>
				</div>
				<div class="p-2">
					<div class="card p-2">
						<img src="assets/images/ok.jpg" class="img-fluid" alt="">
						<h4>Lorem ipsum dolor.</h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Expedita, voluptatum!</p>
					</div>
				</div>
				<div class="p-2">
					<div class="card p-2">
						<img src="assets/images/ok.jpg" class="img-fluid" alt="">
						<h4>Lorem ipsum dolor.</h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Expedita, voluptatum!</p>
					</div>
				</div>
				<div class="p-2">
					<div class="card p-2">
						<img src="assets/images/ok.jpg" class="img-fluid" alt="">
						<h4>Lorem ipsum dolor.</h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Expedita, voluptatum!</p>
					</div>
				</div>
				<div class="p-2">
					<div class="card p-2">
						<img src="assets/images/ok.jpg" class="img-fluid" alt="">
						<h4>Lorem ipsum dolor.</h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Expedita, voluptatum!</p>
					</div>
				</div>
				<div class="p-2">
					<div class="card p-2">
						<img src="assets/images/ok.jpg" class="img-fluid" alt="">
						<h4>Lorem ipsum dolor.</h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Expedita, voluptatum!</p>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="section" id="lastDuo">
		<?
					$result51 = mysqli_query($db,get('*','duolar'));
					while ($row51 = $result51->fetch_assoc()){
						$typeQ = mysqli_query($db,getAll("types WHERE id=$row51[type]"));

					}

					$update = mysqli_query($db,update('duolar','id=3','id=14'));

					$result5 = mysqli_query($db,getAllOrderLimit('duolar','id',9));
					$row5 = mysqli_fetch_assoc($result5);
				?>
		<div class="container">
			<h4 class="font-rubik font-size-20">Songi duolar</h4>
			<hr>
			<div class="grid">
				<?do{?>
				<div class="grid-item">
					<div class="card">
						<div class="card-header"><?= $row5['title'] ?></div>
						<div class="card-body">
							<p class="arabicText"><?= $row5['textar'] ?></p>
							<p><?= $row5['mano'] ?></p>
						</div>
						<div class="card-footer">
							<i class="fas fa-copy"></i>
						</div>
					</div>
				</div>
				<?}while($row5 = mysqli_fetch_assoc($result5));?>
			</div>
		</div>
	</section>
	<section class="section">
		<div class="container">
			<div class="row">
				<div class="col-4 p-2">
					<div class="card p-2" id="okko">
						<img src="assets/images/ok.jpg" class="img-fluid" alt="">
						<h4>Loremssss ipsum dolor.</h4>
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