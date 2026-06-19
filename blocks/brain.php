<?
if(session_status() === PHP_SESSION_NONE) session_start();

// Muhitga qarab xatolar: lokal/dev'da HAMMA xato ekranda ko‘rinadi (debug uchun),
// hosting/production'da ko‘rsatilmaydi — faqat log'ga yoziladi.
$host = $_SERVER['SERVER_NAME'] ?? ($_SERVER['HTTP_HOST'] ?? '');
$host = preg_replace('/:\d+$/', '', $host); // bo‘lsa, portni olib tashlash
$isDev = in_array($host, ['localhost', '127.0.0.1', '::1'], true)
	|| strpos($host, '.') === false                    // OSPanel papka-domeni, masalan "quronvasunnat"
	|| preg_match('/\.(local|test|localhost|dev)$/i', $host);

ini_set('log_errors', '1');
if ($isDev) {
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
} else {
	error_reporting(E_ALL & ~E_DEPRECATED);
	ini_set('display_errors', '0');
}

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

