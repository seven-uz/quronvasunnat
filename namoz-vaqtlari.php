<?
require 'blocks/brain.php';
require 'functions.php';
require 'blocks/PrayerTimes.php';

$page = "namoz-vaqtlari";
$pageTitle = word('Namoz vaqtlari');
$pageDescription = "O'zbekiston shaharlari uchun bugungi namoz vaqtlari: Bomdod, Quyosh, Peshin, Asr, Shom va Xufton. Hanafiy mazhabi bo'yicha aniq hisoblangan.";
$headerImg = "namaz.webp";
$headerColor = "linear-gradient(to bottom, #732200, #7f2a02, #8c3203, #983a04, #a54204, #a14006, #9e3e07, #9a3c09, #85300b, #70250a, #5c1b07, #481100);";

// O'zbekiston shaharlari: kenglik, uzunlik. Vaqt mintaqasi UTC+5.
$cities = array(
    'toshkent'  => array('name' => 'Toshkent',   'lat' => 41.2995, 'lng' => 69.2401),
    'samarqand' => array('name' => 'Samarqand',  'lat' => 39.6270, 'lng' => 66.9750),
    'buxoro'    => array('name' => 'Buxoro',     'lat' => 39.7680, 'lng' => 64.4210),
    'andijon'   => array('name' => 'Andijon',    'lat' => 40.7830, 'lng' => 72.3500),
    'namangan'  => array('name' => 'Namangan',   'lat' => 40.9983, 'lng' => 71.6726),
    'fargona'   => array('name' => 'Farg‘ona',   'lat' => 40.3864, 'lng' => 71.7864),
    'qarshi'    => array('name' => 'Qarshi',     'lat' => 38.8606, 'lng' => 65.7891),
    'nukus'     => array('name' => 'Nukus',      'lat' => 42.4600, 'lng' => 59.6100),
    'urganch'   => array('name' => 'Urganch',    'lat' => 41.5500, 'lng' => 60.6310),
    'termiz'    => array('name' => 'Termiz',     'lat' => 37.2242, 'lng' => 67.2783),
    'jizzax'    => array('name' => 'Jizzax',     'lat' => 40.1158, 'lng' => 67.8422),
    'navoiy'    => array('name' => 'Navoiy',     'lat' => 40.0844, 'lng' => 65.3792),
    'guliston'  => array('name' => 'Guliston',   'lat' => 40.4897, 'lng' => 68.7842),
    'nurafshon' => array('name' => 'Nurafshon',  'lat' => 41.0167, 'lng' => 69.3500),
);

// Tanlangan shahar (xavfsiz)
$cityKey = isset($_GET['shahar']) && isset($cities[$_GET['shahar']]) ? $_GET['shahar'] : 'toshkent';
$city = $cities[$cityKey];

$tz = 5.0; // O'zbekiston
$now = time() + ($tz * 3600) - (date('Z')); // serverdan qat'i nazar UTC+5 mahalliy vaqt
$y = (int) gmdate('Y', $now);
$m = (int) gmdate('n', $now);
$d = (int) gmdate('j', $now);

$pt = new PrayerTimes(18.0, 18.0, 2); // Hanafiy
$times = $pt->getTimes($y, $m, $d, $city['lat'], $city['lng'], $tz);

// Ko'rsatiladigan vaqtlar ro'yxati (Quyosh chiqishi alohida, namoz emas)
$rows = array(
    array('key' => 'fajr',    'label' => 'Bomdod', 'icon' => 'fa-cloud-moon',   'prayer' => true),
    array('key' => 'sunrise', 'label' => 'Quyosh', 'icon' => 'fa-sun',          'prayer' => false),
    array('key' => 'dhuhr',   'label' => 'Peshin', 'icon' => 'fa-sun',          'prayer' => true),
    array('key' => 'asr',     'label' => 'Asr',    'icon' => 'fa-cloud-sun',    'prayer' => true),
    array('key' => 'maghrib', 'label' => 'Shom',   'icon' => 'fa-cloud-moon',   'prayer' => true),
    array('key' => 'isha',    'label' => 'Xufton', 'icon' => 'fa-moon',         'prayer' => true),
);

// Keyingi namozni aniqlash
$nowMinutes = (int) gmdate('G', $now) * 60 + (int) gmdate('i', $now);
$nextKey = null;
foreach ($rows as $r) {
    if (!$r['prayer']) continue;
    list($hh, $mm) = array_map('intval', explode(':', $times[$r['key']]));
    if ($hh * 60 + $mm > $nowMinutes) { $nextKey = $r['key']; break; }
}
if ($nextKey === null) $nextKey = 'fajr'; // bugungi namozlar tugagan -> ertalabki Bomdod

$uzMonths = array(1=>'yanvar',2=>'fevral',3=>'mart',4=>'aprel',5=>'may',6=>'iyun',7=>'iyul',8=>'avgust',9=>'sentyabr',10=>'oktyabr',11=>'noyabr',12=>'dekabr');

require 'blocks/head.php';
require 'blocks/header.php';
?>
<main>
	<section class="section" id="prayerTimes">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-8">

					<div class="text-center mb-4">
						<h3 class="h2"><?php echo word('Namoz vaqtlari') ?></h3>
						<p class="text-muted mb-1">
							<?php echo word('Sana'); ?>:
							<strong><?php echo word($d.'-'.$uzMonths[$m].', '.$y.'-yil'); ?></strong>
						</p>
						<p class="text-muted">
							<?php echo word('Hanafiy mazhabi bo‘yicha hisoblangan'); ?>
						</p>
					</div>

					<form method="get" action="namoz-vaqtlari" class="form-inline justify-content-center mb-4">
						<label class="mr-2 mb-2" for="shahar"><i class="fas fa-map-marker-alt mr-1"></i><?php echo word('Shahar'); ?>:</label>
						<select name="shahar" id="shahar" class="form-control mr-2 mb-2" onchange="this.form.submit()">
							<?php foreach ($cities as $key => $c): ?>
								<option value="<?php echo h($key); ?>" <?php echo $key == $cityKey ? 'selected' : ''; ?>>
									<?php echo word($c['name']); ?>
								</option>
							<?php endforeach; ?>
						</select>
						<noscript><button type="submit" class="btn btn-primary mb-2"><?php echo word('Ko‘rsatish'); ?></button></noscript>
					</form>

					<div class="card shadow-sm">
						<div class="card-header d-flex justify-content-between align-items-center">
							<h5 class="m-0"><i class="fas fa-mosque mr-2"></i><?php echo word($city['name']); ?></h5>
							<span id="ptClock" class="badge badge-primary p-2" style="font-size:1rem;">--:--:--</span>
						</div>
						<ul class="list-group list-group-flush">
							<?php foreach ($rows as $r):
								$isNext = ($r['key'] == $nextKey && $r['prayer']);
							?>
								<li class="list-group-item d-flex justify-content-between align-items-center <?php echo $isNext ? 'bg-primary text-white' : ''; ?>"
									data-time="<?php echo h($times[$r['key']]); ?>">
									<span>
										<i class="fas <?php echo h($r['icon']); ?> mr-2"></i>
										<?php echo word($r['label']); ?>
										<?php if ($isNext): ?>
											<span class="badge badge-light ml-2"><?php echo word('Keyingi'); ?></span>
										<?php endif; ?>
									</span>
									<span style="font-size:1.25rem;font-weight:600;letter-spacing:1px;">
										<?php echo h($times[$r['key']]); ?>
									</span>
								</li>
							<?php endforeach; ?>
						</ul>
						<div class="card-footer text-center text-muted" style="font-size:.85rem;">
							<?php echo word('Keyingi namozgacha'); ?>:
							<strong id="ptCountdown">--:--:--</strong>
						</div>
					</div>

					<p class="text-muted mt-3" style="font-size:.8rem;">
						<i class="fas fa-info-circle mr-1"></i>
						<?php echo word('Vaqtlar astronomik hisob-kitob asosida (Fajr/Isha 18°). Mahalliy masjid taqvimi bilan bir necha daqiqa farq qilishi mumkin. Ehtiyot uchun Bomdodda ozgina erta, Shomda ozgina kech amal qiling.'); ?>
					</p>

				</div>
			</div>
		</div>
	</section>
</main>

<script>
(function () {
	// Keyingi namoz vaqti (UTC+5 mahalliy)
	var nextTime = "<?php echo $times[$nextKey]; ?>";
	function uzNow() {
		// Brauzer mintaqasidan qat'i nazar UTC+5 vaqtini olamiz
		var d = new Date();
		var utc = d.getTime() + d.getTimezoneOffset() * 60000;
		return new Date(utc + 5 * 3600000);
	}
	function pad(n) { return (n < 10 ? '0' : '') + n; }
	function tick() {
		var n = uzNow();
		var clock = document.getElementById('ptClock');
		if (clock) clock.textContent = pad(n.getHours()) + ':' + pad(n.getMinutes()) + ':' + pad(n.getSeconds());

		var parts = nextTime.split(':');
		var target = new Date(n.getTime());
		target.setHours(parseInt(parts[0], 10), parseInt(parts[1], 10), 0, 0);
		var diff = target - n;
		if (diff < 0) diff += 24 * 3600000; // ertangi kunga
		var hh = Math.floor(diff / 3600000);
		var mm = Math.floor((diff % 3600000) / 60000);
		var ss = Math.floor((diff % 60000) / 1000);
		var cd = document.getElementById('ptCountdown');
		if (cd) cd.textContent = pad(hh) + ':' + pad(mm) + ':' + pad(ss);
	}
	tick();
	setInterval(tick, 1000);
})();
</script>

<?include 'blocks/footer.php'?>
</body>

</html>
