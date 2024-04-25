<?php
if ($_COOKIE['lang'] === 'uzl'){
	$nweek = array(1=>"Dushanba",2=>"Seshanba",3=>"Chorshanba",4=>"Payshanba",5=>"Juma",6=>"Shanba",7=>"Yakshanba");
} elseif($_COOKIE['lang'] === 'uzk'){
	$nweek = array(1=>"Душанба",2=>"Сешанба",3=>"Чоршанба",4=>"Пайшанба",5=>"Жума",6=>"Шанба",7=>"Якшанба");
} elseif($_COOKIE['lang'] === 'en'){
	$nweek = array(1=>"Monday",2=>"Tuesday",3=>"Wednesday",4=>"Thursday",5=>"Friday",6=>"Saturday",7=>"Sunday");
} elseif($_COOKIE['lang'] === 'ru'){
	$nweek = array(1=>"Понедельник",2=>"Вторник",3=>"Среда",4=>"Четверг",5=>"Пятница",6=>"Суббота",7=>"Воскресенье");
}