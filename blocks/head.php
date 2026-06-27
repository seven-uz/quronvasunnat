<!DOCTYPE html>
<html lang="uz">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
	<!-- Favicon -->
	<link rel="apple-touch-icon" sizes="57x57" href="assets/images/favicon/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="assets/images/favicon/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="assets/images/favicon/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="assets/images/favicon/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="assets/images/favicon/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="assets/images/favicon/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="assets/images/favicon/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="assets/images/favicon/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192" href="assets/images/favicon/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="assets/images/favicon/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">
	<link rel="manifest" href="/manifest.webmanifest">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="assets/images/favicon/ms-icon-144x144.png">
	<meta name="theme-color" content="<?= ($_COOKIE['darkMode'] ?? 'off') === 'on' ? '#0F1512' : '#1B7F5E' ?>">

	<?php
		// ---- SEO / ulashish meta (Open Graph, Twitter, canonical, JSON-LD) ----
		$__scheme = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
			|| (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')) ? 'https' : 'http';
		$__host = $_SERVER['HTTP_HOST'] ?? '';
		$__base = $__scheme . '://' . $__host;
		$__canonical = $__base . ($_SERVER['REQUEST_URI'] ?? '/');
		$__ogImg = $__base . '/assets/images/' . (!empty($headerImg) ? $headerImg : 'favicon/android-icon-192x192.png');
		$__title = trim(($pageTitle ?? '') . ($siteTitle ?? ''));
		$__desc = $pageDescription ?? "Qur'on va Sunnat — o'zbek tilida Qur'on (tarjima va tilovat), duolar, hadislar, sunnatlar va Asma ul-Husna.";
		$__ld = [
			'@context' => 'https://schema.org',
			'@type'    => 'WebSite',
			'name'     => "Qur'on va Sunnat",
			'url'      => $__base . '/',
			'inLanguage' => 'uz',
			'potentialAction' => [
				'@type'  => 'SearchAction',
				'target' => $__base . '/search?q={search_term_string}',
				'query-input' => 'required name=search_term_string',
			],
		];
	?>
	<meta name="description" content="<?= htmlspecialchars($__desc, ENT_QUOTES, 'UTF-8') ?>">
	<link rel="canonical" href="<?= htmlspecialchars($__canonical, ENT_QUOTES, 'UTF-8') ?>">
	<meta property="og:type" content="website">
	<meta property="og:site_name" content="Qur'on va Sunnat">
	<meta property="og:locale" content="uz_UZ">
	<meta property="og:title" content="<?= htmlspecialchars($__title, ENT_QUOTES, 'UTF-8') ?>">
	<meta property="og:description" content="<?= htmlspecialchars($__desc, ENT_QUOTES, 'UTF-8') ?>">
	<meta property="og:url" content="<?= htmlspecialchars($__canonical, ENT_QUOTES, 'UTF-8') ?>">
	<meta property="og:image" content="<?= htmlspecialchars($__ogImg, ENT_QUOTES, 'UTF-8') ?>">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="<?= htmlspecialchars($__title, ENT_QUOTES, 'UTF-8') ?>">
	<meta name="twitter:description" content="<?= htmlspecialchars($__desc, ENT_QUOTES, 'UTF-8') ?>">
	<meta name="twitter:image" content="<?= htmlspecialchars($__ogImg, ENT_QUOTES, 'UTF-8') ?>">
	<script type="application/ld+json"><?= json_encode($__ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>

	<!-- Fonts: serif sarlavhalar (Lora) + sans matn (Inter), kirill+lotin qo'llab-quvvatlaydi -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lora:wght@500;600;700&display=swap&subset=cyrillic,cyrillic-ext,latin,latin-ext">

	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" integrity="sha256-h20CPZ0QyXlBuAw7A+KluUYx/3pK+c7lYEpqLTlxjYQ=" crossorigin="anonymous" />

	<!-- Owl Carousel 2 -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha256-UhQQ4fxEeABh4JrcmAJ1+16id/1dnlOEVCFOxDef9Lw=" crossorigin="anonymous" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" integrity="sha256-kksNxjDRxd/5+jGurZUJd1sdR2v+ClrCl3svESBaJqw=" crossorigin="anonymous" />

	<!-- Bootstrap -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

	<!-- Animate Css -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.0/animate.min.css" integrity="sha256-6hqHMqXTVEds1R8HgKisLm3l/doneQs+rS1a5NLmwwo=" crossorigin="anonymous" />

	<!-- Custom Styles -->
	<!-- <link rel="stylesheet" href="assets/css/element.css"> -->
	<link rel="stylesheet" href="assets/css/style.css?v=2.1.0">
	<?php if (function_exists('accent_css') && !empty($_COOKIE['globalColor'])): $accentCss = accent_css($_COOKIE['globalColor']); if ($accentCss !== ''): ?>
	<style id="accentOverride"><?= $accentCss ?></style>
	<?php endif; endif; ?>
	<!-- <link rel="stylesheet" href="assets/css/media.css"> -->
	<title><?= $pageTitle . $siteTitle ?></title>
	<!-- Yandex.Metrika counter -->
	<script type="text/javascript" >
		(function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
		m[i].l=1*new Date();
		for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
		k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
		(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

		ym(97104503, "init", {
					clickmap:true,
					trackLinks:true,
					accurateTrackBounce:true,
					webvisor:true
		});
	</script>
	<noscript><div><img src="https://mc.yandex.ru/watch/97104503" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
	<!-- /Yandex.Metrika counter -->
	<!-- PWA: Service Worker ro'yxatga olish -->
	<script>
		if ('serviceWorker' in navigator) {
			window.addEventListener('load', function() {
				navigator.serviceWorker.register('/sw.js').catch(function(err) {
					console.warn('SW ro\'yxatdan o\'tmadi:', err);
				});
			});
		}
	</script>
</head>

<body class="<?= ($_COOKIE['darkMode'] ?? 'off') === 'on' ? 'darkMode' : '' ?>">
<a class="skip-link" href="#main-content"><?php echo word('Asosiy mazmunga o‘tish') ?></a>