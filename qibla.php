<?
require 'blocks/brain.php';
require 'functions.php';

$page = "qibla";
$pageTitle = word('Qibla yo‘nalishi');
$pageDescription = "O'zbekiston shaharlari uchun qibla (Ka'ba) yo'nalishi va Makkagacha bo'lgan masofa — sof astronomik (great-circle) hisob. Qibla kompasi.";
$headerImg = "namaz.webp";
$headerColor = "linear-gradient(to bottom, #00332b, #014a3e, #036b59, #048c72, #04a585, #048c72, #036b59, #014a3e, #002b24, #001f1a, #001512, #000a08);";

// Shaharlar — namoz vaqtlari sahifasi bilan umumiy manba
$cities = require 'blocks/cities.php';

// Tanlangan shahar (xavfsiz)
$cityKey = isset($_GET['shahar']) && isset($cities[$_GET['shahar']]) ? $_GET['shahar'] : 'toshkent';
$city = $cities[$cityKey];

// Ka'ba koordinatalari (Makkai mukarrama)
$kaabaLat = 21.4225;
$kaabaLng = 39.8262;

/**
 * Qibla yo'nalishi — boshlang'ich azimut (great-circle initial bearing),
 * haqiqiy shimoldan soat yo'nalishi bo'yicha 0-360 daraja.
 */
function qiblaBearing($lat, $lng, $kLat, $kLng) {
    $phi1 = deg2rad($lat);
    $phi2 = deg2rad($kLat);
    $dLng = deg2rad($kLng - $lng);
    $y = sin($dLng) * cos($phi2);
    $x = cos($phi1) * sin($phi2) - sin($phi1) * cos($phi2) * cos($dLng);
    $brng = rad2deg(atan2($y, $x));
    return fmod($brng + 360.0, 360.0);
}

/**
 * Ikki nuqta orasidagi masofa (km) — haversine formulasi.
 */
function greatCircleKm($lat, $lng, $kLat, $kLng) {
    $R = 6371.0; // Yer radiusi (km)
    $phi1 = deg2rad($lat);
    $phi2 = deg2rad($kLat);
    $dPhi = deg2rad($kLat - $lat);
    $dLng = deg2rad($kLng - $lng);
    $a = sin($dPhi / 2) ** 2 + cos($phi1) * cos($phi2) * sin($dLng / 2) ** 2;
    return $R * 2 * atan2(sqrt($a), sqrt(1 - $a));
}

/**
 * Azimutni 16 rumbli kompas nomiga aylantirish (o'zbekcha).
 */
function compassName($brng) {
    // 8 rumb: Shimol, Shimoli-sharq, Sharq, ...
    $eight = array('Shimol', 'Shimoli-sharq', 'Sharq', 'Janubi-sharq',
                   'Janub', 'Janubi-g‘arb', 'G‘arb', 'Shimoli-g‘arb');
    $idx = (int) round($brng / 45.0) % 8;
    return $eight[$idx];
}

$bearing  = qiblaBearing($city['lat'], $city['lng'], $kaabaLat, $kaabaLng);
$distance = greatCircleKm($city['lat'], $city['lng'], $kaabaLat, $kaabaLng);
$bearingR = round($bearing, 1);
$distanceR = number_format($distance, 0, '.', ' ');
$dirName  = compassName($bearing);

require 'blocks/head.php';
require 'blocks/header.php';
?>
<main>
	<section class="section" id="qiblaPage">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-8">

					<div class="text-center mb-4">
						<h3 class="h2"><?php echo word('Qibla yo‘nalishi') ?></h3>
						<p class="text-muted">
							<?php echo word('Tanlangan shahardan Ka‘ba (Makkai mukarrama) tomon yo‘nalish'); ?>
						</p>
					</div>

					<form method="get" action="qibla" class="form-inline justify-content-center mb-4">
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
							<h5 class="m-0"><i class="fas fa-kaaba mr-2"></i><?php echo word($city['name']); ?></h5>
							<span class="badge badge-success p-2" style="font-size:1rem;"><?php echo h($bearingR); ?>&deg;</span>
						</div>
						<div class="card-body text-center">

							<!-- Qibla kompasi (sof SVG, JS device-orientation bilan jonlanadi) -->
							<div style="position:relative;width:240px;height:240px;margin:0 auto;">
								<svg viewBox="0 0 200 200" width="240" height="240" id="compassDial" style="transition:transform .2s ease-out;">
									<circle cx="100" cy="100" r="96" fill="#f8f9fa" stroke="#dee2e6" stroke-width="2"/>
									<circle cx="100" cy="100" r="78" fill="none" stroke="#e9ecef" stroke-width="1"/>
									<!-- Rumb harflari -->
									<text x="100" y="22" text-anchor="middle" font-size="14" font-weight="bold" fill="#dc3545">N</text>
									<text x="184" y="105" text-anchor="middle" font-size="13" fill="#6c757d">E</text>
									<text x="100" y="190" text-anchor="middle" font-size="13" fill="#6c757d">S</text>
									<text x="16" y="105" text-anchor="middle" font-size="13" fill="#6c757d">W</text>
									<!-- Qibla strelkasi (azimut bo'yicha aylantiriladi) -->
									<g id="qiblaNeedle" transform="rotate(<?php echo h($bearingR); ?> 100 100)">
										<line x1="100" y1="100" x2="100" y2="24" stroke="#198754" stroke-width="4" stroke-linecap="round"/>
										<polygon points="100,14 92,34 108,34" fill="#198754"/>
										<circle cx="100" cy="100" r="6" fill="#198754"/>
									</g>
								</svg>
							</div>

							<p class="mt-3 mb-1">
								<span class="h4 text-success"><?php echo h($bearingR); ?>&deg;</span>
								<span class="text-muted">(<?php echo word($dirName); ?>)</span>
							</p>
							<p class="text-muted mb-0">
								<i class="fas fa-route mr-1"></i>
								<?php echo word('Makkagacha masofa'); ?>:
								<strong>~<?php echo h($distanceR); ?> <?php echo word('km'); ?></strong>
							</p>

							<div id="orientHint" class="alert alert-info mt-3 mb-0" style="display:none;font-size:.85rem;">
								<i class="fas fa-mobile-alt mr-1"></i>
								<span id="orientText"><?php echo word('Telefonni tekis ushlab, kompasni faollashtiring.'); ?></span>
							</div>
							<button id="orientBtn" class="btn btn-outline-success btn-sm mt-3" style="display:none;">
								<i class="fas fa-compass mr-1"></i><?php echo word('Kompasni yoqish'); ?>
							</button>
						</div>
					</div>

					<p class="text-muted mt-3" style="font-size:.8rem;">
						<i class="fas fa-info-circle mr-1"></i>
						<?php echo word('Yo‘nalish haqiqiy (geografik) shimoldan soat yo‘nalishi bo‘yicha hisoblangan. Telefon kompasi magnit shimolni ko‘rsatishi sababli bir necha daraja farq qilishi mumkin. Aniqlik uchun ochiq joyda, metall buyumlardan uzoqda foydalaning.'); ?>
					</p>

				</div>
			</div>
		</div>
	</section>
</main>

<script>
(function () {
	var qiblaBearing = <?php echo json_encode((float) $bearingR); ?>;
	var dial = document.getElementById('compassDial');
	var needle = document.getElementById('qiblaNeedle');
	var hint = document.getElementById('orientHint');
	var text = document.getElementById('orientText');
	var btn = document.getElementById('orientBtn');

	function supportsOrientation() {
		return (typeof window.DeviceOrientationEvent !== 'undefined');
	}

	// Qurilma yo'nalishini olib, butun kompas siferblatini aylantiramiz,
	// shunda yashil strelka doim haqiqiy qibla tomonni ko'rsatadi.
	function handleOrientation(e) {
		var heading = null;
		if (typeof e.webkitCompassHeading === 'number') {
			heading = e.webkitCompassHeading; // iOS: magnit shimolga nisbatan
		} else if (e.alpha !== null && typeof e.alpha === 'number') {
			heading = 360 - e.alpha; // Android (taxminiy)
		}
		if (heading === null) return;
		if (dial) dial.style.transform = 'rotate(' + (-heading) + 'deg)';
		if (text) text.textContent = '<?php echo word('Kompas faol. Yashil strelka — qibla tomon.'); ?>';
	}

	function startOrientation() {
		if (window.DeviceOrientationEvent && typeof DeviceOrientationEvent.requestPermission === 'function') {
			// iOS 13+ ruxsat so'raydi
			DeviceOrientationEvent.requestPermission().then(function (state) {
				if (state === 'granted') {
					window.addEventListener('deviceorientation', handleOrientation, true);
					if (btn) btn.style.display = 'none';
				}
			}).catch(function () {});
		} else {
			window.addEventListener('deviceorientationabsolute', handleOrientation, true);
			window.addEventListener('deviceorientation', handleOrientation, true);
			if (btn) btn.style.display = 'none';
		}
	}

	if (supportsOrientation() && ('ontouchstart' in window || navigator.maxTouchPoints > 0)) {
		if (hint) hint.style.display = 'block';
		if (btn) btn.style.display = 'inline-block';
		if (btn) btn.addEventListener('click', startOrientation);
	}
})();
</script>

<?include 'blocks/footer.php'?>
</body>

</html>
