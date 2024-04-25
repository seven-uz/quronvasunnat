<?php
session_start();

include '../../unique/config.php';
include '../connection.php';
include '../functions.php';

$products_with_tannarx = $Query->getN("products", [
	'fields' => 'id, title, compound, season, reaper_1_price, reaper_2_price, reaper_helper_price, tailor_price, iron_man_price, selling_price, additional_expenses, info, photo, active',
	'order' => ['title'],
]);

$raw_materials_q = $Query->getN("raw_materials rw", [
	'fields' => 'irw.id as import_id, rw.id, rw.title, rw.unit, irw.cost',
	'order' => ['rw.title'],
	'where' => [
		['column' => 'rw.active', 'value' => "'1'"],
	],
	'join' => [
		['table' => 'import_raw_m irw', 'on' => 'rw.id = irw.raw_m_id'],
	],
	'ignore' => ['where' => " AND irw.deleted <> '1'"],
]);

rsort($raw_materials_q);

$raw_material_ids = [];
$raw_materials = [];
foreach ($raw_materials_q as $val) {
	if(in_array($val['id'], $raw_material_ids)) continue;
	$raw_material_ids[] = $val['id'];
	$raw_materials[$val['id']] = $val;
}

foreach ($products_with_tannarx as $val) {
	$price_raw_materials = 0;
	$price_services = 0;
	$compound = json_decode($val['compound'], true);
	if($compound != null){
		foreach ($compound as $k => $row) {
			$price_raw_materials += intval($raw_materials[$k]['cost']) * $row;
		}
	}
	$price_services = ($val['reaper_1_price'] + $val['reaper_2_price'] + $val['reaper_helper_price']) + $val['tailor_price'] + $val['iron_man_price'] + $val['additional_expenses'];

	$products_arr[$val['id']] = $price_raw_materials+$price_services;
}
unset($products_with_tannarx, $raw_materials_q);


$roles_q = mysqli_query($db, "SELECT p.id as p_id, p.title as p_title, p.value as p_value,
rp.view_access,rp.add_access,rp.edit_access,rp.delete_access,rp.function_access,rp.history_access,.rp.recent_history_access
FROM ".PREFIX."role_permissions rp
LEFT JOIN ".PREFIX."roles r ON r.id = rp.role_id
LEFT JOIN ".PREFIX."permissions p ON p.id = rp.permission_id
WHERE rp.role_id = '$_SESSION[role]' AND p.value='orders'") or die("Error getting role list");

$user_order_permissions = [];

while($row = $roles_q->fetch_assoc()){
	$user_order_permissions = [
		'edit_access' => $row['edit_access'],
		'delete_access' => $row['delete_access'],
	];
}

$query = $Query->getN("orders o", [
	'fields' => 'o.id, o.client_id, u.fio, o.order_num, o.product_id, o.qty, o.price, o.status, o.comment, p.title, p.photo, o.created_at as sold_time',
	'order' => ['o.created_at desc'],
	'join' => [
		['table' => 'products p', 'on' => 'p.id = o.product_id'],
		['table' => 'clients c', 'on' => 'c.id = o.client_id'],
		['table' => 'users u', 'on' => 'u.id = c.user_id'],
	],
	'where' => [
		['column' => 'o.client_id', 'value' => "'{$_POST['client_id']}'"],
	],
]);


foreach ($query as $val) {

	if($user_order_permissions['edit_access'] === '1'){
		$edit = '<button class="btn btn-icon btn-light-warning w-30px h-30px me-3 editBtn" data-bs-toggle="modal" data-bs-target="#editOrderModal"
		data-id="'.$val['id'].'" data-client_id="'.$val['client_id'].'" data-tannarx_edit="'.$products_arr[$val['product_id']].'" data-product_id="'.$val['product_id'].'" data-qty="'.$val['qty'].'" data-price="'.$val['price'].'" data-comment="'.$val['comment'].'"
		data-table="orders" edit_btn="editBtn" delete_btn="deleteBtn">
			<i class="ki-duotone ki-notepad-edit fs-3">
				<span class="path1"></span>
				<span class="path2"></span>
				<span class="path3"></span>
				<span class="path4"></span>
			</i>
		</button>';
	}

	if($user_order_permissions['delete_access'] === '1'){
		$delete = '<button class="btn btn-icon btn-light-danger w-30px h-30px me-3 deleteBtn" data-bs-toggle="modal" data-id="'.$val['id'].'" data-table="orders">
			<i class="ki-duotone ki-trash fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
		</button>';
	}


	$data[] = [
		'num' => ++$num,
		'product' => '<div class="d-flex align-items-center">
			<a class="symbol symbol-50px d-md-block d-none me-5">
				<span class="symbol-label" style="background-image:url(/assets/media/products/'.$val['photo'].');"></span>
			</a>
			<a class="text-gray-800 fs-5 fw-bold">'. lot_kir($val['title']) .'</a>
		</div>',
		'sold_time' => dwt($val['sold_time']),
		'qty' => nf($val['qty']),
		'cost' => nf($val['price']),
		'comment' => $val['comment'],
		'actions' => $edit.$delete,
	];
}

$json['data'] = $data;

echo json_encode($json);