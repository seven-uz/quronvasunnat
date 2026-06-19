<?php
	// Ulanish ma'lumotlari. Ishlab chiqarish (production) parollarini bu yerga
	// yozmang — db.local.php faylida saqlang (u .gitignore ichida).
	$host = "localhost";
	$user = "root";
	$password = "";
	$database = "quronvasunnat";

	$localConfig = __DIR__ . '/db.local.php';
	if (is_file($localConfig)) {
		require $localConfig;
	}

	$db = mysqli_connect($host, $user, $password, $database);

	if (!$db) {
		exit("Problem with selection of 'database'");
	}

	mysqli_set_charset($db, "utf8mb4");
?>