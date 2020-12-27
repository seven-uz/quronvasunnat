<?
$url2 = 'http://api.aladhan.com/v1/gToH?date='.date("d-m-Y");
$ch2 = curl_init();
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch2, CURLOPT_URL, $url2);
$response2 = curl_exec($ch2);
$data2 = json_decode($response2, true);
curl_close($ch2);

$hijriMonthName = $data2['data']['hijri']['month']['en'];
$hijriMonthNameAr = $data2['data']['hijri']['month']['ar'];
$hijriDay = $data2['data']['hijri']['day'];
$hijriMonth = $data2['data']['hijri']['month']['number'];
$hijriYear = $data2['data']['hijri']['year'];
$hijriDayName = $data2['data']['hijri']['weekday']['en'];
$hijriDayNameAr = $data2['data']['hijri']['weekday']['ar'];

if($hijriMonthName == "Shawwāl") $hijriMonthName = "Shavvol";else
if($hijriMonthName == "Ṣafar") $hijriMonthName = "Safar";

require 'nav.php';
?>
<header id="header" <?if($_COOKIE['headerImg']=='on' ):?>style="background-image: url(assets/images/<?= $headerImg ?>);"
	<? else :?>style="background-image: <?= $headerColor ?>"
	<? endif ?>>
	<div class="dayLine">
		<div class="col-12">
			<div class="bg-white p-3 rounded d-md-flex justify-content-between">
				<h6 class="m-0 pb-md-0 pb-2">
					<? echo word('Milodiy: '.date("d").'-'.cmn(date("m")).' '.date("Y").'-yil. ').wn() ?>
				</h6>
				<h6 class="m-0 d-md-block d-none"><?= $words['bugun'] ?></h6>
				<h6 class="m-0">
					<? echo word('Xijriy: '.$hijriYear.'-yil '.$hijriDay.'-'.$hijriMonthName.'. '.$hijriDayName) ?>
				</h6>
			</div>
		</div>
	</div>
	<? if(date("N") == 5) :?>
	<div class="col-12 text-center">
		<div class="congr-juma">
			<? echo word('Juma ayyomingiz muborak bo‘lsin')?>
		</div>
	</div>
	<div class="d-flex justify-content-between">
		<div class="col-lg-6 px-4 pb-4">
			<div class="owl-carousel owl-theme owl-loaded bg-white rounded header-carousel juma">
				<div class="owl-stage-outer">
					<div class="owl-stage">
						<div class="owl-item">
							<div class="card-header p-3 d-flex align-items-center justify-content-between">
								<h6 class="card-title m-0">
									<? echo word("Juma sunnatlari") ?>
								</h6>
							</div>
							<div class="card-body">
								<p class="card-text indent-20">
									<strong>
										<? echo word('- G‘usl qilish; - Toza libos kiyish; - Tirnoq olish; - Misvok ishlatish; - Xo‘shbo‘ylanish; Payg‘ambarimiz Muhammad (S.A.V) ga salovot aytish; - Qur’on tilovati; Masjidga erta borish; - Duoni ko‘p qilish;') ?> </strong></p>
							</div>
						</div>
						<div class="owl-item">
							<div class="card-header p-3 d-flex align-items-center justify-content-between">
								<h6 class="card-title m-0">
									<? echo word("Juma sunnatlari") ?>
								</h6>
							</div>
							<div class="card-body">
								<p class="card-text indent-20">
									<strong>
										<? echo word(' erta borish; - Duoni ko‘p qilish;') ?> </strong></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-6 px-4 pb-4">
			<div class="owl-carousel owl-theme owl-loaded bg-white rounded header-carousel juma">
				<div class="owl-stage-outer">
					<div class="owl-stage">
						<div class="owl-item">
							<div class="card-header p-3 d-flex align-items-center justify-content-between">
								<h6 class="card-title m-0">
									<? echo word("Juma haqida hadislar") ?>
								</h6>
							</div>
							<div class="card-body">
								<p class="card-text indent-20">
									<strong>
										<? echo word('- G‘usl qi olish; - Misvok ishlatish; - Xo‘shbo‘ylanish; Payg‘ambarimiz Muhammad (S.A.V) ga salovot aytish; - Qur’on tilovati; Masjidga erta borish; - Duoni ko‘p qilish;') ?> </strong></p>
							</div>
						</div>
						<div class="owl-item">
							<div class="card-header p-3 d-flex align-items-center justify-content-between">
								<h6 class="card-title m-0">
									<? echo word("Juma sunnatlari") ?>
								</h6>
							</div>
							<div class="card-body">
								<p class="card-text indent-20">
									<strong>
										<? echo word(' erta borish; - Duoni ko‘p qilish;') ?> </strong></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?elseif($_COOKIE['headerSlider'] == 'on' and date("N") != 5):?>
	<div class="col-lg-6 px-4 pb-4">
		<div class="owl-carousel owl-theme owl-loaded bg-white rounded header-carousel daily">
			<div class="owl-stage-outer">
				<div class="owl-stage">
					<?
						$duoDaily = mysqli_query($db, "SELECT * FROM duolar WHERE type=1");
						while($row = $duoDaily->fetch_assoc()){?>
					<div class="owl-item">
						<div class="card-header p-md-3 p-2 d-flex align-items-center justify-content-between">
							<h6 class="card-title m-0">
								<? echo word($row['title']) ?>
							</h6>
							<div>
								<a href="<? echo shareTg($_SERVER['SERVER_NAME'], word($row['text'])) ?>" target="_blank"><i class="fas fa-share-alt" data-toggle="tooltip" data-placement="top" title="<? echo word('Do‘stlarga ulashish')?>" class="tooltip" role="tooltip"></i></a>
								<i onclick="copytext('#sliderText<?= $row['id'] ?>')" class="fas fa-copy" data-toggle="tooltip" data-placement="top" title="<? echo word('Ko‘chirib olish')?>"></i>
								<p style="display:none" id="sliderText<?= $row['id'] ?>">
									<? echo word($row['title']) . "\n"; ?><i style="text-align:right">
										<? echo $row['textar'] . "</i>\n" . word($row['text']) . "\n" . word('Maʼnosi:') . ' ' . word($row['mano']) ?>
								</p>

							</div>
						</div>
						<div class="card-body p-md-3 p-2">
							<p class="card-text arabicSlider"><?= $row['textar'] ?></p>
							<p class="card-text indent-20">
								<? echo word('Ma’nosi:') ?> <strong>
									<? echo word($row['mano']) ?></strong></p>
						</div>
						<div class="card-footer p-2 d-flex align-items-center">
							<a href="#" class="btn btn-primary d-none d-sm-block">
								<? echo word('To‘liq ko‘rish') ?></a>
						</div>
						<div class="carousel-sm-more d-sm-none"><a href="#"><i class="fas fa-link"></i></a></div>
					</div>
					<?}?>
				</div>
			</div>
		</div>
	</div>
	<?endif?>
</header>