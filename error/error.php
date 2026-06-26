<?php
// Umumiy xato sahifasi (DB/curl'siz, mustaqil — 500'da ham ishonchli ishlaydi).
// .htaccess: ErrorDocument 401/403/404/500 -> /error/error.php
$code = (int)($_SERVER['REDIRECT_STATUS'] ?? 0);
$map = [
	400 => "So'rov noto'g'ri",
	401 => "Avtorizatsiya kerak",
	403 => "Ruxsat yo'q",
	404 => "Sahifa topilmadi",
	500 => "Server xatosi",
];
if (!isset($map[$code])) {
	$code = 404;
}
$title = $map[$code];
http_response_code($code);
?><!DOCTYPE html>
<html lang="uz">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $code . ' — ' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>
	<style>
		:root{--green:#1B7F5E;--ink:#1A1A1A;--muted:#6B6F69;--bg:#FAFAF7;}
		*{box-sizing:border-box}
		body{margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;
			font-family:system-ui,-apple-system,'Segoe UI',Roboto,Arial,sans-serif;
			background:var(--bg);color:var(--ink);padding:24px}
		.box{text-align:center;max-width:440px}
		.code{font-size:84px;font-weight:800;color:var(--green);line-height:1;margin:0}
		h1{font-size:22px;margin:12px 0 6px}
		p{color:var(--muted);margin:0 0 22px}
		a{display:inline-block;background:var(--green);color:#fff;text-decoration:none;
			padding:11px 22px;border-radius:999px;font-weight:600}
	</style>
</head>
<body>
	<div class="box">
		<p class="code"><?php echo $code; ?></p>
		<h1><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></h1>
		<p>Kechirasiz, kutilmagan holat yuz berdi.</p>
		<a href="/">Bosh sahifaga qaytish</a>
	</div>
</body>
</html>
