<?php

$page = 'login';

include '../unique/config.php';
include '../core/brain.php';
include '../core/functions.php';

if (isset($_POST['email'])) {$email = htmlspecialchars($_POST['email']); if ($email == '') {unset($email);}}
if (isset($_POST['password'])) {$password = htmlspecialchars($_POST['password']); if ($password == '') {unset($password);}}

if (empty($email)) {
	echo '<div class="alert alert-danger d-flex align-items-center p-5">
		<i class="ki-duotone ki-information-5 fs-2hx text-danger me-4"><span class="path1"></span><span class="path2"></span></i>
		<div class="d-flex flex-column">
			<h4 class="mb-1">'.lot_kir("Ushbulardan birini kiriting: pochta manzil, login, telefon raqam!").'</h4>
		</div>
	</div>';
	exit;
}

$email = trim($email);
$email = mb_strtolower($email);

$email_pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";

if(preg_match($email_pattern, $email) === 0){
	$email = removess($email);
}

if (empty($password)) {
	exit ('
	<div class="alert alert-danger d-flex align-items-center p-5">
		<i class="ki-duotone ki-information-5 fs-2hx text-danger me-4"><span class="path1"></span><span class="path2"></span></i>
		<div class="d-flex flex-column">
			<h4 class="mb-1 text-danger">'.lot_kir("Parolni kiriting!").'</h4>
		</div>
	</div>');
}

$password = trim($password);

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
	$ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	$ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif(!empty($_SERVER['REMOTE_ADDR'])) {
	$ip = $_SERVER['REMOTE_ADDR'];
}
$password = strrev(md5($password));

$minus5minute_from_now = date("Y-m-d H:i:s", strtotime("-5 minutes", strtotime($now)));

$get_errors = mysqli_query($db, "SELECT COUNT(ip) as cnt FROM ".PREFIX."ipqulflash WHERE ip='$ip' AND date > '$minus5minute_from_now' AND response_code = 401");
$soni = $get_errors->fetch_assoc()['cnt'];

if ($soni >= 10) {
	exit('
	<div class="alert alert-danger d-flex align-items-center p-5">
		<i class="ki-duotone ki-information-5 fs-2hx text-danger me-4"><span class="path1"></span><span class="path2"></span></i>
		<div class="d-flex flex-column">
			<h4 class="mb-1 text-danger">'.lot_kir("Siz maʼlumotlarni 10 martadan ortiq noto‘g‘ri terdingiz. Keyingi urinish uchun 5 daqiqa kuting.").'</h4>
		</div>
	</div>');
}

$device = $_SERVER['HTTP_USER_AGENT'];

$result = mysqli_query($db, "SELECT u.* FROM ".PREFIX."users u WHERE u.email='$email' AND u.password='$password' AND u.active='1'");
$myrow = mysqli_fetch_assoc($result);
if (empty($myrow['id'])) {
	mysqli_query ($db, "INSERT INTO ".PREFIX."ipqulflash VALUES (NULL, '$ip', '$now', '401', NULL, '$device')");

	exit('
	<div class="alert alert-danger d-flex align-items-center p-5">
		<i class="ki-duotone ki-information-5 fs-2hx text-danger me-4"><span class="path1"></span><span class="path2"></span></i>
		<div class="d-flex flex-column">
			<h4 class="mb-1 text-danger">'.lot_kir("Urinishlar soni ".(10 - ($soni+1))." ta qoldi").'</h4>
			<span>'.lot_kir("Login yoki parol noto‘g‘ri. Iltimos, tekshirib qaytadan urinib ko‘ring!").'</span>
		</div>
	</div>
	');

} else {

	mysqli_query ($db, "INSERT INTO ".PREFIX."ipqulflash VALUES (NULL, '$ip', '$now', '200', {$myrow['id']}, '$device')");
	mysqli_query ($db, "UPDATE ".PREFIX."users SET last_enter = '$now' WHERE (email='$email' or tel='$email' or login='$email') AND password='$password' AND active = '1'");

	$_SESSION['id'] = $myrow['id'];
	$_SESSION['email'] = $myrow['email'];
	$_SESSION['fio'] = $myrow['fio'];
	$_SESSION['role'] = $myrow['role'];
	$_SESSION['role_value'] = $myrow['role_value'];
	if($myrow['type'] != '') $_SESSION['type'] = $myrow['type'];
	$_SESSION['sessiontime'] = time();
	$_SESSION['auth_time'] = time();

	if (isset($_POST['save'])){
		setcookie("email", $_POST["email"], time()+60*60*24*50, '/');
		setcookie("password", $_POST["password"], time()+60*60*24*50, '/');
	}
	if (!isset($_POST['save'])){
		setcookie("email", $_POST["email"], time()-1, '/');
		setcookie("password", $_POST["password"], time()-1, '/');
	}
	echo "<html><head><meta http-equiv='Refresh' content='0; URL=".ADMIN_PAGE."'></head></html>";
	exit;
}