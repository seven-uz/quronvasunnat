<?php

include '../blocks/brain.php';
include '../functions.php';

$calendar = mysqli_query($db, "SELECT mt.*, CONCAT(u.surname, ' ', u.name) as fio FROM ".PREFIX."calendar mt LEFT JOIN ".PREFIX."users u ON u.id = mt.user_id order by mt.created_at DESC, mt.id DESC");
$exam = mysqli_query($db, "SELECT mt.*, CONCAT(u.surname, ' ', u.name) as fio, CONCAT(p.surname, ' ', p.name) as pupil FROM ".PREFIX."exam mt LEFT JOIN ".PREFIX."users u ON u.id = mt.created_user LEFT JOIN ".PREFIX."pupils px ON px.id = mt.pupil_id LEFT JOIN ".PREFIX."users p ON p.id = px.user_id order by mt.created_at DESC, mt.id DESC");
$expenses = mysqli_query($db, "SELECT mt.*, CONCAT(u.surname, ' ', u.name) as fio, e.title FROM ".PREFIX."expenses mt LEFT JOIN ".PREFIX."expenses_cats e ON e.id = mt.cat_id LEFT JOIN ".PREFIX."users u ON u.id = mt.created_user order by mt.created_at DESC, mt.id DESC");
$groups = mysqli_query($db, "SELECT mt.*, CONCAT(u.surname, ' ', u.name) as fio FROM ".PREFIX."groups mt LEFT JOIN ".PREFIX."users u ON u.id = mt.created_user order by mt.created_at DESC, mt.id DESC");
$groups_folders = mysqli_query($db, "SELECT mt.*, CONCAT(u.surname, ' ', u.name) as fio FROM ".PREFIX."groups_folders mt LEFT JOIN ".PREFIX."users u ON u.id = mt.created_user order by mt.created_at DESC, mt.id DESC");
$menu = mysqli_query($db, "SELECT mt.*, CONCAT(u.surname, ' ', u.name) as fio FROM ".PREFIX."menu mt LEFT JOIN ".PREFIX."users u ON u.id = mt.created_user order by mt.created_at DESC, mt.id DESC");
$messages = mysqli_query($db, "SELECT mt.*, CONCAT(u.surname, ' ', u.name) as fio, CONCAT(fu.surname, ' ', fu.name) as from_u, CONCAT(tu.surname, ' ', tu.name) as to_u FROM ".PREFIX."messages mt LEFT JOIN ".PREFIX."users u ON u.id = mt.created_user LEFT JOIN ".PREFIX."users fu ON fu.id = mt.from_id LEFT JOIN ".PREFIX."users tu ON tu.id = mt.to_id order by mt.created_at DESC, mt.id DESC");
$payments = mysqli_query($db, "SELECT mt.*, CONCAT(u.surname, ' ', u.name) as fio FROM ".PREFIX."payments mt LEFT JOIN ".PREFIX."users u ON u.id = mt.created_user order by mt.created_at DESC, mt.id DESC");
$posts = mysqli_query($db, "SELECT mt.*, CONCAT(u.surname, ' ', u.name) as fio FROM ".PREFIX."posts mt LEFT JOIN ".PREFIX."users u ON u.id = mt.created_user order by mt.created_at DESC, mt.id DESC");
$subjects = mysqli_query($db, "SELECT mt.*, CONCAT(u.surname, ' ', u.name) as fio FROM ".PREFIX."subjects mt LEFT JOIN ".PREFIX."users u ON u.id = mt.created_user order by mt.created_at DESC, mt.id DESC");
$rooms = mysqli_query($db, "SELECT mt.*, CONCAT(u.surname, ' ', u.name) as fio FROM ".PREFIX."rooms mt LEFT JOIN ".PREFIX."users u ON u.id = mt.created_user order by mt.created_at DESC, mt.id DESC");
$pupils = mysqli_query($db, "SELECT mt.*, CONCAT(u.surname, ' ', u.name) as fio, CONCAT(u2.surname, ' ', u2.name) as pupil FROM ".PREFIX."pupils mt LEFT JOIN ".PREFIX."users u ON u.id = mt.created_user LEFT JOIN ".PREFIX."users u2 ON u2.id = mt.user_id order by mt.created_at DESC, mt.id DESC");
$pupils_discounts = mysqli_query($db, "SELECT mt.*, CONCAT(u.surname, ' ', u.name) as fio, CONCAT(u2.surname, ' ', u2.name) as pupil FROM ".PREFIX."pupils_discounts mt LEFT JOIN ".PREFIX."pupils p ON p.id = mt.pupil_id LEFT JOIN ".PREFIX."users u ON u.id = mt.created_user LEFT JOIN ".PREFIX."users u2 ON u2.id = p.user_id order by mt.created_at DESC, mt.id DESC");
$teachers = mysqli_query($db, "SELECT mt.*, CONCAT(u.surname, ' ', u.name) as fio, CONCAT(u2.surname, ' ', u2.name) as teacher FROM ".PREFIX."teachers mt LEFT JOIN ".PREFIX."users u ON u.id = mt.created_user LEFT JOIN ".PREFIX."users u2 ON u2.id = mt.user_id order by mt.created_at DESC, mt.id DESC");
$users = mysqli_query($db, "SELECT * FROM ".PREFIX."users order by created_at DESC, id DESC");
$ipqulflash = mysqli_query($db, "SELECT * FROM ".PREFIX."ipqulflash order by date DESC, id DESC");
mysqli_close($db);

$data = [];

function main($data = []){
	$res = [
		"sort" => $data[2].$data[0],
		"id" => $data[0],
		"time" => dwt($data[2], true),
		"user" => $data[1],
		"item" => $data[3],
		"action" => $data[4],
		"value" => $data[5],
	];
	return $res;
}

while($row = $ipqulflash->fetch_assoc()){
	$data[] = main([$row['id'], $words['system'], $row['date'], $words['login'], $words['attemp_to_enter'], ($row['response_code'] == 200) ? $words['login'] : $words['wrong_info']]);
}
while($row = $calendar->fetch_assoc()){
	$data[] = main([$row['id'], ($row['created_user'] == 0 && !is_null($row['created_user'])) ? $words['system'] : $row['fio'], $row['created_at'], $words['calendar'], $words['added'], '-']);
	if($row['updated_at'] != ''){$data[] = main([$row['id'], ($row['updated_user'] == 0 && !is_null($row['updated_user'])) ? $words['system'] : $row['fio'], $row['updated_at'], $words['calendar'], $words['edited'], '-']);}
	if($row['deleted_at'] != ''){$data[] = main([$row['id'], ($row['deleted_user'] == 0 && !is_null($row['deleted_user'])) ? $words['system'] : $row['fio'], $row['deleted_at'], $words['calendar'], $words['deleted'], '-']);}
	if($row['restored_at'] != ''){$data[] = main([$row['id'], ($row['restored_user'] == 0 && !is_null($row['restored_user'])) ? $words['system'] : $row['fio'], $row['restored_at'], $words['calendar'], $words['restored'], '-']);}
}
while($row = $exam->fetch_assoc()){
	$data[] = main([$row['id'], ($row['created_user'] == 0 && !is_null($row['created_user'])) ? $words['system'] : $row['fio'], $row['created_at'], $words['exam'], $words['added'], $row['pupil'].': '.$row['point']]);
	if($row['updated_at'] != ''){$data[] = main([$row['id'], ($row['updated_user'] == 0 && !is_null($row['updated_user'])) ? $words['system'] : $row['fio'], $row['updated_at'], $words['exam'], $words['edited'], $row['pupil'].': '.$row['point']]);}
	if($row['deleted_at'] != ''){$data[] = main([$row['id'], ($row['deleted_user'] == 0 && !is_null($row['deleted_user'])) ? $words['system'] : $row['fio'], $row['deleted_at'], $words['exam'], $words['deleted'], $row['pupil'].': '.$row['point']]);}
	if($row['restored_at'] != ''){$data[] = main([$row['id'], ($row['restored_user'] == 0 && !is_null($row['restored_user'])) ? $words['system'] : $row['fio'], $row['restored_at'], $words['exam'], $words['restored'], $row['pupil'].': '.$row['point']]);}
}
while($row = $expenses->fetch_assoc()){
	$data[] = main([$row['id'], ($row['created_user'] == 0 && !is_null($row['created_user'])) ? $words['system'] : $row['fio'], $row['created_at'], $words['expenses'], $words['added'], $row['title']]);
	if($row['updated_at'] != ''){$data[] = main([$row['id'], ($row['updated_user'] == 0 && !is_null($row['updated_user'])) ? $words['system'] : $row['fio'], $row['updated_at'], $words['expenses'], $words['edited'], $row['title']]);}
	if($row['deleted_at'] != ''){$data[] = main([$row['id'], ($row['deleted_user'] == 0 && !is_null($row['deleted_user'])) ? $words['system'] : $row['fio'], $row['deleted_at'], $words['expenses'], $words['deleted'], $row['title']]);}
	if($row['restored_at'] != ''){$data[] = main([$row['id'], ($row['restored_user'] == 0 && !is_null($row['restored_user'])) ? $words['system'] : $row['fio'], $row['restored_at'], $words['expenses'], $words['restored'], $row['title']]);}
}
while($row = $posts->fetch_assoc()){
	$data[] = main([$row['id'], ($row['created_user'] == 0 && !is_null($row['created_user'])) ? $words['system'] : $row['fio'], $row['created_at'], $words['posts'], $words['added'], $row['title']]);
	if($row['updated_at'] != ''){$data[] = main([$row['id'], ($row['updated_user'] == 0 && !is_null($row['updated_user'])) ? $words['system'] : $row['fio'], $row['updated_at'], $words['posts'], $words['edited'], $row['title']]);}
	if($row['deleted_at'] != ''){$data[] = main([$row['id'], ($row['deleted_user'] == 0 && !is_null($row['deleted_user'])) ? $words['system'] : $row['fio'], $row['deleted_at'], $words['posts'], $words['deleted'], $row['title']]);}
	if($row['restored_at'] != ''){$data[] = main([$row['id'], ($row['restored_user'] == 0 && !is_null($row['restored_user'])) ? $words['system'] : $row['fio'], $row['restored_at'], $words['posts'], $words['restored'], $row['title']]);}
}
while($row = $menu->fetch_assoc()){
	$data[] = main([$row['id'], ($row['created_user'] == 0 && !is_null($row['created_user'])) ? $words['system'] : $row['fio'], $row['created_at'], $words['menu'], $words['added'], $row['title'.$_COOKIE['lang']]]);
	if($row['updated_at'] != ''){$data[] = main([$row['id'], ($row['updated_user'] == 0 && !is_null($row['updated_user'])) ? $words['system'] : $row['fio'], $row['updated_at'], $words['menu'], $words['edited'], $row['title'.$_COOKIE['lang']]]);}
	if($row['deleted_at'] != ''){$data[] = main([$row['id'], ($row['deleted_user'] == 0 && !is_null($row['deleted_user'])) ? $words['system'] : $row['fio'], $row['deleted_at'], $words['menu'], $words['deleted'], $row['title'.$_COOKIE['lang']]]);}
	if($row['restored_at'] != ''){$data[] = main([$row['id'], ($row['restored_user'] == 0 && !is_null($row['restored_user'])) ? $words['system'] : $row['fio'], $row['restored_at'], $words['menu'], $words['restored'], $row['title'.$_COOKIE['lang']]]);}
}
while($row = $messages->fetch_assoc()){
	$data[] = main([$row['id'], ($row['created_user'] == 0 && !is_null($row['created_user'])) ? $words['system'] : $row['fio'], $row['created_at'], $words['messages'], $words['added'], $row['from_u'].'->'.$row['to_u']]);
	if($row['updated_at'] != ''){$data[] = main([$row['id'], ($row['updated_user'] == 0 && !is_null($row['updated_user'])) ? $words['system'] : $row['fio'], $row['updated_at'], $words['messages'], $words['edited'], $row['from_u'].' -> '.$row['to_u']]);}
	if($row['deleted_at'] != ''){$data[] = main([$row['id'], ($row['deleted_user'] == 0 && !is_null($row['deleted_user'])) ? $words['system'] : $row['fio'], $row['deleted_at'], $words['messages'], $words['deleted'], $row['from_u'].' -> '.$row['to_u']]);}
	if($row['restored_at'] != ''){$data[] = main([$row['id'], ($row['restored_user'] == 0 && !is_null($row['restored_user'])) ? $words['system'] : $row['fio'], $row['restored_at'], $words['messages'], $words['restored'], $row['from_u'].' -> '.$row['to_u']]);}
}
while($row = $payments->fetch_assoc()){
	$data[] = main([$row['id'], ($row['created_user'] == 0 && !is_null($row['created_user'])) ? $words['system'] : $row['fio'], $row['created_at'], $words['payment_'], $words['added'], nf($row['summa'])]);
	if($row['updated_at'] != ''){$data[] = main([$row['id'], ($row['updated_user'] == 0 && !is_null($row['updated_user'])) ? $words['system'] : $row['fio'], $row['updated_at'], $words['payment_'], $words['edited'], nf($row['summa'])]);}
	if($row['deleted_at'] != ''){$data[] = main([$row['id'], ($row['deleted_user'] == 0 && !is_null($row['deleted_user'])) ? $words['system'] : $row['fio'], $row['deleted_at'], $words['payment_'], $words['deleted'], nf($row['summa'])]);}
	if($row['restored_at'] != ''){$data[] = main([$row['id'], ($row['restored_user'] == 0 && !is_null($row['restored_user'])) ? $words['system'] : $row['fio'], $row['restored_at'], $words['payment_'], $words['restored'], nf($row['summa'])]);}
}
while($row = $rooms->fetch_assoc()){
	$data[] = main([$row['id'], ($row['created_user'] == 0 && !is_null($row['created_user'])) ? $words['system'] : $row['fio'], $row['created_at'], $words['room'], $words['added'], $row['title']]);
	if($row['updated_at'] != ''){$data[] = main([$row['id'], ($row['updated_user'] == 0 && !is_null($row['updated_user'])) ? $words['system'] : $row['fio'], $row['updated_at'], $words['room'], $words['edited'], $row['title']]);}
	if($row['deleted_at'] != ''){$data[] = main([$row['id'], ($row['deleted_user'] == 0 && !is_null($row['deleted_user'])) ? $words['system'] : $row['fio'], $row['deleted_at'], $words['room'], $words['deleted'], $row['title']]);}
	if($row['restored_at'] != ''){$data[] = main([$row['id'], ($row['restored_user'] == 0 && !is_null($row['restored_user'])) ? $words['system'] : $row['fio'], $row['restored_at'], $words['room'], $words['restored'], $row['title']]);}
}
while($row = $subjects->fetch_assoc()){
	$data[] = main([$row['id'], ($row['created_user'] == 0 && !is_null($row['created_user'])) ? $words['system'] : $row['fio'], $row['created_at'], $words['subject'], $words['added'], $row['title']]);
	if($row['updated_at'] != ''){$data[] = main([$row['id'], ($row['updated_user'] == 0 && !is_null($row['updated_user'])) ? $words['system'] : $row['fio'], $row['updated_at'], $words['subject'], $words['edited'], $row['title']]);}
	if($row['deleted_at'] != ''){$data[] = main([$row['id'], ($row['deleted_user'] == 0 && !is_null($row['deleted_user'])) ? $words['system'] : $row['fio'], $row['deleted_at'], $words['subject'], $words['deleted'], $row['title']]);}
	if($row['restored_at'] != ''){$data[] = main([$row['id'], ($row['restored_user'] == 0 && !is_null($row['restored_user'])) ? $words['system'] : $row['fio'], $row['restored_at'], $words['subject'], $words['restored'], $row['title']]);}
}
while($row = $groups->fetch_assoc()){
	$data[] = main([$row['id'], ($row['created_user'] == 0 && !is_null($row['created_user'])) ? $words['system'] : $row['fio'], $row['created_at'], $words['group'], $words['added'], $row['title']]);
	if($row['updated_at'] != ''){$data[] = main([$row['id'], ($row['updated_user'] == 0 && !is_null($row['updated_user'])) ? $words['system'] : $row['fio'], $row['updated_at'], $words['group'], $words['edited'], $row['title']]);}
	if($row['deleted_at'] != ''){$data[] = main([$row['id'], ($row['deleted_user'] == 0 && !is_null($row['deleted_user'])) ? $words['system'] : $row['fio'], $row['deleted_at'], $words['group'], $words['deleted'], $row['title']]);}
	if($row['restored_at'] != ''){$data[] = main([$row['id'], ($row['restored_user'] == 0 && !is_null($row['restored_user'])) ? $words['system'] : $row['fio'], $row['restored_at'], $words['group'], $words['restored'], $row['title']]);}
}
while($row = $groups_folders->fetch_assoc()){
	$data[] = main([$row['id'], ($row['created_user'] == 0 && !is_null($row['created_user'])) ? $words['system'] : $row['fio'], $row['created_at'], $words['folders'], $words['added'], $row['title']]);
	if($row['updated_at'] != ''){$data[] = main([$row['id'], ($row['updated_user'] == 0 && !is_null($row['updated_user'])) ? $words['system'] : $row['fio'], $row['updated_at'], $words['folders'], $words['edited'], $row['title']]);}
	if($row['deleted_at'] != ''){$data[] = main([$row['id'], ($row['deleted_user'] == 0 && !is_null($row['deleted_user'])) ? $words['system'] : $row['fio'], $row['deleted_at'], $words['folders'], $words['deleted'], $row['title']]);}
	if($row['restored_at'] != ''){$data[] = main([$row['id'], ($row['restored_user'] == 0 && !is_null($row['restored_user'])) ? $words['system'] : $row['fio'], $row['restored_at'], $words['folders'], $words['restored'], $row['title']]);}
}
while($row = $teachers->fetch_assoc()){
	$data[] = main([$row['id'], ($row['created_user'] == 0 && !is_null($row['created_user'])) ? $words['system'] : $row['fio'], $row['created_at'], $words['teacher'], $words['added'], $row['teacher']]);
	if($row['updated_at'] != ''){$data[] = main([$row['id'], ($row['updated_user'] == 0 && !is_null($row['updated_user'])) ? $words['system'] : $row['fio'], $row['updated_at'], $words['teacher'], $words['edited'], $row['teacher']]);}
	if($row['deleted_at'] != ''){$data[] = main([$row['id'], ($row['deleted_user'] == 0 && !is_null($row['deleted_user'])) ? $words['system'] : $row['fio'], $row['deleted_at'], $words['teacher'], $words['deleted'], $row['teacher']]);}
	if($row['restored_at'] != ''){$data[] = main([$row['id'], ($row['restored_user'] == 0 && !is_null($row['restored_user'])) ? $words['system'] : $row['fio'], $row['restored_at'], $words['teacher'], $words['restored'], $row['teacher']]);}
}
while($row = $pupils->fetch_assoc()){
	$data[] = main([$row['id'], ($row['created_user'] == 0 && !is_null($row['created_user'])) ? $words['system'] : $row['fio'], $row['created_at'], $words['pupil'], $words['added'], $row['pupil']]);
	if($row['updated_at'] != ''){$data[] = main([$row['id'], ($row['updated_user'] == 0 && !is_null($row['updated_user'])) ? $words['system'] : $row['fio'], $row['updated_at'], $words['pupil'], $words['edited'], $row['pupil']]);}
	if($row['deleted_at'] != ''){$data[] = main([$row['id'], ($row['deleted_user'] == 0 && !is_null($row['deleted_user'])) ? $words['system'] : $row['fio'], $row['deleted_at'], $words['pupil'], $words['deleted'], $row['pupil']]);}
	if($row['restored_at'] != ''){$data[] = main([$row['id'], ($row['restored_user'] == 0 && !is_null($row['restored_user'])) ? $words['system'] : $row['fio'], $row['restored_at'], $words['pupil'], $words['restored'], $row['pupil']]);}
}
while($row = $pupils_discounts->fetch_assoc()){
	$data[] = main([$row['id'], ($row['created_user'] == 0 && !is_null($row['created_user'])) ? $words['system'] : $row['fio'], $row['created_at'], $words['bonus'], $words['added'], $row['pupil']]);
	if($row['updated_at'] != ''){$data[] = main([$row['id'], ($row['updated_user'] == 0 && !is_null($row['updated_user'])) ? $words['system'] : $row['fio'], $row['updated_at'], $words['bonus'], $words['edited'], $row['pupil']]);}
	if($row['deleted_at'] != ''){$data[] = main([$row['id'], ($row['deleted_user'] == 0 && !is_null($row['deleted_user'])) ? $words['system'] : $row['fio'], $row['deleted_at'], $words['bonus'], $words['deleted'], $row['pupil']]);}
	if($row['restored_at'] != ''){$data[] = main([$row['id'], ($row['restored_user'] == 0 && !is_null($row['restored_user'])) ? $words['system'] : $row['fio'], $row['restored_at'], $words['bonus'], $words['restored'], $row['pupil']]);}
}
while($row = $users->fetch_assoc()){
	$data[] = main([$row['id'], ($row['created_user'] == 0 && !is_null($row['created_user'])) ? $words['system'] : $row['fio'], $row['created_at'], $words['user'], $words['added'], $row['surname'].' '.$row['name']]);
	if($row['updated_at'] != ''){$data[] = main([$row['id'], ($row['updated_user'] == 0 && !is_null($row['updated_user'])) ? $words['system'] : $row['fio'], $row['updated_at'], $words['user'], $words['edited'], $row['surname'].' '.$row['name']]);}
	if($row['deleted_at'] != ''){$data[] = main([$row['id'], ($row['deleted_user'] == 0 && !is_null($row['deleted_user'])) ? $words['system'] : $row['fio'], $row['deleted_at'], $words['user'], $words['deleted'], $row['surname'].' '.$row['name']]);}
	if($row['restored_at'] != ''){$data[] = main([$row['id'], ($row['restored_user'] == 0 && !is_null($row['restored_user'])) ? $words['system'] : $row['fio'], $row['restored_at'], $words['user'], $words['restored'], $row['surname'].' '.$row['name']]);}
}

$json['data'] = $data;
rsort($json['data']);

echo json_encode($json);