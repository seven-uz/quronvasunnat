<?php
session_start();

include '../../unique/config.php';
include '../connection.php';
include '../functions.php';

$first_day_this_month = $_POST['date'].'-01';

$users = $Query->getN("users u", [
	'fields' => 'u.id, u.fio, u.photo, r.title as role_title, u.group_id, r.value as role_value',
	'join' => [
		['table' => 'roles r', 'on' => 'r.id = u.role'],
	],
	'order' => ['fio'],
	'group' => 'u.id',
	'idAsKey' => true,
	'where' => [
		['column' => 'r.is_worker', 'value' => "'1'"],
	],
]);

$users_groups = $Query->getN("users_groups", [
	'fields' => 'user_group_id, title, type',
	'order' => ['user_group_id'],
	'where' => [
		['column' => 'active', 'value' => "'1'"],
	],
]);

$production = $Query->getN("production p", [
	// 'fields' => 'p.id, p.user_id, p.group_id, p.product_id, SUBSTR(p.time, 1, 10) as time, SUM(p.qty),
	'fields' => 'p.id, p.user_id, p.group_id, p.product_id, p.time, SUBSTR(p.time, 9, 2) as date, p.qty,
	pr.tailor_price, pr.reaper_1_price, pr.reaper_2_price, pr.reaper_helper_price, pr.iron_man_price',
	'join' => [
		['table' => 'products pr', 'on' => 'pr.id = p.product_id'],
	],
	'where' => [
		['column' => 'YEAR(p.time)', 'value' => "'".date("Y", strtotime($first_day_this_month))."'"],
		['column' => 'MONTH(p.time)', 'value' => "'".date("m", strtotime($first_day_this_month))."'"],
	],
	// 'group' => 'time, p.id, p.qty',
]);

foreach ($production as $key => $value) {

	if($value['group_id'] != '5'){
		$production_arr['master'][$value['date']][] = ($value['qty'] * $value['tailor_price']);
	}

	foreach ($users_groups as $k => $v) {

		if($value['group_id'] == $v['user_group_id']){
			$production_arr['streams'][$value['group_id']][$value['date']] += $value['qty'] * $value['tailor_price'];
		}

	}


	// $production_arr['streams'][$value['group_id']][$value['date']] += $value['qty'];
	// if($value['group_id'] == ''){
	// 	$production_arr['users'][$value['user_id']][$value['date']] += $value['qty'];
	// }
}


$attendance = $Query->getN("attendance a", [
	'fields' => 'a.id, a.date, a.worker_id, a.not_come, a.comment, u.fio',
	'join' => [
		['table' => 'users u', 'on' => 'u.id = a.worker_id'],
	],
	'where' => [
		['column' => 'YEAR(a.date)', 'value' => "'".date("Y", strtotime('-1 month', $first_day_this_month))."'"],
		['column' => 'MONTH(a.date)', 'value' => "'".date("m", strtotime('-1 month', $first_day_this_month))."'"],
	],
]);

$paid_q = $Query->getN("expenses", [
	'fields' => 'to_id, to_group_id, SUM(cost) as cost',
	'where' => [
		['column' => 'to_id', 'operand' => '<>', 'value' => "'' OR to_group_id <> ''"],
	],
	'group' => 'to_id, to_group_id',
]);

$paid = [];
foreach ($paid_q as $key => $val) {
	if($val['to_group_id'] === null || $val['to_group_id'] === ''){
		$paid['to_id'][$val['to_id']] = $val['cost'];
	}elseif($val['to_id'] === null || $val['to_id'] === ''){
		$paid['to_group_id'][$val['to_group_id']] = $val['cost'];
	}
}

$works = $Query->getN("works w", [
	'fields' => 'w.id, w.user_id, w.worker_type, SUBSTR(w.date, 9, 2) as date, w.product_id, w.work_qty, w.worked_time,
	p.iron_man_price',
	'join' => [
		['table' => 'products p', 'on' => 'p.id = w.product_id'],
	],
	'where' => [
		['column' => 'YEAR(w.date)', 'value' => "'".date("Y", strtotime($first_day_this_month))."'"],
		['column' => 'MONTH(w.date)', 'value' => "'".date("m", strtotime($first_day_this_month))."'"],
		['column' => 'worker_type', 'value' => "'ironman'"],
	],
]);
$works_packer = $Query->getN("works w", [
	'fields' => 'w.id, w.user_id, w.worker_type, SUBSTR(w.date, 9, 2) as date, w.product_id, w.work_qty, w.worked_time',
	'where' => [
		['column' => 'YEAR(w.date)', 'value' => "'".date("Y", strtotime($first_day_this_month))."'"],
		['column' => 'MONTH(w.date)', 'value' => "'".date("m", strtotime($first_day_this_month))."'"],
		['column' => 'worker_type', 'value' => "'packer'"],
	],
]);

foreach ($works as $key => $val) {
	$works_arr['ironman'][$val['user_id']]['date'][$val['date']] += ($val['work_qty'] * $val['iron_man_price']);
}

foreach ($works_packer as $key => $val) {
	$works_arr['packer'][$val['user_id']]['date'][$val['date']] += $val['worked_time'];
}

foreach ($users_groups as $k0 => $v0) {
	foreach ($users as $key => $val) {

		if($v0['user_group_id'] == $val['group_id']) {
			// foreach ($paid as $expense) {
			// 	$salary[$v0['user_group_id']][$expense['to_id']]['paid'][$expense['month']] += $expense['cost'];
			// 	$salary[$v0['user_group_id']][$expense['to_id']]['paid_amount'] += $expense['cost'];
			// }

			foreach ($production as $k => $v) {

				if($val['id'] != $v['user_id']) continue;

				if($val['role_value'] == 'tailor') $salary_price = $v['tailor_price'];
				elseif($val['role_value'] == 'reaper') $salary_price = $v['reaper_price'];
				elseif($val['role_value'] == 'iron_man') $salary_price = $v['iron_man_price'];

				$salary[$v0['user_group_id']][$v['user_id']]['earned'][$v['month']] += $v['qty'] * $salary_price;
				$salary[$v0['user_group_id']][$v['user_id']]['earned_amount'] += ($v['qty'] * $salary_price);
			}
		}

		if($val['group_id'] == ''){
			// foreach ($paid as $expense) {
			// 	$salary['not_grouped'][$expense['to_id']]['paid'][$expense['month']] += $expense['cost'];
			// 	$salary['not_grouped'][$expense['to_id']]['paid_amount'] += $expense['cost'];
			// }

			foreach ($production as $k => $v) {

				if($val['id'] != $v['user_id']) continue;

				if($val['role_value'] == 'tailor') $salary_price = $v['tailor_price'];
				elseif($val['role_value'] == 'reaper') $salary_price = $v['reaper_price'];
				elseif($val['role_value'] == 'iron_man') $salary_price = $v['iron_man_price'];

				$salary['not_grouped'][$v['user_id']]['earned'][$v['month']] += $v['qty'] * $salary_price;
				$salary['not_grouped'][$v['user_id']]['earned_amount'] += ($v['qty'] * $salary_price);
			}
		}

	}
}

$workers = $attendance_arr = [];

if(!empty($attendance)) {
	$workers = array_unique(arrayColumn($attendance, 'worker_id', 'fio'));

	foreach ($attendance as $key => $value) {
		$attendance_arr[$value['date']][] = $value;
	}
	unset($attendance);
}

foreach ($users_groups as $key => $value) {
	$data[$key] = [
		'title' =>$value['title'],
		'left' => nf(($paid_amount - $earned_amount)),
	];
	for ($i=1; $i <= intval(date("t", strtotime($first_day_this_month))); $i++) {
		if(strlen($i) == 1) $i = '0'.$i;
		$data[$key]['day_'.$i] = nf($production_arr['streams'][$value['user_group_id']][$i]);
	}
}
foreach ($users as $key => $value) {
	if($value['group_id'] != '') continue;
	if($value['role_title'] === 'Master'){
		$data['u_'.$key] = [
			'title' => $value['fio'],
			'left' => nf(($paid_amount - $earned_amount)),
		];
		for ($i=1; $i <= intval(date("t", strtotime($first_day_this_month))); $i++) {
			if(strlen($i) == 1) $i = '0'.$i;
			$data['u_'.$key]['day_'.$i] = ($production_arr['master'][$i]) ? nf(max($production_arr['master'][$i])) : '-';
		}
	}elseif($value['role_value'] === 'ironman'){
		$data['u_'.$key]['title'] = $value['fio'];
		for ($i=1; $i <= intval(date("t", strtotime($first_day_this_month))); $i++) {
			if(strlen($i) == 1) $i = '0'.$i;
			$ironman_salary = ($works_arr['ironman'][$value['id']]['date'][$i]) ? ($works_arr['ironman'][$value['id']]['date'][$i]) : 0;
			$ironman_salary_amount += $ironman_salary;
			$data['u_'.$key]['day_'.$i] = $ironman_salary;
		}
		$data['u_'.$key]['left'] = $ironman_salary_amount;
	}elseif($value['role_value'] === 'packer'){
		$data['u_'.$key]['title'] = $value['fio'];
		for ($i=1; $i <= intval(date("t", strtotime($first_day_this_month))); $i++) {
			if(strlen($i) == 1) $i = '0'.$i;
			$packer_salary = ($works_arr['packer'][$value['id']]['date'][$i]) ? ($works_arr['packer'][$value['id']]['date'][$i] * 12000) : 0;
			$packer_salary_amount += $packer_salary;
			$data['u_'.$key]['day_'.$i] = $ironman_salary;
		}
		$packer_must_pay = ($packer_salary_amount - $paid['to_id'][$value['id']]);
		$data['u_'.$key]['left'] = $packer_must_pay;
	}else{
		$data['u_'.$key]['title'] = $value['fio'];
		for ($i=1; $i <= intval(date("t", strtotime($first_day_this_month))); $i++) {
			if(strlen($i) == 1) $i = '0'.$i;
			$tailor_salary = $production_arr['users'][$value['id']][$i];
			$tailor_salary_amount += $tailor_salary;
			$data['u_'.$key]['day_'.$i] = nf($tailor_salary);
		}
		$data['u_'.$key]['left'] = ($paid_amount - $earned_amount);
	}
}

$json['data'] = $data;

echo json_encode($json);