<?php

// Admin panel — umumiy yuklovchi (bootstrap).
// Sessiya, konfiguratsiya, DB ulanishi va yordamchi funksiyalar shu yerda.

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

// Muhitga qarab xatolar (brain.php bilan bir xil mantiq).
$host = $_SERVER['SERVER_NAME'] ?? ($_SERVER['HTTP_HOST'] ?? '');
$host = preg_replace('/:\d+$/', '', $host);
$isDev = in_array($host, ['localhost', '127.0.0.1', '::1'], true)
	|| strpos($host, '.') === false
	|| preg_match('/\.(local|test|localhost|dev)$/i', $host);

ini_set('log_errors', '1');
if ($isDev) {
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
} else {
	error_reporting(E_ALL & ~E_DEPRECATED);
	ini_set('display_errors', '0');
}

require __DIR__ . '/../config.php';
require __DIR__ . '/../../blocks/db.php';

// ---- Yordamchi funksiyalar ----

// HTML'ga xavfsiz chiqarish.
function e($s) {
	return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

// CSRF token (sessiyada saqlanadi).
function csrf_token() {
	if (empty($_SESSION['csrf'])) {
		$_SESSION['csrf'] = bin2hex(random_bytes(32));
	}
	return $_SESSION['csrf'];
}

// Formaga qo'yiladigan yashirin CSRF maydoni.
function csrf_field() {
	return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

// POST so'rovda CSRF tokenni tekshirish — noto'g'ri bo'lsa to'xtatadi.
function verify_csrf() {
	$sent = $_POST['csrf'] ?? '';
	if (!is_string($sent) || !hash_equals(csrf_token(), $sent)) {
		http_response_code(400);
		exit('Yaroqsiz so\'rov (CSRF).');
	}
}

function is_logged_in() {
	return !empty($_SESSION['admin_ok']);
}

function require_login() {
	if (!is_logged_in()) {
		redirect('login.php');
	}
}

function redirect($url) {
	header('Location: ' . $url);
	exit;
}

// Allow-list'dan jadval konfiguratsiyasini olish. Ruxsat etilmagan bo'lsa — null.
function table_config($key) {
	global $ADMIN_TABLES;
	return $ADMIN_TABLES[$key] ?? null;
}

// SHOW COLUMNS orqali ustunlar ro'yxati. $table FAQAT allow-list kalitlaridan
// kelishi shart (chaqiruvchi table_config() bilan tekshirgan bo'lishi kerak).
function table_columns($db, $table) {
	$cols = [];
	$res = mysqli_query($db, 'SHOW COLUMNS FROM `' . $table . '`');
	if (!$res) {
		error_log('admin SHOW COLUMNS failed: ' . mysqli_error($db));
		return $cols;
	}
	while ($row = mysqli_fetch_assoc($res)) {
		$cols[] = [
			'name'  => $row['Field'],
			'type'  => $row['Type'],
			'null'  => ($row['Null'] === 'YES'),
			'key'   => $row['Key'],
			'extra' => $row['Extra'],
		];
	}
	return $cols;
}

// Ustun "uzun matn"mi (textarea kerakmi)?
function is_textarea_col($type) {
	return (stripos($type, 'text') !== false || stripos($type, 'blob') !== false);
}
