<?php
if ($_COOKIE['lang'] === 'uzl') {
	$monthname = array(1 => 'Yanvar', 2 => 'Fevral', 3 => 'Mart', 4 => 'Aprel', 5 => 'May', 6 => 'Iyun', 7 => 'Iyul', 8 => 'Avgust', 9 => 'Sentabr', 10 => 'Oktabr', 11 => 'Noyabr', 12 => 'Dekabr');
} elseif ($_COOKIE['lang'] === 'uzk') {
	$monthname = array(1 => 'Январ', 2 => 'Феврал', 3 => 'Март', 4 => 'Апрел', 5 => 'Май', 6 => 'Июн', 7 => 'Июл', 8 => 'Август', 9 => 'Сентябр', 10 => 'Октябр', 11 => 'Ноябр', 12 => 'Декабр');
} elseif ($_COOKIE['lang'] === 'en') {
	$monthname = array(1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'December');
} elseif ($_COOKIE['lang'] === 'ru') {
	$monthname = array(1 => 'Январь', 2 => 'Февраль', 3 => 'Март', 4 => 'Апрель', 5 => 'Май', 6 => 'Июнь', 7 => 'Июль', 8 => 'Август', 9 => 'Сентябрь', 10 => 'Октябрь', 11 => 'Ноябрь', 12 => 'Декабрь');
}