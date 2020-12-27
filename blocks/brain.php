<?
session_start();
require 'db.php';

if(!isset($_COOKIE['lang'])) $_COOKIE['lang'] = 'uzk';

if(isset($_GET['lang'])){header("Location: ".$_SERVER['HTTP_REFERER']);}

if(!isset($_COOKIE['qori'])){$_COOKIE['qori'] = "Alafasy_128kbps";}
if(!isset($_COOKIE['headerSlider'])){$_COOKIE['headerSlider'] = "on";}
if(!isset($_COOKIE['headerImg'])){$_COOKIE['headerImg'] = "on";}

if(!isset($_COOKIE['sendToTg'])) {
	setcookie("sendToTg", time(), time()+60*60*24);
}elseif($_COOKIE['sendToTg'] > time() + (60 * 60 * 24)){
    echo "Eskidan ketdi Eskidan ketdi ketdi Eskidan ketdi ketdi Eskidan ketdi ketdi Eskidan ketdi ketdi Eskidan ketdi ketdi Eskidan ketdi ketdi Eskidan ketdi ketdi Eskidan ketdi ketdi Eskidan ketdi ketdi Eskidan ketdi ketdi Eskidan ketdi ketdi Eskidan ketdi ketdi Eskidan ketdi ketdi Eskidan ketdi ketdi Eskidan ketdi ketdi Eskidan ketdi ketdi Eskidan ketdi ketdi Eskidan ketdi ketdi Eskidan ketdi ketdi Eskidan ketdi ketdi Eskidan ketdi ketdi Eskidan ketdi ketdi Eskidan ketdi ketdi Eskidan ketdi ketdi Eskidan ketdi ketdi Eskidan ketdi ketdi Eskidan ketdi ketdi Eskidan ketdi ketdi Eskidan ketdi";
    setcookie("sendToTg", time(), time()+(60*60*24));
}

if(isset($_GET['lang'])){
    if($_GET['lang'] == 'uzl'){
        setcookie("lang", "ru", time()-60);
        setcookie("lang", "en", time()-60);
        setcookie("lang", "uzk", time()-60);
        setcookie("lang", "uzl", time()+60*60*24*365);
        header("Location: $_SERVER[HTTP_REFERER]");
    }elseif($_GET['lang'] == 'uzk'){
        setcookie("lang", "ru", time()-60);
        setcookie("lang", "en", time()-60);
        setcookie("lang", "uzl", time()-60);
        setcookie("lang", "uzk", time()+60*60*24*365);
        header("Location: $_SERVER[HTTP_REFERER]");
    }elseif($_GET['lang'] == 'ru'){
        setcookie("lang", "uzl", time()-60);
        setcookie("lang", "uzk", time()-60);
        setcookie("lang", "en", time()-60);
        setcookie("lang", "ru", time()+60*60*24*365);
        header("Location: $_SERVER[HTTP_REFERER]");
    }elseif($_GET['lang'] == 'en'){
        setcookie("lang", "uzl", time()-60);
        setcookie("lang", "uzk", time()-60);
        setcookie("lang", "ru", time()-60);
        setcookie("lang", "en", time()+60*60*24*365);
        header("Location: $_SERVER[HTTP_REFERER]");
    }
}


date_default_timezone_set("UTC");
$now = date("Y-m-d H:i:s", time() + 5 * 3600);

