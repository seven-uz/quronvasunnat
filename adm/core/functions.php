<?php

if(end(explode('/', $_SERVER['PHP_SELF'])) === 'functions.php'){
	header("location: /");
	exit;
}

function arrayColumn($array = [], $key, $value){
	$result      = array();
	foreach ($array as $val) {
		$result[$val[$key]] = $val[$value];
	}
	return $result;
}

// function colsFromArray(array $array, $keys)
// {
//     if (!is_array($keys)) $keys = [$keys];
//     return array_map(function ($el) use ($keys) {
//         $o = [];
//         foreach($keys as $key){
//             //  if(isset($el[$key]))$o[$key] = $el[$key]; //you can do it this way if you don't want to set a default for missing keys.
//             $o[$key] = isset($el[$key])?$el[$key]:false;
//         }
//         return $o;
//     }, $array);
// }

function required($session, $level, $url, $post = null){
	if ($post != null) {
		if($session < $level && !isset($_POST)){
			header("Location: ".$url);
			exit;
		}
	} else {
		if ($session < $level) {
			header("Location: " . $url);
			exit;
		}
	}
}

function redirect($url){
	header("Location: ".$url);
	exit;
}

function getNow($offset = OFFSET){
	date_default_timezone_set("UTC");
	$time = time();
	$time += $offset * 3600;
	return date("Y-m-d H:i:s", $time);
}

function random_color_part() {
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}

function random_color() {
    return random_color_part() . random_color_part() . random_color_part();
}

function sqlBeauty($value) {

	$res = '';

	foreach (explode(' ', $value) as $key => $v) {
		if(
			$v === 'SELECT' ||
			$v === 'FROM' ||
			$v === 'WHERE' ||
			$v === 'ORDER' ||
			$v === 'GROUP' ||
			$v === 'LEFT' ||
			$v === 'BY' ||
			$v === 'JOIN' ||
			$v === 'RIGHT' ||
			$v === 'INTO' ||
			$v === 'VALUES' ||
			$v === 'SET' ||
			$v === 'INNER'
		){
			if($v === 'SELECT' || $v === 'BY' || $v === 'JOIN' || $v === 'INTO') $res .= $v."<br>&nbsp;&nbsp;&nbsp;&nbsp;"; else
			if($v === 'FROM' || $v === 'WHERE' || $v === 'VALUES' || $v === 'SET') $res .= '<br>'.$v."<br>&nbsp;&nbsp;&nbsp;&nbsp;"; else
			if($v === 'RIGHT' || $v === 'INNER' || $v === 'LEFT' || $v === 'ORDER' ||$v === 'GROUP') $res .= '<br>'.$v."&nbsp;";
		}else{
			$res .= $v."&nbsp;";
		}
	}

  return '<div class="text-left kt-font-xl"><code>'.$res.'</code></div>';
}

function delete_all_between($beginning, $end, $string) {
  $beginningPos = strpos($string, $beginning);
  $endPos = strpos($string, $end);
  if ($beginningPos === false || $endPos === false) {
    return $string;
  }

  $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);

  return delete_all_between($beginning, $end, str_replace($textToDelete, '', $string)); // recursion to ensure all occurrences are replaced
}

function posts($exit = 0){
	?><pre><?php $res = print_r($_POST); ?></pre><?php
	if($exit == 1){
		$res .= exit;
	}
	return $res;
}

function sessions($exit = 0){
	?><pre><?php $res = print_r($_SESSION); ?></pre><?php
	if($exit == 1){
		$res .= exit;
	}
	return $res;
}

function cookies($exit = 0){
    ?><pre><?php $res = print_r($_COOKIE); ?></pre><?php
    if($exit == 1){
        $res .= exit;
    }
    return $res;
}

function prr($val, $exit = 0){
	?><pre><code><?php $res = print_r($val); ?></code></pre><?php
	if($exit == 1){
		$res .= exit;
	}
	return $res;
}

function vd($val, $exit = 0){
	?><pre><code><?php $res = var_dump($val); ?></code></pre><?php
	if($exit == 1){
		$res .= exit;
	}
	return $res;
}

function pri($val, $exit = 0){
	$res = '<div class="text-left"><code>[<br>';
	if(is_array($val)){
		foreach($val as $k => $v){
			if(is_array($v)) {
				$res .= '&nbsp;&nbsp;&nbsp;&nbsp;['.$k.'] => [<br>';
				foreach ($v as $k2 => $v2){
					$res .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;['.$k2.'] => '.$v2.'<br>';
				}
				$res .= '&nbsp;&nbsp;&nbsp;&nbsp;],<br>';
			}else{
				$res .= '&nbsp;&nbsp;&nbsp;&nbsp;['.$k.'] => '.$v.'<br>';
			}
		}
	}else{
		$res .= $val.'<br>';
	}
	$res .= ']</code></div>';

	echo $res;

	if($exit == 1){
		exit;
	}
}

function baseError($lineNum, $errorNo, $errorText, $post){

	global $words;

	exit ('<strong>'.$words['error'] . ' №: ' . $errorNo . '</strong><br><br>
		<div class="text-left">' . $words['errorlinenum'] . ': ' . $lineNum . '<br><br>' .
		$words[$errorNo] . '<br><br>
		(' . $errorText . ')<br><br></div>
	<strong>' . $words['recievedquery'] . ': </strong><br>' . pri($post));
}

function string_between_two_string($str, $starting_word, $ending_word){
    $subtring_start = strpos($str, $starting_word);
    //Adding the starting index of the starting word to
    //its length would give its ending index
    $subtring_start += strlen($starting_word);
    //Length of our required sub string
    $size = strpos($str, $ending_word, $subtring_start) - $subtring_start;
    // Return the substring from the index substring_start of length size
    return substr($str, $subtring_start, $size);
}

function getFileLineNumber($value, $file){
    foreach ($file as $k => $line) {
        if(stripos($line, "baseError(".$value) === true){
            return $k;
        }
    }
}

function bytes($bytes){
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
}

function search_file($folderName, $fileName){
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

function phone_number12($sPhone){
    $sPhone = preg_replace("[^0-9]",'',$sPhone);
    if(strlen($sPhone) != 12) return(False);
    $sArea = substr($sPhone, 0,3);
    $sArea2 = substr($sPhone, 3,2);
    $sPrefix = substr($sPhone,5,3);
    $sNumber = substr($sPhone,8,2);
    $sNumber2 = substr($sPhone,10,2);
    $sPhone = "(".$sArea." ".$sArea2.")".$sPrefix."-".$sNumber."-".$sNumber2;
    return($sPhone);
}

function phone_number9($sPhone){
    $sPhone = preg_replace("[^0-9]",'',$sPhone);
    if(strlen($sPhone) != 9) return(False);
    $sArea = substr($sPhone, 0,2);
    $sPrefix = substr($sPhone,2,3);
    $sNumber = substr($sPhone,5,2);
    $sNumber2 = substr($sPhone,7,2);
    $sPhone = " ".$sArea." ".$sPrefix."-".$sNumber."-".$sNumber2;
    return($sPhone);
}

function phone_number9w($sPhone){
    $sPhone = preg_replace("[^0-9]",'',$sPhone);
    if(strlen($sPhone) != 9) return(False);
    $sArea = substr($sPhone, 0,2);
    $sPrefix = substr($sPhone,2,3);
    $sNumber = substr($sPhone,5,2);
    $sNumber2 = substr($sPhone,7,2);
    $sPhone = " (".$sArea.") ".$sPrefix."-".$sNumber."-".$sNumber2;
    return($sPhone);
}

function randomColour() {

    $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
    $color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
    return $color;
}

function translitkir_lot($value){
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
		'<code>' => '<code>','</code>' => '</code>',
	);

	$value = str_replace(array_keys($converter),array_values($converter),$value);
	return $value;
}

function translitlot_kir($value){
	$converter = array(
		'<p>' => '<p>','</p>' => '</p>',
		'<i>' => '<i>','</i>' => '</i>',
		'<u>' => '<u>','</u>' => '</u>',
		'<ul>' => '<ul>','</ul>' => '</ul>',
		'<li>' => '<li>','</li>' => '</li>',
		'<em>' => '<em>','</em>' => '</em>',
		'<strong>' => '<strong>','</strong>' => '</strong>',
		'<code>' => '<code>','</code>' => '</code>',
		'o‘'=>'ў','O‘'=>'Ў',' E'=>' Э','E'=>' Э','Ya'=>'Я','Yu'=>'Ю','Yo'=>'Ё','yo'=>'ё','G‘'=>'Ғ','Ch'=>'Ч','CH'=>'Ч','Sh'=>'Ш','SH'=>'Ш','ʼ'=>'ъ',' e'=>' э',
		'C'=>'С','D'=>'Д','F'=>'Ф','G'=>'Г','H'=>'Ҳ','I'=>'И','J'=>'Ж','K'=>'К','L'=>'Л','M'=>'М','N'=>'Н','O' => 'О','P'=>'П','ya'=>'я','yu'=>'ю','ye'=>'е','ts'=>'ц',
		'Q'=>'Қ','R'=>'Р','S'=>'С','T'=>'Т','U'=>'У','V'=>'В','W'=>'В','X'=>'Х','Y'=>'Й','Z'=>'З','’'=>'ъ','g‘'=>'ғ','ch'=>'ч','sh'=>'ш','A'=>'А','B'=>'Б',

		'a'=>'а','b'=>'б','c'=>'с','d'=>'д','e'=>'е','f'=>'ф','g'=>'г','h'=>'ҳ','i'=>'и','j'=>'ж','k'=>'к','l'=>'л','m'=>'м',
		'n'=>'н','o'=>'о','p'=>'п','q'=>'қ','r'=>'р','s'=>'с','t'=>'т','u'=>'у','v'=>'в','w'=>'в','x'=>'х','y'=>'й','z'=>'з',
	);

	$value = str_replace(array_keys($converter),array_values($converter),$value);
	$value = str_replace('<бр>', '<br>', $value);
	// $value = str_replace('ts', 'ц', $value);
	return $value;
}

function lot_kir($text){
	$text = textType2($text);
	if($_COOKIE['lang'] === 'uzk') return translitlot_kir($text); else return $text;
}

function textType($value){
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

function textType2($value){
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

function modalBonus($value,$val2,$val3,$get){
	$value = '
	<div class="swal2-bonus swal2-container swal2-center swal2-shown" style="overflow-y: auto;display:none" id="delbtn">
		<div aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-popup swal2-modal swal2-show" tabindex="-1" role="dialog" aria-live="assertive" aria-modal="true" style="display: flex;">
			<form id="bonus-confirm">
					<div class="swal2-header">
						<h2 class="swal2-title pb-5" id="swal2-title" style="display: flex;">'.$value.'</h2>
						<input type="hidden" name="id" id="swal2-bonus">
						<input type="hidden" name="group" value="'.$get.'">
						<input type="text" name="summa" class="form-control" id="number" required>
					</div>
					<div class="swal2-actions">
						<button type="button" class="btn btn-danger m-btn m-btn--wide swal2-styled err w-25" id="bonus-cancel">'.$val2.'</button>
						<button type="submit" class="btn btn-brand m-btn m-btn--wide swal2-styled err w-25">'.$val3.'</button>
					</div>
			</form>
		</div>
	</div>';
	return($value);
}

function modalInfo($value,$val2,$val3){
	$value = '
	<div class="swal2-container swal2-center swal2-shown" style="overflow-y: auto;display:none" id="delbtn">
		<div aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-popup swal2-modal swal2-show" tabindex="-1" role="dialog" aria-live="assertive" aria-modal="true" style="display: flex;">
			<div class="swal2-header">
					<div class="swal2-icon swal2-question swal2-animate-question-icon" style="display:flex">
					</div>
				<h2 class="swal2-title" id="swal2-title" style="display: flex;">'.$value.'</h2>
			</div>
			<div class="swal2-actions">
				<button type="button" class="btn btn-brand m-btn m-btn--wide swal2-styled err w-25" id="cancel">'.$val2.'</button>
				<button type="button" class="btn btn-danger m-btn m-btn--wide swal2-styled err w-25" id="confirm">'.$val3.'</button>
			</div>
		</div>
	</div>';
	return($value);
}

function toastScs($value){
    $value = '<div id="toast-container" class="toast-top-full-width"><div class="toast toast-success" aria-live="polite" style=""><button type="button" class="toast-close-button" role="button">×</button><div class="toast-message">'.$value.'</div></div></div>';
    return($value);
}

function toastErr($value){
    $value = '<div id="toast-container" class="toast-top-full-width"><div class="toast toast-error" aria-live="polite" style=""><button type="button" class="toast-close-button" role="button">×</button><div class="toast-message">'.$value.'</div></div></div>';
    return($value);
}

function nf($value, $symbol = '-', $after_zero = 0){
	if($value != ''){
    $value = number_format($value,$after_zero,',',' ');
	}else{
    $value = $symbol;
	}

	return $value;
}

function dateSimple($val){
	if($val == '' || is_null($val)){
		return '';
	}else{
		return date("d.m.Y", strtotime($val));
	}
}

function dateDefault($val, $type = 'date'){
	if($val == '' || is_null($val)){
		return '';
	}else{
		if($type === 'date'){
			return date("Y-m-d", strtotime($val));
		}elseif($type === 'time'){
			return date("Y-m-d H:i:s", strtotime($val));
		}
	}
}

function FormBuilder($FormVars = [], $fields){

	$result = '<form ';
	$result .= (!empty($FormVars['id'])) ? 'id="'.$FormVars['id'].'"' : '';
	$result .= '>';

	foreach ($fields as $key => $val) {

		foreach ($val as $k => $v) {
			if(isset($v['classes'])){
				$classes = ' '.$v['classes'];
			}
			if(isset($v['name'])){
				$name = 'name="'.$v['name'].'"';
			}
			if(isset($v['placeholder'])){
				$placeholder = ' placeholder="'.$v['placeholder'].'"';
			}
			if(isset($v['options'])){
				$options = '';
				foreach ($v['options'] as $opt) {
					$options .= $opt.' ';
				}
				$options = ' '.rtrim($options, ' ');
			}

			$result .= '<div class="form-floating mb-7">';
			if($key === 'input'){
				$result .= '<input type="'.$v['type'].'" class="form-control form-control-solid'.$classes.'"'. $name.$placeholder.$options.' />';
			}elseif($key === 'textarea'){
				$result .= '<textarea class="form-control form-control-solid'.$classes.'"'. $name.$placeholder.$options.'></textarea>';
			}elseif($key === 'select'){
				$result .= $v['options'];
			}
			$result .= '<label'; $result .= ($v['options'] && in_array('required', $v['options'])) ? ' class="required"' : ''; $result .= '>'.lot_kir($v['title']).'</label>';
			$result .= '</div>';
		}

	}

	$result .= '<div class="text-center pt-5">
		<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close">'.lot_kir("Inkor etish").'</button>
		<button type="submit" class="btn btn-primary">
			<input type="hidden" name="table" value="'.$FormVars['table'].'">
			<span class="indicator-label">'.lot_kir("Saqlash").'</span>
			<span class="indicator-progress">'.lot_kir("Amaliyot bajarilmoqda").'...
			<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
		</button>
	</div>';

	$result .= '</form>';

	echo $result;
}

function dod($val){
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

function dwt($val, $seconds = false){
    $date_str = new DateTime($val);
    $date = $date_str->Format('d.m.Y');
    $date_month = $date_str->Format('d.m');
    $date_year = $date_str->Format('Y');

		if($seconds === true){
			$date_time = $date_str->Format('H:i:s');
		}else{
			$date_time = $date_str->Format('H:i');
		}

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
    } elseif ($date == date('d.m.Y',strtotime('-1 day'))) {
			if($_COOKIE['lang'] == 'uzl'){
				$val = 'Kecha '.$date_time;
			}elseif($_COOKIE['lang'] == 'uzk'){
				$val = 'Кеча '.$date_time;
			}elseif($_COOKIE['lang'] == 'en'){
				$val = 'Yesterday '.$date_time;
			}elseif($_COOKIE['lang'] == 'ru'){
				$val = 'Вчера '.$date_time;
			}
    } else if ($date != date('d.m.Y') && $date_year != date('Y')) {
    	$val = $ndate_exp[0].'-'.$nmonth_name.' '.$ndate_exp[2];
    } else {
			$val = $ndate_exp[0].'-'.$nmonth_name.' '.$date_time;
		}
    return($val);
}

function dwmy($val){
	$date_str = new DateTime($val);
	$date = $date_str->Format('d.m.Y');
	$date_year = $date_str->Format('Y');

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

	return $nmonth_name.' '. $date_year;
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

function dcm(){
    $date = date('m');

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
        if ($key == intval($date[1])) $val = $value;
    }
    return($val);
}

function dm($val){
    return date("d.m", strtotime($val));
}

function mn($value, $config = false){

	if(strlen($value) > 2){
		$year = ' - '.date("Y", strtotime($value));
		$date = substr($value, 5, 2);
	}else{
		$date = date($value);
		$year = '';
	}

	if($_COOKIE['lang'] == 'uzl'){
		$nmonth = array(1=>'Yanvar',2=>'Fevral',3=>'Mart',4=>'Aprel',5=>'May',6=>'Iyun',7=>'Iyul',8=>'Avgust',9=>'Sentabr',10=>'Oktabr',11=>'Noyabr',12=>'Dekabr');
	}elseif($_COOKIE['lang'] == 'uzk'){
		$nmonth = array(1=>'Январ',2=>'Феврал',3=>'Март',4=>'Апрел',5=>'Май',6=>'Июн',7=>'Июл',8=>'Август',9=>'Сентябр',10=>'Октябр',11=>'Ноябр',12=>'Декабр');
	}elseif($_COOKIE['lang'] == 'en'){
		$nmonth = array(1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'Oktober',11=>'November',12=>'December');
	}elseif($_COOKIE['lang'] == 'ru'){
		$nmonth = array(1=>'Январь',2=>'Февраль',3=>'Март',4=>'Апрел',5=>'Май',6=>'Июнь',7=>'Июль',8=>'Август',9=>'Сентябр',10=>'Октябр',11=>'Ноябр',12=>'Декабр');
	}
	foreach($nmonth as $key => $val) {
		if ($key != intval($date)) continue;
		if($config === 'shorter'){
			if(date("Y", strtotime($value)) == date("Y")){
				$res = $val;
			}else{
				$res = $val.$year;
			}
		}else{
			$res = $val.$year;
		}
	}

	return($res);
}

function mns($value){

	if(strlen($value) > 2){
		$year = ' - '.date("Y", strtotime($value));
		$date = substr($value, 5, 2);
	}else{
		$date = date($value);
		$year = '';
	}

	if($_COOKIE['lang'] == 'uzl'){
			$nmonth = array(1=>'Yan',2=>'Fev',3=>'Mar',4=>'Apr',5=>'May',6=>'Iyun',7=>'Iyul',8=>'Avg',9=>'Sen',10=>'Okt',11=>'Noy',12=>'Dek');
	}elseif($_COOKIE['lang'] == 'uzk'){
			$nmonth = array(1=>'Ян',2=>'Фев',3=>'Мар',4=>'Апр',5=>'Май',6=>'Июн',7=>'Июл',8=>'Авг',9=>'Сен',10=>'Окт',11=>'Ноя',12=>'Дек');
	}elseif($_COOKIE['lang'] == 'en'){
			$nmonth = array(1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Dec');
	}elseif($_COOKIE['lang'] == 'ru'){
			$nmonth = array(1=>'Ян',2=>'Фев',3=>'Мар',4=>'Апр',5=>'Май',6=>'Июн',7=>'Июл',8=>'Авг',9=>'Сен',10=>'Окт',11=>'Ноя',12=>'Дек');
	}
	foreach($nmonth as $key => $value) {
			if ($key == intval($date)) {
				$val = $value;
			}
	}
	return($val);
}

function removess($value){
    $value = str_replace("(","",$value);
    $value = str_replace(")","",$value);
    $value = str_replace("-","",$value);
    $value = str_replace(" ","",$value);
    $value = str_replace(".","",$value);
    $value = str_replace("&","",$value);
    $value = str_replace("'","",$value);
    $value = str_replace("‘","",$value);
    $value = str_replace("ʼ","",$value);

    return($value);
}

function remove_spaces($value){
    return str_replace(" ","",$value);
}
function remove_undescore($value){
    return str_replace("_","",$value);
}

function errorMsg($value, $db, $words){

	$res = $words['error'] . ' №: <strong>' . mysqli_errno($db) . '</strong><br>' . $words[mysqli_errno($db)] . '. (' . mysqli_error($db) . ')<br>
		<strong>' . $words['recievedquery'] . ': </strong><br>' . $value;
	return $res;
}

function modalScs($value){

	global $words;

	$value = '
		<div class="swal2-container swal2-center swal2-shown" style="overflow-y: auto;" id="scs">
			<div aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-popup swal2-modal swal2-show" tabindex="-1" role="dialog" aria-live="assertive" aria-modal="true" style="display: flex;">
				<div class="swal2-header">
					<div class="swal2-icon swal2-success swal2-animate-success-icon">
						<div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div>
						<span class="swal2-success-line-tip"></span>
						<span class="swal2-success-line-long"></span>
						<div class="swal2-success-ring"></div>
						<div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div>
						<div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div>
					</div>
					<h2 class="swal2-title" id="swal2-title">'.$words['scsfinish'].'</h2>
				</div>
				<div class="swal2-content">
					<div id="swal2-content" style="display: block;">'.$value.'</div>
					<div class="swal2-validation-message" id="swal2-validation-message"></div>
				</div>
				<div class="swal2-actions">
					<button type="button" class="swal2-confirm btn btn-secondary swal2-styled scs">OK</button>
				</div>
			</div>
		</div>';
	return($value);
}

function modalErr($value){

	global $words;

	$value = '
	<div class="swal2-container swal2-center swal2-shown" style="overflow-y: auto;" id="err">
		<div aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-popup swal2-modal swal2-show" tabindex="-1" role="dialog" aria-live="assertive" aria-modal="true" style="display: flex;">
			<div class="swal2-header">
				<div class="swal2-icon swal2-error swal2-animate-error-icon" style="display: flex;">
					<span class="swal2-x-mark">
						<span class="swal2-x-mark-line-left"></span>
						<span class="swal2-x-mark-line-right"></span>
					</span>
				</div>
				<h2 class="swal2-title" id="swal2-title" style="display: flex;">'.$words['errors'].'</h2>
			</div>
			<div class="swal2-content">
				<div id="swal2-content" style="display: block;">'.$value.'</div>
			</div>
			<div class="swal2-actions">
				<button type="button" class="swal2-confirm btn btn-secondary m-btn m-btn--wide swal2-styled err">OK</button>
			</div>
		</div>
	</div>';
	return($value);
}

function table_thead($rows = [], $user_permissions = [], $theadClass = ''){

	$res = '<thead'; if($theadClass != '') $res .= ' class="'.$theadClass.'"'; $res .= '>
		<tr>';
		foreach ($rows as $k => $v) {

			if(array_key_exists('actions', $v) || array_key_exists('lastItemClass', $v)){
				continue;
			}

			if($k == 0){
				if($v[0] == 'false') continue;
				if ($v[0] == 'replace') {
					$res .= '<th';
					if (array_key_exists('class', $v))
						$res .= ' class="' . $v['class'] . '"';
					$res .= '>' . $v['value'] . '</th>';
					continue;
				}elseif($v[0] == 'edit'){
					$res .= '<th class="w-50px text-center">'.$v['value'].'</th>';
					continue;
				}else{
					$res .= '<th class="w-50px text-center">№</th>';
				}
			}

			$res .= '<th ';

			if (array_key_exists('class', $v)) {
				$res .= ' class="'.$v['class'];
				if(in_array('left', $v)){
					$res .= ' text-left';
				}
				if(in_array('center', $v)){
					$res .= ' text-center';
				}
				if(in_array('right', $v)){
					$res .= ' text-right';
				}
				$res .= '"';
			}else{
				if (in_array('left', $v)) {
					$res .= ' class="text-left"';
				}
				if (in_array('center', $v)) {
					$res .= ' class="text-center"';
				}
				if (in_array('right', $v)) {
					$res .= ' class="text-right"';
				}
			}

			$res .= ' >' . $v['value'] . '</th>';
		}
		if(end($rows)['actions']['status'] !== false){
			if($user_permissions['edit_access'] > 0 || $user_permissions['delete_access'] > 0){
				if(end($rows)['actions']['class'] != ''){
					$res .= '<th class="w-50px '.end($rows)['actions']['class'].'">'.lot_kir("Amallar").'</th>';
				}else{
					$res .= '<th class="w-50px">'.lot_kir("Amallar").'</th>';
				}
			}
		}
		$res .= '</tr>
	</thead>';

	return($res);
}

function actionsFunction($modalTarget, $data = [], $user_permissions, $type = 'dropdown'){

	if ($user_permissions['edit_access'] === '1' || $user_permissions['delete_access'] === '1') {
		if(!isset($data['edit_btn'])) $data['edit_btn'] = 'editBtn';
		if(!isset($data['delete_btn'])) $data['delete_btn'] = 'deleteBtn';
		if($type == 'dropdown'){
			$res = '<td class="text-end pe-5 text-nowrap">';
			if ($user_permissions['edit_access'] === '1') {
			$res .= '<button class="btn btn-icon btn-light-warning w-30px h-30px me-3 '.$data['edit_btn'].'"';
				if($data['modal'] === 'stacked') {$res .= ' data-bs-stacked-modal="#'.$modalTarget.'"';} else {$res .= ' data-bs-toggle="modal" data-bs-target="#'.$modalTarget.'"';}
				foreach ($data as $k => $v) { if($k === 'modal') continue; $res .= $k.'=\'' . $v . '\''; } $res .= '>
				<i class="ki-duotone ki-notepad-edit fs-3">
					<span class="path1"></span>
					<span class="path2"></span>
					<span class="path3"></span>
					<span class="path4"></span>
				</i>
			</button>';
			}
			if ($user_permissions['delete_access'] === '1') {
			$res .= '<button class="btn btn-icon btn-light-danger w-30px h-30px '. $data['delete_btn'] . '" data-bs-toggle="'; if($data['modal'] === 'stacked') {$res .= 'stacked';} else {$res .= 'modal';} $res .= '" data-id="'.$data['data-id'].'" data-table="'.$data['data-table'].'">
				<i class="ki-duotone ki-trash fs-3">
					<span class="path1"></span>
					<span class="path2"></span>
					<span class="path3"></span>
					<span class="path4"></span>
					<span class="path5"></span>
				</i>
			</button>';
			}
			$res .= '</td>';
		}
		return $res;
	}
}

function count_week_days($month, $params = []){
	// $month, $weekdays, $until = 'today'

	// $month Year and Month. Date format
	// $weekdays 0 (for Sunday) through 6 (for Saturday). INT or array
	// $until: today = today date, last_day = Last day of month, int value = int value date of month. INT or String

	$res = 0;
	if($params['from']){
		if($params['from'] === 'today'){
			$first_day = intval(date('d', strtotime(date("Y-m-d"))));
		}elseif($params['from'] === 'first_day'){
			$first_day = 1;
		}else{
			$first_day = intval($params['from']);
		}
	}else{
		$first_day = 1;
	}

	if($params['until']){
		if($params['until'] === 'today'){
			$last_day = intval(date('d', strtotime(date("Y-m-d"))));
		}elseif($params['until'] === 'last_day'){
			$last_day = intval(date('t', strtotime(date($month))));
		}else{
			$last_day = intval($params['until']);
		}
	}else{
		$last_day = intval(date('t', strtotime(date($month))));
	}

	if(is_array($params['days'])){
		foreach ($params['days'] as $v) {
			for ($i = $first_day; $i <= $last_day; $i++) {

				if(strlen($i) == 1) $i = '0'.$i;

				if(date("w", strtotime(date($month.'-'.$i))) == $v) $res++;
			}
		}
	}else{

		for ($i = $first_day; $i <= $last_day; $i++) {

			if(strlen($i) == 1) $i = '0'.$i;

			if(date("w", strtotime(date($month.'-'.$i))) == $params['days']) $res++;
		}
	}


	return $res;
}

function months_count($start, $end){
	return ((date('Y', strtotime($end)) - date('Y', strtotime($start))) * 12) + (date('m', strtotime($end)) - date('m', strtotime($start)));
}

include 'classes/query.php';
include 'classes/functions.php';