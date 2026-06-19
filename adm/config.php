<?php

// Qur'on va Sunnat — admin panel konfiguratsiyasi.
// Maxfiy qiymatlar (admin login va paroli) config.local.php da (gitignore) saqlanadi.
// Namuna uchun: config.local.example.php ni "config.local.php" deb nusxalang.

if (is_file(__DIR__ . '/config.local.php')) {
	require __DIR__ . '/config.local.php';
}

// Default'lar (config.local.php ulardan ustun turadi).
// DIQQAT: ishlab chiqarishda (production) albatta config.local.php da o'zingiznikini bering.
if (!defined('ADMIN_USER'))      define('ADMIN_USER', 'admin');
// Default parol: "admin" — faqat dastlabki sozlash uchun. Production'da ALBATTA o'zgartiring.
if (!defined('ADMIN_PASS_HASH')) define('ADMIN_PASS_HASH', '$2y$10$0j.f5RoUwuoxRTn0N1VqJuTkGiLwGtHn0uSqrxVU4PgcL.fFvSF4O');

// Boshqariladigan jadvallar OQ RO'YXATI (allow-list). SQL'ga faqat shu kalitlar tushadi.
// Har biri: label (ko'rinadigan nom), list (ro'yxatda ko'rsatiladigan ustunlar).
$ADMIN_TABLES = [
	'duolar' => [
		'label' => 'Duolar',
		'list'  => ['id', 'title', 'titlear'],
	],
	'hadislar' => [
		'label' => 'Hadislar',
		'list'  => ['id', 'title'],
	],
	'sunnatlar' => [
		'label' => 'Sunnatlar',
		'list'  => ['id', 'title'],
	],
	'asmaulhusna' => [
		'label' => 'Asma ul-Husna',
		'list'  => ['id', 'title', 'titlear'],
	],
	'tags' => [
		'label' => 'Teglar',
		'list'  => ['id', 'title'],
	],
];
