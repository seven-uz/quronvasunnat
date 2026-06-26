<?php
// Dinamik robots.txt — .htaccess /robots.txt ni shu faylga yo'naltiradi.
// Domen avtomatik aniqlanadi (sitemap havolasi har doim to'g'ri bo'ladi).
header('Content-Type: text/plain; charset=utf-8');

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? '';

echo "User-agent: *\n";
echo "Allow: /\n";
echo "Disallow: /adm/\n";
echo "Disallow: /actions/\n";
echo "Disallow: /blocks/\n";
echo "Disallow: /cache/\n";
echo "Disallow: /error/\n";
if ($host !== '') {
	echo "Sitemap: {$scheme}://{$host}/sitemap.xml\n";
}
