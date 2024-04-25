<?php

include 'connection.php';

date_default_timezone_set("UTC");
$now = date("Y-m-d H:i:s", time() + (OFFSET * 3600));
$first_day_this_month = date("Y-m-01");

if(!isset($_COOKIE['darkmode'])){ $_COOKIE['darkmode'] = 'off'; }
if(!isset($_COOKIE['rightNavBtn'])){ setcookie("rightNavBtn", "on", time()+60*60*24*365); }
if(!isset($_COOKIE['lockMouse'])){ setcookie("lockMouse", "off", time()+60*60*24*365); }
if(!isset($_COOKIE['lang'])){ $_COOKIE['lang'] = "uzl"; setcookie("lang", "uzl", time()+60*60*24*365); }
if(!isset($_COOKIE['daily'])){ $_COOKIE['daily'] = "on"; setcookie("daily", "on", time()+60*60*24*365); }
if(!isset($_COOKIE['sideBar'])){ setcookie("sideBar", "on", time()+60*60*24*365); }

if(isset($_GET['lang']) && $_GET['lang'] != ''){
	setcookie("lang", $_GET['lang'], time()+60*60*24*365, ADMIN_PAGE);
	header("Location: $_SERVER[HTTP_REFERER]");
}

if(!isset($_COOKIE['logoskin'],$_COOKIE['asideskin'],$_COOKIE['headerskin'])){
	setcookie("logoskin", 'navy', time()+60*60*24*365);
	setcookie("asideskin", 'navy', time()+60*60*24*365);
	setcookie("headerskin", 'light', time()+60*60*24*365);
	setcookie("bgsite", $_COOKIE['bgsite'], time()-60);
	setcookie("bgblock", $_COOKIE['bgblock'], time()-60);
	setcookie("menubg", $_COOKIE['menubg'], time()-60);
	setcookie("headerbg", $_COOKIE['headerbg'], time()-60);
	header("Refresh:0");
}

session_start();

if(!isset($_SESSION['sessiontime'])){
	session_unset();
	if($page !='auth' && $page !='register' && $page != 'login' && $page != 'regaction' && $page != 'useradd'){
		header("Location: ".ADMIN_PAGE."auth");
	}
}

if(isset($_SESSION['sessiontime'])){
	if(time() > (intval($_SESSION['sessiontime']) + USER_UPDATE_TIME)){
		mysqli_query($db, "UPDATE ".PREFIX."users SET last_enter = '$now' WHERE id = '{$_SESSION['id']}'");
		if(time() < (intval($_SESSION['sessiontime']) + LOGOUTTIME)){
			$_SESSION['sessiontime'] = time();
		}
	}
	if(time() > (intval($_SESSION['sessiontime']) + LOGOUTTIME)){
		if($page != 'auth'){
			session_unset();
			header("Location: ".ADMIN_PAGE."auth");
		}
	}
}

$showContent = [
	'header' => ['menu' => false],
	'toolbar' => false,
	'footer' => false,
];