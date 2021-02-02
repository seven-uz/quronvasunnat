<?
require 'blocks/brain.php';
require 'functions.php';
$page = "index";
$pageTitle = word('Asosiy sahifa');
$headerImg = "koran.webp";
$headerColor = "linear-gradient(109.6deg, rgba(9,15,33,1) 16%, #233E67 91.1% );";
require 'blocks/head.php';
require 'blocks/header.php';
?>
<div id="output22"></div>

<main>
	<section id="randomSection">
		<div class="container-fluid">
			<div class="row sameHeight">
				<div class="col-md-4">
					<div class="card w-100">
						<?$getSura = mysqli_query($db, "SELECT count(*) FROM suralar");
						$suraRand = mt_rand(0,$getSura->fetch_row()[0] - 1);
						$sura = mysqli_query($db, "SELECT * FROM suralar LIMIT $suraRand, 1");
						$suraRow = $sura->fetch_assoc();
						if($suraRow[ns] < 10) $audioFNs = '00'.$suraRow[ns].'/00'.$suraRow[ns];else
						if($suraRow[ns] > 10 and $suraRow[ns] < 100) $audioFNs = '0'.$suraRow[ns].'/0'.$suraRow[ns];else
						if($suraRow[ns] > 99) $audioFNs = $suraRow[ns].'/'.$suraRow[ns];
						if($suraRow[no] < 10) $audioFNo = '00'.$suraRow[no];else
						if($suraRow[no] > 10 and $suraRow[no] < 100) $audioFNo = '00'.$suraRow[no]-1;else
						if($suraRow[no] > 99) $audioFNo = '00'.$suraRow[no]-1;
						if($suraRow[ns] < 10) $ns = '00'.$suraRow[ns];else
						if($suraRow[ns] > 10 and $suraRow[ns] < 100) $ns = '0'.$suraRow[ns];else
						if($suraRow[ns] > 99) $ns = $suraRow[ns];
						
						if($suraRow['no'] < 10) $no = '00'.$suraRow['no'];else
						if($suraRow['no'] > 10 and $suraRow[no] < 100) $no = '0'.$suraRow['no'];else
						if($suraRow['no'] > 99) $no = $suraRow[no];
						$linkSura = 'https://everyayah.com/data/'.$_COOKIE['qori'].'/'.$ns.$no;
						// echo $linkSura;
						?>
						<div class="card-header p-2 d-flex justify-content-between">
							<span class="btn btn-transparent disabled mr-5">
								<? echo word('Tasodifiy oyat') ?>
							</span>
							<div class="randBtns">
								<i onclick="document.getElementById('s<?= $suraRow['ns'] ?>o<?= $suraRow['no'] ?>').play();document.getElementById('s<?= $suraRow['ns'] ?>o<?= $suraRow['no'] ?>').loop = false;" class="fas fa-play" id="playRS" data-toggle="tooltip" data-placement="top" title="<? echo word('Eshitish') ?>"></i>
								<i onclick="document.getElementById('s<?= $suraRow['ns'] ?>o<?= $suraRow['no'] ?>').pause();document.getElementById('s<?= $suraRow['ns'] ?>o<?= $suraRow['no'] ?>').loop = false;" class="fas fa-pause d-none" id="pauseRS" data-toggle="tooltip" data-placement="top" title="<? echo word('Qotirish') ?>"></i>
								<i onclick="document.getElementById('s<?= $suraRow['ns'] ?>o<?= $suraRow['no'] ?>').play();document.getElementById('s<?= $suraRow['ns'] ?>o<?= $suraRow['no'] ?>').loop = true;" class="fas fa-sync-alt" id="loopRS" data-toggle="tooltip" data-placement="top" title="<? echo word('Qayta-qayta eshitish') ?>"></i>
								<i onclick="document.getElementById('s<?= $suraRow['ns'] ?>o<?= $suraRow['no'] ?>').pause();document.getElementById('s<?= $suraRow['ns'] ?>o<?= $suraRow['no'] ?>').currentTime=0" class="fas fa-stop d-none" id="stopRS" data-toggle="tooltip" data-placement="top" title="<? echo word('To‘xtatish') ?>"></i>
								<audio id="s<?= $suraRow['ns'] ?>o<?= $suraRow['no'] ?>">
									<!-- <source src="<?= $linkSura ?>.ogg" type="audio/ogg"> -->
									<source src="<?= $linkSura ?>.mp3" type="audio/mpeg">
									<a href="<?= $linkSura ?>.mp3">Скачайте музыку</a>.
									Тег audio не поддерживается вашим браузером.
								</audio>
								<a href="https://t.me/share/url?url=<?= $_SERVER['SERVER_NAME'] ?>/quron?id=<?= $suraRow['no'] ?>&text=<?= $suraRow['textar'] . '<br>' . $suraRow['mano'] ?>"><i class="fas fa-share-alt" id="shareAyah" data-toggle="tooltip" data-placement="top" title="<? echo word('Do‘stlar bilan baham ko‘rish') ?>"></i></a>
								<i onclick="copytext('#copyRS<?= $suraRow['id'] ?>')" class="fas fa-copy" data-toggle="tooltip" data-placement="top" title="<? echo word('Matnni ko‘chirib olish') ?>"></i>
								<p style="display:none" id="copyRS<?= $suraRow['id'] ?>"><i style="text-align:right">
										<? echo $suraRow['textar'] . "</i>\n" . word('Maʼnosi') . ": " . $suraRow['mano'] . "\n[" . $suraRow['title'] . " surasi " . $suraRow['no'] . "-oyat]"; ?>
								</p>
							</div>
						</div>
						<div class="card-body" id="">
							<div id="output"></div>
							<p id="randDuo"><?= $suraRow['textar']; ?></p>
							<p class="mano indent-20"><b>
									<? echo mb_strimwidth(word($suraRow['mano']), 0, 500, '...') ?></b><br>
								<a href="quron?sura=<?= $suraRow['ns'] ?>#<?= $suraRow['no']; ?>">[
									<? echo word($suraRow['title']) . ' ' . word('surasi') . ' ' . $suraRow['no'] . '-' . word('oyat') ?>]</a></p>
						</div>
					</div>
				</div>
				<div class="col-md-4 py-md-0 py-4">
					<div class="card w-100">
						<?$getDuo = mysqli_query($db, "SELECT count(*) FROM duolar");
						$duoRand = mt_rand(0,$getDuo->fetch_row()[0] - 1);
						$duo = mysqli_query($db, "SELECT * FROM duolar LIMIT $duoRand, 1");
						$duoRow = $duo->fetch_assoc();
						?>
						<div class="card-header d-flex justify-content-between">
							<span class="btn btn-transparent disabled mr-5">
								<? echo word('Tasodifiy duo') ?></span>
							<div class="randBtns">
								<i onclick="document.getElementById('d<?= $duoRow['id'] ?>').play();document.getElementById('d<?= $duoRow['id'] ?>').loop = false;" class="fas fa-play" id="playRD" data-toggle="tooltip" data-placement="top" title="<? echo word('Eshitish') ?>"></i>
								<i onclick="document.getElementById('d<?= $duoRow['id'] ?>').pause();document.getElementById('d<?= $duoRow['id'] ?>').loop = false;" class="fas fa-pause d-none" id="pauseRD" data-toggle="tooltip" data-placement="top" title="<? echo word('Qotirish') ?>"></i>
								<i onclick="document.getElementById('d<?= $duoRow['id'] ?>').play();document.getElementById('d<?= $duoRow['id'] ?>').loop = true;" class="fas fa-sync-alt" id="loopRD" data-toggle="tooltip" data-placement="top" title="<? echo word('Qayta-qayta eshitish') ?>"></i>
								<i onclick="document.getElementById('d<?= $duoRow['id'] ?>').pause();document.getElementById('d<?= $duoRow['id'] ?>').currentTime=0" class="fas fa-stop d-none" id="stopRD" data-toggle="tooltip" data-placement="top" title="<? echo word('To‘xtatish') ?>"></i>
								<audio id="d<?= $duoRow['id'] ?>">
									<source src="assets/audio/duo/<?= $duoRow['audio'] ?>" type="audio/mpeg">
									<a href="assets/audio/duo/<?= $duoRow['audio'] ?>">Скачайте музыку</a>.
									Тег audio не поддерживается вашим браузером.
								</audio>
								<i class="fas fa-share-alt" id="shareAyah" data-toggle="tooltip" data-placement="top" title="<? echo word('Do‘stlar bilan baham ko‘rish') ?>"></i>
								<i onclick="copytext('#copyRD<?= $duoRow['id'] ?>')" class="fas fa-copy" data-toggle="tooltip" data-placement="top" title="<? echo word('Matnni ko‘chirib olish') ?>"></i>
								<p style="display:none" id="copyRD<?= $duoRow['id'] ?>"><i style="text-align:right">
										<? echo $duoRow['textar'] . "</i>\n" . word($duoRow['text']) . "\n\n" . word('Manosi') . ": " . word($duoRow['mano']) ?>
								</p>
							</div>
						</div>
						<div class="card-body">
							<h5 class="card-title"><a href="duo?id=<?= $duoRow['id'] ?>">
									<? echo word($duoRow['title']) ?></a></h5>
							<p class="randDuo"><?= $duoRow['textar'] ?></p>
							<p class="mano indent-20"><b>
									<? echo word($duoRow['text']) ?></b></p>
							<p class="mano indent-20">
								<? echo word('Ma’nosi') ?>: <b>
									<? echo word($duoRow['mano']) ?></b></p>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="card w-100">
						<?$getSunnat = mysqli_query($db, "SELECT count(*) FROM sunnatlar");
						$sunnatRand = mt_rand(0,$getSunnat->fetch_row()[0] - 1);
						$sunnat = mysqli_query($db, "SELECT * FROM sunnatlar LIMIT $sunnatRand, 1");
						$sunnatRow = $sunnat->fetch_assoc();?>
						<div class="card-header d-flex justify-content-between">
							<span class="btn btn-transparent disabled mr-5">
								<? echo word('Tasodifiy sunnat') ?></span>
							<div class="randBtns">
								<i class="fas fa-share-alt" id="shareAyah" data-toggle="tooltip" data-placement="top" title="<? echo word('Do‘stlar bilan baham ko‘rish') ?>"></i>
								<i onclick="copytext('#copyRSun<?= $sunnatRow['id'] ?>')" class="fas fa-copy" data-toggle="tooltip" data-placement="top" title="<? echo word('Matnni ko‘chirib olish') ?>"></i>
								<p style="display:none" id="copyRSun<?= $sunnatRow['id'] ?>"><i>
										<? echo word($sunnatRow['title']) . "</i>\n\n" . word($sunnatRow['text']) ?>
								</p>
								<a href="sunnat?id=<?= $sunnatRow['id'] ?>"><i class="fas fa-link" data-toggle="tooltip" data-placement="top" title="<? echo word('To‘liq ko‘rish') ?>"></i></a>
							</div>
						</div>
						<div class="card-body">
							<h5 class="card-title">
								<? echo word($sunnatRow['title']) ?>
							</h5>
							<p class="mano"><b>
									<? echo word($sunnatRow['text']) ?></b></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="section" id="aboutKoran">
		<div class="container">
			<h4 class="text-center h2 d-flex justify-md-content-center justify-content-between align-items-center">
				<? echo word('Qur’on haqida') ?>...
				<span style="font-size:16px">
					<a href="quron">
						<span class="d-none d-md-block">
							<? echo word('To‘liq o‘qish')?></span>
						<i class="fas fa-angle-double-right d-md-none"></i>
					</a>
				</span>
			</h4>
			<div class="section-body text-justify indent-20">
				<?$quron = mysqli_query($db, 'SELECT * FROM quron WHERE id=1');
				$rowi = $quron->fetch_assoc();?>
				<div class="item">
					<h5>
						<? echo word($rowi['title']) ?>
					</h5>
					<p>
						<? echo word($rowi['text']) ?>
					</p>
				</div>
				<div class="item">
					<h6></h6>
					<p></p>
				</div>
				<div class="item">
					<h6></h6>
					<p></p>
				</div>
			</div>
		</div>
	</section>
	<section id="asmaulhusna">
		<div class="container">
			<h4 class="h2 d-flex justify-md-content-center justify-content-between align-items-center">
				<? echo word('Asma ul-Husna') ?>
				<span style="font-size:16px">
					<a href="asmaulhusna">
						<span class="d-none d-md-block">
							<? echo word('Barchasini ko‘rish')?></span>
						<i class="fas fa-angle-double-right d-md-none"></i>
					</a>
				</span>
			</h4>
			<!-- owl carousel -->
			<div class="owl-carousel" id="first-owl">
				<?$asmaulhusna = mysqli_query($db, "SELECT * FROM asmaulhusna ORDER BY RAND() LIMIT 30");
				while($row = $asmaulhusna->fetch_assoc()){
				echo '
				<a href="asmaulhusna?='.$row['id'].'">
					<div class="item">
						<div class="product font-rale">
							<h5>'.word($row['title']).'</h5>
							<p>'.mb_strimwidth(word($row['text']), 0, 100, '...').'</p>
						</div>
					</div>
				</a>
				';
				}?>
			</div>
		</div>
	</section>
	<section class="section" id="lastDuo">
		<div class="container">
			<h4 class="h2 d-flex justify-md-content-center justify-content-between align-items-center">
				<? echo word('So‘ngi qo‘shilgan duolar') ?>
				<span style="font-size:16px">
					<a href="duo">
						<span class="d-none d-md-block">
							<? echo word('Barchasini ko‘rish')?></span>
						<i class="fas fa-angle-double-right d-md-none"></i>
					</a>
				</span>
			</h4>
			<div class="grid">
				<?$duolar = mysqli_query($db,"SELECT * FROM duolar ORDER BY id DESC LIMIT 9");
				while($row = $duolar->fetch_assoc()){
				echo '<div class="grid-item">
					<div class="card">
						<div class="card-header">'.word($row['title']).'</div>
						<div class="card-body">
							<p class="arabicText">'.$row['textar'].'</p>
							<p>'.word($row['mano']).'</p>
						</div>
					</div>
				</div>';
				}?>
			</div>
		</div>
	</section>
	<section class="section" id="lastSunnat">
		<div class="container">
			<h4 class="h2 d-flex justify-md-content-center justify-content-between align-items-center">
				<? echo word('So‘ngi qo‘shilgan sunnatlar') ?>
				<span style="font-size:16px">
					<a href="sunnat">
						<span class="d-none d-md-block">
							<? echo word('Barchasini ko‘rish')?></span>
						<i class="fas fa-angle-double-right d-md-none"></i>
					</a>
				</span>
			</h4>
			<div class="row">
				<?$lastSunnat = mysqli_query($db, "SELECT * FROM sunnatlar ORDER BY id DESC LIMIT 9");
				while($row = $lastSunnat->fetch_assoc()){
				echo '<div class="col-md-4 p-2">
					<div class="card p-2">
						<img src="" class="img-fluid" alt="">
						<h4><a href="sunnat?id='.$row['id'].'">'.word($row['title']).'</a></h4>
						<p>'.mb_strimwidth(word($row['text']), 0, 80, '...').'</p>
					</div>
				</div>';
				}?>
			</div>
		</div>
	</section>
</main>

<?include 'blocks/footer.php'?>

<script src="assets/js/pages/index.js"></script>

</body>

</html>