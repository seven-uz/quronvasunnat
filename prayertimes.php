<?
require 'blocks/brain.php';
require 'functions.php';

$page = "prayertimes";
$pageTitle = word('Namoz vaqtlari');
$headerImg = "namaz.webp";
$headerColor = "linear-gradient(to bottom, #732200, #7f2a02, #8c3203, #983a04, #a54204);";

// Shaharlar: aladhan uchun inglizcha qiymat => ko'rinadigan o'zbekcha nom.
$ptCities = [
	'Tashkent'  => 'Toshkent',
	'Samarkand' => 'Samarqand',
	'Bukhara'   => 'Buxoro',
	'Andijan'   => 'Andijon',
	'Namangan'  => 'Namangan',
	'Fergana'   => "Farg'ona",
	'Qarshi'    => 'Qarshi',
	'Navoiy'    => 'Navoiy',
	'Jizzakh'   => 'Jizzax',
	'Gulistan'  => 'Guliston',
	'Termez'    => 'Termiz',
	'Urgench'   => 'Urganch',
	'Nukus'     => 'Nukus',
	'Khiva'     => 'Xiva',
];

// Tanlangan shahar (GET, keyin cookie, default Toshkent) — har qanday chiqishdan oldin.
$ptCity = $_COOKIE['PTcity'] ?? 'Tashkent';
if (isset($_GET['city']) && isset($ptCities[$_GET['city']])) {
	$ptCity = $_GET['city'];
	setcookie('PTcity', $ptCity, time() + 60 * 60 * 24 * 365, '/');
}
if (!isset($ptCities[$ptCity])) $ptCity = 'Tashkent';

require 'blocks/head.php';
require 'blocks/header.php';

// Namoz vaqtlari — kunlik, shahar bo'yicha, keshlangan (jonli curl emas).
$ptDate = date('d-m-Y');
$ptUrl  = 'https://api.aladhan.com/v1/timingsByCity/' . $ptDate
	. '?city=' . urlencode($ptCity) . '&country=Uzbekistan&method=2';
$ptData  = cached_json_get($ptUrl, 21600);
$timings = $ptData['data']['timings'] ?? null;

// aladhan kaliti => [o'zbekcha nom, namozmi?]
$ptRows = [
	'Fajr'    => ['Bomdod', true],
	'Sunrise' => ['Quyosh chiqishi', false],
	'Dhuhr'   => ['Peshin', true],
	'Asr'     => ['Asr', true],
	'Maghrib' => ['Shom', true],
	'Isha'    => ['Xufton', true],
];

// Keyingi namozni aniqlash (shahar vaqt mintaqasi bo'yicha).
$ptNext = null;
if ($timings) {
	$tz = $ptData['data']['meta']['timezone'] ?? 'Asia/Tashkent';
	try {
		$nowHM = (new DateTime('now', new DateTimeZone($tz)))->format('H:i');
		foreach ($ptRows as $key => $info) {
			if (!$info[1] || empty($timings[$key])) continue; // faqat namozlar
			if (substr($timings[$key], 0, 5) >= $nowHM) { $ptNext = $key; break; }
		}
	} catch (Exception $e) { $ptNext = null; }
}
?>
<main id="main-content" tabindex="-1">
	<section class="section" id="prayerTimes">
		<div class="container">
			<div class="d-md-flex justify-content-between align-items-center mb-4">
				<h4 class="h2 m-0"><?php echo word('Namoz vaqtlari') ?></h4>
				<form method="get" action="prayertimes" class="mt-3 mt-md-0 d-flex align-items-center">
					<label class="mr-2 mb-0" for="citySelect"><?php echo word('Shahar') ?>:</label>
					<select name="city" id="citySelect" class="form-control" style="width:auto" onchange="this.form.submit()" aria-label="<?php echo word('Shaharni tanlash') ?>">
						<?php foreach ($ptCities as $val => $label): ?>
							<option value="<?= htmlspecialchars($val, ENT_QUOTES, 'UTF-8') ?>"<?= $val === $ptCity ? ' selected' : '' ?>><?php echo word($label) ?></option>
						<?php endforeach; ?>
					</select>
					<noscript><button type="submit" class="btn btn-primary ml-2"><?php echo word('Ko‘rsatish') ?></button></noscript>
				</form>
			</div>

			<?php if ($timings): ?>
				<p class="text-muted mb-4">
					<strong><?php echo word($ptCities[$ptCity]) ?></strong> —
					<?= htmlspecialchars($ptData['data']['date']['gregorian']['date'] ?? $ptDate, ENT_QUOTES, 'UTF-8') ?>
					<?php if (!empty($ptData['data']['date']['hijri'])): $h = $ptData['data']['date']['hijri']; ?>
						(<?= htmlspecialchars(trim(($h['day'] ?? '') . ' ' . ($h['month']['en'] ?? '') . ' ' . ($h['year'] ?? '')), ENT_QUOTES, 'UTF-8') ?> <?php echo word('hijriy') ?>)
					<?php endif; ?>
				</p>
				<div class="row">
					<?php foreach ($ptRows as $key => $info):
						if (empty($timings[$key])) continue;
						$isNext = ($key === $ptNext); ?>
						<div class="col-6 col-md-4 col-lg-2 mb-3 d-flex align-items-stretch">
							<div class="card w-100 text-center<?= $isNext ? ' border-primary' : '' ?>">
								<div class="card-body p-3">
									<div class="text-muted small mb-1"><?php echo word($info[0]) ?></div>
									<div class="h4 m-0"><?= htmlspecialchars(substr($timings[$key], 0, 5), ENT_QUOTES, 'UTF-8') ?></div>
									<?php if ($isNext): ?><div class="small text-primary mt-1"><?php echo word('keyingi') ?></div><?php endif; ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<p class="text-muted small mt-3"><?php echo word('Vaqtlar aladhan.com xizmatidan olingan (taxminiy). Aniq vaqt uchun mahalliy masjid e’loniga e’tibor bering.') ?></p>
			<?php else: ?>
				<div class="empty-state">
					<i class="fas fa-mosque"></i>
					<h2><?php echo word('Vaqtlarni olishda xatolik') ?></h2>
					<p><?php echo word('Namoz vaqtlarini hozir yuklab bo‘lmadi. Birozdan keyin qayta urinib ko‘ring.') ?></p>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>
<?include 'blocks/footer.php'?>
</body>

</html>
