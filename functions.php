<?
if(session_status() === PHP_SESSION_NONE) session_start();

$siteTitle = word(' | Qur’on va Sunnat');

function word($value){
    if(($_COOKIE['lang'] ?? '') == 'uzk'){
        return tokr($value);
    }else{
        return $value;
    }
}

function shareTg($url, $text) {
  $share_url = 'https://t.me/share/url?url='.rawurlencode($url).'&text='.rawurlencode($text);
  return "$share_url";
}

function get($field, $table){
    $query = "SELECT $field FROM $table";
    return $query;
}
function getAll($table){
    $query = "SELECT * FROM $table";
    return $query;
}
function getAllOrderLimit($table, $order, $limit){
    $query = "SELECT * FROM $table ORDER BY $order LIMIT $limit";
    return $query;
}
function getWhere($table, $order, $limit){
    $query = "SELECT * FROM $table ORDER BY $order LIMIT $limit";
    return $query;
}
function update($table, $set, $where){
    $query = "UPDATE $table SET $set WHERE $where";
    return $query;
}

function arabicNumbers($value){
    $converter = array('0'=>'٠','1' => '١','2'=>'٢','3'=>'٣','4'=>'٤','5'=>'٥','6'=>'٦','7'=>'٧','8'=>'٨','9'=>'٩');
    $value = strtr($value, $converter);
    return $value;
}

function sessions(){
    ?>
<pre><?= print_r($_SESSION) ?></pre>
<?
}

function cookies(){
    ?>
<pre><?= print_r($_COOKIE) ?></pre>
<?
}
function printr($val){
    ?>
<pre><?= print_r($val) ?></pre>
<?
    return $val;
}

function bytes($bytes){
    if ($bytes >= 1073741824){
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    }
    elseif ($bytes >= 1048576){
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    }
    elseif ($bytes >= 1024){
        $bytes = number_format($bytes / 1024) . ' KB';
    }
    elseif ($bytes > 1){
        $bytes = $bytes . ' bytes';
    }
    elseif ($bytes == 1){
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}

function search_file($folderName, $fileName)
{
    $dir = opendir($folderName);
    while (($file = readdir($dir)) !== false){
        if($file != "." && $file != ".."){
            if(is_file($folderName."/".$file)){
                if($file == $fileName) return $folderName."/".$file;
            }
            if(is_dir($folderName."/".$file)) return search_file($folderName."/".$file, $fileName);
        }
    }
    closedir($dir);
}

function phone_number12($sPhone)
{
    $sPhone = preg_replace("/[^0-9]/",'',$sPhone);
    if(strlen($sPhone) != 12) return(False);
    $sArea = substr($sPhone, 0,3);
    $sArea2 = substr($sPhone, 3,2);
    $sPrefix = substr($sPhone,5,3);
    $sNumber = substr($sPhone,8,2);
    $sNumber2 = substr($sPhone,10,2);
    $sPhone = "(".$sArea." ".$sArea2.")".$sPrefix."-".$sNumber."-".$sNumber2;
    return($sPhone);
}

function phone_number9($sPhone)
{
    $sPhone = preg_replace("/[^0-9]/",'',$sPhone);
    if(strlen($sPhone) != 9) return(False);
    $sArea = substr($sPhone, 0,2);
    $sPrefix = substr($sPhone,2,3);
    $sNumber = substr($sPhone,5,2);
    $sNumber2 = substr($sPhone,7,2);
    $sPhone = " ".$sArea." ".$sPrefix."-".$sNumber."-".$sNumber2;
    return($sPhone);
}

function phone_number9w($sPhone)
{
    $sPhone = preg_replace("/[^0-9]/",'',$sPhone);
    if(strlen($sPhone) != 9) return(False);
    $sArea = substr($sPhone, 0,2);
    $sPrefix = substr($sPhone,2,3);
    $sNumber = substr($sPhone,5,2);
    $sNumber2 = substr($sPhone,7,2);
    $sPhone = " (".$sArea.") ".$sPrefix."-".$sNumber."-".$sNumber2;
    return($sPhone);
}

function randomColour()
{
    // Found here https://css-tricks.com/snippets/php/random-hex-color/
    $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
    $color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
    return $color;
}

function translitkir_lot($value)
{
$converter = array(
' Е' =>' Ye','Ц'=>'Ts','Ч'=>'Ch','Ш'=>'Sh','Щ'=>'Shh','Ъ'=>'``','Ы'=>'Y`','Ь'=>'`','Э'=>'E','Ю'=>'Yu','Я'=>'Ya','Ғ'=>'G‘','Қ'=>'Q','Ҳ'=>'H','Ў'=>'O‘','А'=>'A','Б'=>'B','В'=>'V','Г'=>'G','Д'=>'D','Ё'=>'Yo','Ж'=>'J','З'=>'Z','И'=>'I','Й'=>'Y','К'=>'K','Л'=>'L','М'=>'M','Н'=>'N','О'=>'O','П'=>'P','Р'=>'R','С'=>'S','Т'=>'T','У'=>'U','Ф'=>'F','Х'=>'X',

' е'=>' ye','а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','ё'=>'yo','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'x','ц'=>'ts','ч'=>'ch','ш'=>'sh','щ'=>'shh','ъ'=>'’','ы'=>'y`','ь'=>'’','э'=>'e','ю'=>'yu','я'=>'ya','ғ'=>'g‘','қ'=>'q','ҳ'=>'h','ў'=>'o‘',
"<"=>"<",
">"=>">",
"<strong>"=>"<strong>",
"</strong>"=>"</strong>",
'[br]' => '<br>',
'<p>' => '<p>','</p>' => '</p>',
'<i>' => '<i>','</i>' => '</i>',
'<u>' => '<u>','</u>' => '</u>',
'<ul>' => '<ul>','</ul>' => '</ul>',
'<li>' => '<li>','</li>' => '</li>',
'<em>' => '<em>','</em>' => '</em>',
'<strong>' => '<strong>','</strong>' => '</strong>',
'<code>' => '<code>','</code>' => '</code>',
	);

	$value = str_replace(array_keys($converter),array_values($converter),$value);
	return $value;
}

function tokr($value)
{
	$converter = array('o‘'=>'ў','O‘'=>'Ў',' E'=>' Э','E'=>' Э','Ya'=>'Я','Yu'=>'Ю','Yo'=>'Ё','yo'=>'ё','G‘'=>'Ғ','Ch'=>'Ч','CH'=>'Ч','Sh'=>'Ш','SH'=>'Ш','ʼ'=>'ъ',' e'=>' э','ya'=>'я','yu'=>'ю','ye'=>'е','’'=>'ъ','g‘'=>'ғ','ch'=>'ч','sh'=>'ш','A'=>'А','B'=>'Б','C'=>'С','D'=>'Д','F'=>'Ф','G'=>'Г','H'=>'Ҳ','I'=>'И','J'=>'Ж','K'=>'К','L'=>'Л','M'=>'М','N'=>'Н','O' => 'О','P'=>'П','Q'=>'Қ','R'=>'Р','S'=>'С','T'=>'Т','U'=>'У','V'=>'В','W'=>'В','X'=>'Х','Y'=>'Й','Z'=>'З','a'=>'а','b'=>'б','c'=>'с','d'=>'д','e'=>'е','f'=>'ф','g'=>'г','h'=>'ҳ','i'=>'и','j'=>'ж','k'=>'к','l'=>'л','m'=>'м','n'=>'н','o'=>'о','p'=>'п','q'=>'қ','r'=>'р','s'=>'с','t'=>'т','u'=>'у','v'=>'в','w'=>'в','x'=>'х','y'=>'й','z'=>'з');

	$value = str_replace(array_keys($converter),array_values($converter),$value);
    $value = str_replace('<бр>','<br>',$value);
    $value = str_replace('<п>','<p>',$value);
    $value = str_replace('</п>','</p>',$value);
    $value = str_replace('<ҳ6>','<h6>',$value);
    $value = str_replace('</ҳ6>','</h6>',$value);
	return $value;
}


function textTypeWT($value){
    $value = trim($value);
    $value = str_replace("ʻ", "'",$value);
    $value = str_replace("ʼ", "'",$value);
    $value = str_replace("O'", "O‘",$value);
    $value = str_replace("o'", "o‘",$value);
    $value = str_replace("G'", "G‘",$value);
    $value = str_replace("g'", "g‘",$value);
    $value = str_replace("'", "’",$value);
    $value = str_replace(' "', " “",$value);
    $value = str_replace('" ', "” ",$value);
    $value = str_replace('", ', "”, ",$value);
    $value = translitkir_lot($value);
    $value = stripslashes($value);
    $value = htmlspecialchars($value);
    return($value);
}

function textType($value)
{
    $value = trim($value);
    $value = str_replace("ʻ", "'",$value);
    $value = str_replace("ʼ", "'",$value);
    $value = str_replace("O'", "O‘",$value);
    $value = str_replace("o'", "o‘",$value);
    $value = str_replace("G'", "G‘",$value);
    $value = str_replace("g'", "g‘",$value);
    $value = str_replace("'", "’",$value);
    $value = str_replace(' "', " “",$value);
    $value = str_replace('" ', "” ",$value);
    $value = str_replace('", ', "”, ",$value);
    $value = stripslashes($value);
    $value = htmlspecialchars($value);
    return($value);
}

function textType2($value)
{
    // textType bilan bir xil — takror kodni olib tashlash uchun alias.
    return textType($value);
}

function modalScs($value){
    $value = '
<div class="mymodal">
<div class="mymodal-success">
    <div class="header">
      <h2 class="title">'.word('Muvaffaqiyatli').'</h2>
    </div>
    <div class="content">
      <div>'.$value.'</div>
    </div>
    <div class="my-modal-actions">
      <button type="button" class="my-modal-confirm btn btn-secondary">OK</button>
    </div>
    </div>
</div>';
    return($value);
}

function modalErr($value){
    $value = '
    <div class="mymodal">
        <div class="mymodal-error">
            <div class="header">
            <h2 class="title">'.word('Xatolik').'</h2>
            </div>
            <div class="content">
            <div>'.$value.'</div>
            </div>
            <div class="my-modal-actions">
            <button type="button" class="my-modal-confirm btn btn-secondary">OK</button>
            </div>
        </div>
    </div>';
    return($value);
}

function modalInfo($value,$val2,$val3){
    $value = '
<div class="my-modal-container my-modal-center my-modal-shown" style="overflow-y: auto;">
  <div aria-labelledby="my-modal-title" aria-describedby="my-modal-content" class="my-modal-popup my-modal-modal my-modal-show" tabindex="-1" role="dialog" aria-live="assertive" aria-modal="true" style="display: flex;">
    <div class="my-modal-header">
        <div class="my-modal-icon my-modal-question my-modal-animate-question-icon" style="display:flex">
        </div>
      <h2 class="my-modal-title" id="my-modal-title" style="display: flex;">'.$value.'</h2>
    </div>
    <div class="my-modal-actions">
      <button type="button" class="btn btn-success m-btn m-btn--wide my-modal-styled err w-25" id="my-modal-cancel">'.$val2.'</button>
      <button type="button" class="btn btn-danger m-btn m-btn--wide my-modal-styled err w-25" id="my-modal-confirm">'.$val3.'</button>
    </div>
  </div>
</div>';
    return($value);
}

function toastScs($value){
    $value = '<div id="toast-container" class="toast-top-full-width"><div class="toast toast-success" aria-live="polite"><button type="button" class="toast-close-button" role="button">×</button><div class="toast-message">'.$value.'</div></div></div>';
    return($value);
}

function toastErr($value){
    $value = '<div id="toast-container" class="toast-top-full-width"><div class="toast toast-error" aria-live="polite"><button type="button" class="toast-close-button" role="button">×</button><div class="toast-message">'.$value.'</div></div></div>';
    return($value);
}

function nf($value){
    $value = number_format($value,0,',',' ');
    return $value;
}

function d($val){

    $date_str = new DateTime($val);
    $date = $date_str->Format('d.m.Y');
    $date_month = $date_str->Format('d.m');
    $date_year = $date_str->Format('Y');

    $date_time = $date_str->Format('H:i');

    $ndate = date('d.m.Y');
    $ndate_time = date('H:i');
    $ndate_time_m = date('i');
    $ndate_exp = explode('.',$date);

    if($_COOKIE['lang'] == 'uzl'){
        $nmonth = array(1=>'Yanvar',2=>'Fevral',3=>'Mart',4=>'Aprel',5=>'May',6=>'Iyun',7=>'Iyul',8=>'Avgust',9=>'Sentabr',10=>'Oktabr',11=>'Noyabr',12=>'Dekabr');
    }elseif($_COOKIE['lang'] == 'uzk'){
        $nmonth = array(1=>'Январ',2=>'Феврал',3=>'Март',4=>'Апрел',5=>'Май',6=>'Июн',7=>'Июл',8=>'Август',9=>'Сентябр',10=>'Октябр',11=>'Ноябр',12=>'Декабр');
    }elseif($_COOKIE['lang'] == 'en'){
        $nmonth = array(1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'Oktober',11=>'November',12=>'December');
    }elseif($_COOKIE['lang'] == 'ru'){
        $nmonth = array(1=>'Январь',2=>'Февраль',3=>'Март',4=>'Апрел',5=>'Май',6=>'Июнь',7=>'Июль',8=>'Август',9=>'Сентябр',10=>'Октябр',11=>'Ноябр',12=>'Декабр');
    }

    foreach($nmonth as $key => $value) {
    if ($key == intval($ndate_exp[1])) $nmonth_name = $value;
    }
    if($date == date('d.m.Y')) {
        if($_COOKIE['lang'] == 'uzl'){
            $val = 'Bugun';
        }elseif($_COOKIE['lang'] == 'uzk'){
             $val = 'Бугун';
        }elseif($_COOKIE['lang'] == 'en'){
            $val = 'Today';
        }elseif($_COOKIE['lang'] == 'ru'){
            $val = 'Сегодня';
        }
    }
    elseif ($date == date('d.m.Y',strtotime('-1 day'))) {
        if($_COOKIE['lang'] == 'uzl'){
            $val = 'Kecha';
        }elseif($_COOKIE['lang'] == 'uzk'){
             $val = 'Кеча';
        }elseif($_COOKIE['lang'] == 'en'){
            $val = 'Yesterday';
        }elseif($_COOKIE['lang'] == 'ru'){
            $val = 'Вчера';
        }
    }
    else if ($date != date('d.m.Y') && $date_year != date('Y')) {
    $val = $ndate_exp[0].'-'.$nmonth_name.' '.$ndate_exp[2];
    }
    else {$val = $ndate_exp[0].'-'.$nmonth_name;}
    return($val);
}


function dt($val){

    $date_str = new DateTime($val);
    $date = $date_str->Format('d.m.Y');
    $date_month = $date_str->Format('d.m');
    $date_year = $date_str->Format('Y');

    $date_time = $date_str->Format('H:i');

    $ndate = date('d.m.Y');
    $ndate_time = date('H:i');
    $ndate_time_m = date('i');
    $ndate_exp = explode('.',$date);

    if($_COOKIE['lang'] == 'uzl'){
        $nmonth = array(1=>'Yanvar',2=>'Fevral',3=>'Mart',4=>'Aprel',5=>'May',6=>'Iyun',7=>'Iyul',8=>'Avgust',9=>'Sentabr',10=>'Oktabr',11=>'Noyabr',12=>'Dekabr');
    }elseif($_COOKIE['lang'] == 'uzk'){
        $nmonth = array(1=>'Январ',2=>'Феврал',3=>'Март',4=>'Апрел',5=>'Май',6=>'Июн',7=>'Июл',8=>'Август',9=>'Сентябр',10=>'Октябр',11=>'Ноябр',12=>'Декабр');
    }elseif($_COOKIE['lang'] == 'en'){
        $nmonth = array(1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'Oktober',11=>'November',12=>'December');
    }elseif($_COOKIE['lang'] == 'ru'){
        $nmonth = array(1=>'Январь',2=>'Февраль',3=>'Март',4=>'Апрел',5=>'Май',6=>'Июнь',7=>'Июль',8=>'Август',9=>'Сентябр',10=>'Октябр',11=>'Ноябр',12=>'Декабр');
    }

    foreach($nmonth as $key => $value) {
    if ($key == intval($ndate_exp[1])) $nmonth_name = $value;
    }
    if($date == date('d.m.Y')) {
        if($_COOKIE['lang'] == 'uzl'){
            $val = 'Bugun '.$date_time;
        }elseif($_COOKIE['lang'] == 'uzk'){
             $val = 'Бугун '.$date_time;
        }elseif($_COOKIE['lang'] == 'en'){
            $val = 'Today '.$date_time;
        }elseif($_COOKIE['lang'] == 'ru'){
            $val = 'Сегодня '.$date_time;
        }
    }
    elseif ($date == date('d.m.Y',strtotime('-1 day'))) {
        if($_COOKIE['lang'] == 'uzl'){
            $val = 'Kecha '.$date_time;
        }elseif($_COOKIE['lang'] == 'uzk'){
             $val = 'Кеча '.$date_time;
        }elseif($_COOKIE['lang'] == 'en'){
            $val = 'Yesterday '.$date_time;
        }elseif($_COOKIE['lang'] == 'ru'){
            $val = 'Вчера '.$date_time;
        }
    }
    else if ($date != date('d.m.Y') && $date_year != date('Y')) {
    $val = $ndate_exp[0].'-'.$nmonth_name.' '.$ndate_exp[2];
    }
    else {$val = $ndate_exp[0].'-'.$nmonth_name.' '.$date_time;}
    return($val);
}


function dwt2($val){

    $date_str = new DateTime($val);
    $date = $date_str->Format('d.m.Y');
    $date_month = $date_str->Format('d.m');
    $date_year = $date_str->Format('Y');

    $date_time = $date_str->Format('H:i');

    $ndate = date('d.m.Y');
    $ndate_time = date('H:i');
    $ndate_time_m = date('i');
    $ndate_exp = explode('.',$date);

    if($_COOKIE['lang'] == 'uzl'){
        $nmonth = array(1=>'Yanvar',2=>'Fevral',3=>'Mart',4=>'Aprel',5=>'May',6=>'Iyun',7=>'Iyul',8=>'Avgust',9=>'Sentabr',10=>'Oktabr',11=>'Noyabr',12=>'Dekabr');
    }elseif($_COOKIE['lang'] == 'uzk'){
        $nmonth = array(1=>'Январ',2=>'Феврал',3=>'Март',4=>'Апрел',5=>'Май',6=>'Июн',7=>'Июл',8=>'Август',9=>'Сентябр',10=>'Октябр',11=>'Ноябр',12=>'Декабр');
    }elseif($_COOKIE['lang'] == 'en'){
        $nmonth = array(1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'Oktober',11=>'November',12=>'December');
    }elseif($_COOKIE['lang'] == 'ru'){
        $nmonth = array(1=>'Январь',2=>'Февраль',3=>'Март',4=>'Апрел',5=>'Май',6=>'Июнь',7=>'Июль',8=>'Август',9=>'Сентябр',10=>'Октябр',11=>'Ноябр',12=>'Декабр');
    }

    foreach($nmonth as $key => $value) {
    if ($key == intval($ndate_exp[1])) $nmonth_name = $value;
    }
    if($date == date('d.m.Y')) {
        if($_COOKIE['lang'] == 'uzl'){
            $val = $date_time;
        }
    }
    elseif ($date == date('d.m.Y',strtotime('-1 day'))) {
        if($_COOKIE['lang'] == 'uzl'){
            $val = 'Kecha '.$date_time;
        }elseif($_COOKIE['lang'] == 'uzk'){
             $val = 'Кеча '.$date_time;
        }elseif($_COOKIE['lang'] == 'en'){
            $val = 'Yesterday '.$date_time;
        }elseif($_COOKIE['lang'] == 'ru'){
            $val = 'Вчера '.$date_time;
        }
    }
    else if ($date != date('d.m.Y') && $date_year != date('Y')) {
    $val = $ndate_exp[0].'-'.$nmonth_name.' '.$ndate_exp[2];
    }
    else {$val = $ndate_exp[0].'-'.$nmonth_name;}
    return($val);
}


function cmn(){

    $date = date('m');

    $nmonth = array(1=>'Yanvar',2=>'Fevral',3=>'Mart',4=>'Aprel',5=>'May',6=>'Iyun',7=>'Iyul',8=>'Avgust',9=>'Sentabr',10=>'Oktabr',11=>'Noyabr',12=>'Dekabr');

    foreach($nmonth as $key => $value) {
        if ($key == intval(date('m'))) {
            if($_COOKIE['lang'] == 'uzl') $val = $value;else
            $val = tokr($value);
        }
    }
    return $val;
}


function wn(){

    $weekname = array(1=>'Dushanba',2=>'Seshanba',3=>'Chorshanba',4=>'Payshanba',5=>'Juma',6=>'Shanba',7=>'Yakshanba');

    foreach($weekname as $key => $value) {
        if ($key == intval(date('N'))) {
            if($_COOKIE['lang'] == 'uzl') $val = $value;else
            $val = tokr($value);
        }
    }
    return $val;
}


function removeSymbol($value){

    $value = str_replace("(","",$value);
    $value = str_replace(")","",$value);
    $value = str_replace("-","",$value);
    $value = str_replace(" ","",$value);
    $value = str_replace(".","",$value);
    $value = str_replace("&","",$value);

    return $value;
}



// Tashqi API (JSON) javobini fayl keshida saqlaydi — har so'rovdagi jonli curl o'rniga.
// $ttl soniyada amal qiladi. Tarmoq uzilsa, eskirgan kesh (bo'lsa) qaytariladi (graceful).
function cached_json_get($url, $ttl = 21600){
	$dir = __DIR__ . '/cache';
	if(!is_dir($dir)) @mkdir($dir, 0775, true);
	$file = $dir . '/api_' . md5($url) . '.json';

	// Yangi kesh bo'lsa — undan foydalanamiz.
	if(is_file($file) && (time() - filemtime($file)) < $ttl){
		$cached = @file_get_contents($file);
		if($cached !== false && $cached !== '') return json_decode($cached, true);
	}

	// Jonli olish (qisqa timeout — API sekin/uzilgan bo'lsa sahifa kutib qolmasin).
	$response = false;
	if(function_exists('curl_init')){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		$response = curl_exec($ch);
		curl_close($ch);
	}

	if($response !== false && $response !== ''){
		@file_put_contents($file, $response, LOCK_EX);
		return json_decode($response, true);
	}

	// API javob bermadi — eskirgan kesh bo'lsa ham, yo'qdan ko'ra yaxshi.
	if(is_file($file)){
		$stale = @file_get_contents($file);
		if($stale !== false && $stale !== '') return json_decode($stale, true);
	}
	return null;
}

// Berilgan milodiy sana (d-m-Y) uchun hijriy sana ma'lumoti (keshlangan).
function getArabicDate($date){
	$data = cached_json_get('https://api.aladhan.com/v1/gToH?date=' . urlencode($date), 21600);
	return $data['data'] ?? null;
}

/* ---------------------------------------------------------------------
 * Urg'u rangi (accent) yordamchilari — foydalanuvchi tanlagan rang (globalColor
 * cookie) asosida dizayn-tizim CSS o‘zgaruvchilarini qayta bo‘yash uchun.
 * --------------------------------------------------------------------- */
function _hex_rgb($hex){
    $hex = ltrim($hex, '#');
    return array(hexdec(substr($hex,0,2)), hexdec(substr($hex,2,2)), hexdec(substr($hex,4,2)));
}

function _rgb_hex($r,$g,$b){
    return sprintf('#%02x%02x%02x', max(0,min(255,(int)round($r))), max(0,min(255,(int)round($g))), max(0,min(255,(int)round($b))));
}

// $f < 0 => qoraytirish, $f > 0 => oqartirish (oq tomon).
function _shade($hex, $f){
    list($r,$g,$b) = _hex_rgb($hex);
    if($f < 0){ $t = 1 + $f; $r *= $t; $g *= $t; $b *= $t; }
    else { $r += (255-$r)*$f; $g += (255-$g)*$f; $b += (255-$b)*$f; }
    return _rgb_hex($r,$g,$b);
}

// Tanlangan rangdan dizayn-tizim urg‘u o‘zgaruvchilarini hosil qiladi.
// Yaroqsiz qiymatda bo‘sh satr qaytaradi (hech narsa o‘zgarmaydi).
function accent_css($hex){
    if(!is_string($hex) || !preg_match('/^#[0-9a-fA-F]{6}$/', $hex)) return '';
    $green    = $hex;
    $green600 = _shade($hex, -0.12);
    $green700 = _shade($hex, -0.28);
    $green50  = _shade($hex, 0.90);
    return ":root{--green:$green;--green-600:$green600;--green-700:$green700;--green-50:$green50;}";
}
