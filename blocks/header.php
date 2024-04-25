<?php

// $arabic_date = getArabicDate(date("d-m-Y"));

$hijriMonthName = $arabic_date['hijri']['month']['en'];
$hijriMonthNameAr = $arabic_date['hijri']['month']['ar'];
$hijriDay = $arabic_date['hijri']['day'];
$hijriMonth = $arabic_date['hijri']['month']['number'];
$hijriYear = $arabic_date['hijri']['year'];
$hijriDayName = $arabic_date['hijri']['weekday']['en'];
$hijriDayNameAr = $arabic_date['hijri']['weekday']['ar'];

if($hijriMonthName == "Shawwāl") $hijriMonthName = "Shavvol";else
if($hijriMonthName == "Ṣafar") $hijriMonthName = "Safar";

if($hijriDayName == "Al Athnayn") $hijriDayName = "Al Isnayn";
elseif($hijriDayName == "Al Thalaata") $hijriDayName = "Al Salasa";
elseif($hijriDayName == "Al Arba'a") $hijriDayName = "Al Arba'a";
elseif($hijriDayName == "Al Juma'a") $hijriDayName = "Al Jum'a";
elseif($hijriDayName == "Al Ahad") $hijriDayName = "Al Ahad";
elseif($hijriDayName == "Al Khamees") $hijriDayName = "Al Homsata";
elseif($hijriDayName == "Al Sabt") $hijriDayName = "Al Sabt";

require 'nav.php';
?>
<header id="header" <?if($_COOKIE['headerImg']=='on' ):?>style="min-height:30vh;background-image: url(assets/images/<?= $headerImg ?>);"
	<?php else :?>style="min-height:30vh;background-image: <?= $headerColor ?>"
	<?php endif ?>>
	<div class="dayLine">
		<div class="col-fluid">
			<div class="bg-white p-3 rounded d-md-flex justify-content-between">
				<h6 class="m-0 pb-md-0 pb-2">
					<?php echo word('Milodiy sana: '.date("d").'-'.cmn(date("m")).', '.date("Y").'-yil. ').wn() ?>
				</h6>
				<h6 class="m-0 d-md-block d-none"><?= $words['bugun'] ?></h6>
				<h6 class="m-0">
					<?php echo word('Xijriy sana: '.$hijriDay.'-'.$hijriMonthName.', '.$hijriYear.'-yil. '.$hijriDayName) ?>
				</h6>
			</div>
		</div>
	</div>
	<?php if(date("N") == 5) :?>
	<div class="col-12 text-center">
		<div class="congr-juma">
			<?php echo word('Juma ayyomingiz muborak bo‘lsin')?>
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
									<?php echo word("Juma sunnatlari") ?>
								</h6>
							</div>
							<div class="card-body">
								<p class="card-text indent-20">
									<strong>
										<?php echo word('- G‘usl qilish; - Toza libos kiyish; - Tirnoq olish; - Misvok ishlatish; - Xo‘shbo‘ylanish; Payg‘ambarimiz Muhammad (S.A.V) ga salovot aytish; - Qur’on tilovati; Masjidga erta borish; - Duoni ko‘p qilish;') ?> </strong></p>
							</div>
						</div>
						<div class="owl-item">
							<div class="card-header p-3 d-flex align-items-center justify-content-between">
								<h6 class="card-title m-0">
									<?php echo word("Juma sunnatlari") ?>
								</h6>
							</div>
							<div class="card-body">
								<p class="card-text indent-20">
									<strong>
										<?php echo word(' erta borish; - Duoni ko‘p qilish;') ?> </strong></p>
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
									<?php echo word("Juma haqida hadislar") ?>
								</h6>
							</div>
							<div class="card-body">
								<p class="card-text indent-20">
									<strong>
										<?php echo word('- G‘usl qi olish; - Misvok ishlatish; - Xo‘shbo‘ylanish; Payg‘ambarimiz Muhammad (S.A.V) ga salovot aytish; - Qur’on tilovati; Masjidga erta borish; - Duoni ko‘p qilish;') ?> </strong></p>
							</div>
						</div>
						<div class="owl-item">
							<div class="card-header p-3 d-flex align-items-center justify-content-between">
								<h6 class="card-title m-0">
									<?php echo word("Juma sunnatlari") ?>
								</h6>
							</div>
							<div class="card-body">
								<p class="card-text indent-20">
									<strong>
										<?php echo word(' erta borish; - Duoni ko‘p qilish;') ?> </strong></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php elseif($_COOKIE['headerSlider'] == 'on' and date("N") != 5):?>
	<!-- <div class="col-lg-6 px-4 pb-4">
		<div class="owl-carousel owl-theme owl-loaded bg-white rounded header-carousel daily">
			<div class="owl-stage-outer">
				<div class="owl-stage">
					<?php
						//$duoDaily = mysqli_query($db, "SELECT * FROM duolar WHERE type=1");
						//while($row = $duoDaily->fetch_assoc()){?>
					<div class="owl-item">
						<div class="card-header p-md-3 p-2 d-flex align-items-center justify-content-between">
							<h6 class="card-title m-0">
								<?php //echo word($row['title']) ?>
							</h6>
							<div>
								<a href="<?php //echo shareTg($_SERVER['SERVER_NAME'], word($row['text'])) ?>" target="_blank"><i class="fas fa-share-alt" data-toggle="tooltip" data-placement="top" title="<?php //echo word('Do‘stlarga ulashish')?>" class="tooltip" role="tooltip"></i></a>
								<i onclick="copytext('#sliderText<?// $row['id'] ?>')" class="fas fa-copy" data-toggle="tooltip" data-placement="top" title="<?php //echo word('Ko‘chirib olish')?>"></i>
								<p style="display:none" id="sliderText<?// $row['id'] ?>">
									<?php //echo word($row['title']) . "\n"; ?><i style="text-align:right">
										<?php //echo $row['textar'] . "</i>\n" . word($row['text']) . "\n" . word('Maʼnosi:') . ' ' . word($row['mano']) ?>
								</p>

							</div>
						</div>
						<div class="card-body p-md-3 p-2">
							<p class="card-text arabicSlider"><?// $row['textar'] ?></p>
							<p class="card-text indent-20">
								<?php //echo word('Ma’nosi:') ?> <strong>
									<?php //echo word($row['mano']) ?></strong></p>
						</div>
						<div class="card-footer p-2 d-flex align-items-center">
							<a href="#" class="btn btn-primary d-none d-sm-block">
								<?php //echo word('To‘liq ko‘rish') ?></a>
						</div>
						<div class="carousel-sm-more d-sm-none"><a href="#"><i class="fas fa-link"></i></a></div>
					</div>
					<?//}?>
				</div>
			</div>
		</div>
	</div> -->
	<?php endif; ?>
</header>