<?php
require __DIR__ . '/inc/bootstrap.php';

// Allaqachon kirgan bo'lsa — bosh sahifaga.
if (is_logged_in()) {
	redirect('index.php');
}

$error = '';
$lock = login_lock_seconds();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	verify_csrf();

	if ($lock > 0) {
		// Qulflangan — urinishni tekshirmaymiz ham.
		$error = 'Juda ko\'p urinish. ' . $lock . ' soniyadan keyin qayta urinib ko\'ring.';
	} else {
		$user = trim($_POST['user'] ?? '');
		$pass = (string)($_POST['pass'] ?? '');

		$userOk = hash_equals(ADMIN_USER, $user);
		$passOk = password_verify($pass, ADMIN_PASS_HASH);

		if ($userOk && $passOk) {
			login_reset_throttle();
			session_regenerate_id(true);
			$_SESSION['admin_ok'] = true;
			redirect('index.php');
		}

		// Aniq xato bermaymiz (login yoki parol — bittasi noto'g'ri).
		login_register_fail();
		$lock = login_lock_seconds();
		$error = 'Login yoki parol noto\'g\'ri.';
		usleep(300000); // brute-force'ni biroz sekinlashtirish
	}
}

// GET'da ham qulflangan bo'lsa ogohlantiramiz.
if ($lock > 0 && $error === '') {
	$error = 'Juda ko\'p urinish. ' . $lock . ' soniyadan keyin qayta urinib ko\'ring.';
}
?><!DOCTYPE html>
<html lang="uz">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex, nofollow">
	<title>Kirish — Qur'on va Sunnat admin</title>
	<link rel="stylesheet" href="assets/admin.css">
</head>
<body class="adm-login-body">
	<form class="adm-login" method="post" action="login.php">
		<h1>Boshqaruv paneli</h1>
		<p class="adm-login-sub">Qur'on va Sunnat</p>
		<?php if ($error !== ''): ?>
			<div class="adm-alert adm-alert-error"><?= e($error) ?></div>
		<?php endif; ?>
		<?= csrf_field() ?>
		<label>
			<span>Login</span>
			<input type="text" name="user" autocomplete="username" autofocus required>
		</label>
		<label>
			<span>Parol</span>
			<input type="password" name="pass" autocomplete="current-password" required>
		</label>
		<button type="submit" class="adm-btn adm-btn-primary">Kirish</button>
	</form>
</body>
</html>
