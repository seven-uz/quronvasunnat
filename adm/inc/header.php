<?php
// Admin sahifa sarlavhasi (header). Faqat tizimga kirgan foydalanuvchi uchun.
// Chaqirishdan oldin bootstrap.php yuklangan va require_login() chaqirilgan bo'lsin.
$pageTitle = $pageTitle ?? 'Boshqaruv';
?><!DOCTYPE html>
<html lang="uz">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex, nofollow">
	<title><?= e($pageTitle) ?> — Qur'on va Sunnat admin</title>
	<link rel="stylesheet" href="assets/admin.css">
</head>
<body>
<header class="adm-top">
	<div class="adm-top-inner">
		<a class="adm-brand" href="index.php">Qur'on va Sunnat <span>admin</span></a>
		<nav class="adm-nav">
			<a href="index.php">Bosh sahifa</a>
			<a href="/" target="_blank" rel="noopener">Saytni ko'rish</a>
			<a class="adm-logout" href="logout.php">Chiqish</a>
		</nav>
	</div>
</header>
<main class="adm-main">
	<div class="adm-container">
