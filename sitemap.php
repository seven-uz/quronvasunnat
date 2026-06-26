<?php
// Dinamik sitemap.xml — .htaccess /sitemap.xml ni shu faylga yo'naltiradi.
// Statik sahifalar + DB'dagi kontent (suralar, duolar, hadislar, sunnatlar).

require __DIR__ . '/blocks/db.php'; // $db

header('Content-Type: application/xml; charset=utf-8');

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? '';
$base = $scheme . '://' . $host;

$urls = [];
$urls[] = ['/', '1.0', 'daily'];
foreach (['quron', 'duo', 'hadis', 'sunnat', 'asmaulhusna', 'search', 'namoz', 'roza', 'zakot', 'haj', 'kalendar', 'prayertimes'] as $p) {
	$urls[] = ['/' . $p, '0.8', 'weekly'];
}

// DB'dan id'larni xavfsiz yig'ish (jadval yo'q bo'lsa ham yiqilmaydi).
function sitemap_ids($db, $table) {
	$ids = [];
	$res = @mysqli_query($db, 'SELECT `id` FROM `' . $table . '` ORDER BY `id`');
	if ($res) {
		while ($row = mysqli_fetch_assoc($res)) {
			$ids[] = (int)$row['id'];
		}
	}
	return $ids;
}

foreach (sitemap_ids($db, 'suranames') as $id) { $urls[] = ['/quron?sura=' . $id, '0.7', 'monthly']; }
foreach (sitemap_ids($db, 'duolar')    as $id) { $urls[] = ['/duo?id=' . $id,    '0.6', 'monthly']; }
foreach (sitemap_ids($db, 'hadislar')  as $id) { $urls[] = ['/hadis?id=' . $id,  '0.6', 'monthly']; }
foreach (sitemap_ids($db, 'sunnatlar') as $id) { $urls[] = ['/sunnat?id=' . $id, '0.6', 'monthly']; }

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($urls as $u) {
	$loc = htmlspecialchars($base . $u[0], ENT_QUOTES, 'UTF-8');
	echo "\t<url><loc>{$loc}</loc><changefreq>{$u[2]}</changefreq><priority>{$u[1]}</priority></url>\n";
}
echo '</urlset>' . "\n";
