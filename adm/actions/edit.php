<?php

include '../unique/config.php';
include '../core/brain.php';

if (empty($_POST)) {
	header("Location: " . ADMIN_PAGE);
	exit;
}

include '../core/functions.php';

if(isset($_POST['table']) && !empty($_POST['table'])) {
	if(is_array($_POST['table'])){
		$main_table = removess(strtolower(htmlspecialchars(trim($_POST['table'][0]))));
		$second_table = removess(strtolower(htmlspecialchars(trim($_POST['table'][1]))));
		$third_table = removess(strtolower(htmlspecialchars(trim($_POST['table'][2]))));
	}else{
		$main_table = removess(strtolower(htmlspecialchars(trim($_POST['table']))));
	}
} else exit(lot_kir("Jadval tanlanmagan!"));

if($user_permissions[$main_table]['edit_access'] !== '1') exit(lot_kir("Ushbu amaliyotni bajarish uchun ruhsatingiz yo'q!"));

if(isset($_POST['workers'])) $main_table = 'users';
elseif($_POST['table'] === 'import') $main_table = 'import_raw_m';
elseif($_POST['table'] === 'workers') $main_table = 'users';

if($second_table === 'menu_categories') $main_table = 'menu_categories';
elseif($second_table === 'menu_parents') $main_table = 'menu_parents';
elseif($second_table === 'expenses_cats') $main_table = 'expenses_cats';
elseif($second_table === 'expenses_types') $main_table = 'expenses_types';
elseif($second_table === 'soldout_by_diller') $main_table = 'soldout_by_diller';

if(isset($_POST['id'])) $id = intval($_POST['id']); else unset($id);
if(isset($_POST['type_id'])) $type_id = intval($_POST['type_id']); else unset($type_id);
if(isset($_POST['cat_id'])) $cat_id = intval($_POST['cat_id']); else unset($cat_id);
if(isset($_POST['import_id'])) $import_id = intval($_POST['import_id']); else unset($import_id);
if(isset($_POST['parent_id'])) $parent_id = intval($_POST['parent_id']); else unset($parent_id);
if(isset($_POST['raw_m_id'])) $raw_m_id = intval($_POST['raw_m_id']); else unset($raw_m_id);
if(isset($_POST['supplier_id'])) $supplier_id = intval($_POST['supplier_id']); else unset($supplier_id);
if(isset($_POST['sort'])) $sort = intval($_POST['sort']); else unset($sort);
if(isset($_POST['order_num'])) $order_num = intval($_POST['order_num']); else unset($order_num);
if(isset($_POST['product_id'])) $product_id = intval($_POST['product_id']); else unset($product_id);
if(isset($_POST['client_id'])) $client_id = intval($_POST['client_id']); else unset($client_id);
if(isset($_POST['role_id'])) $role_id = intval($_POST['role_id']); else unset($role_id);
if(isset($_POST['permission_id'])) $permission_id = intval($_POST['permission_id']); else unset($permission_id);

if(!empty($_POST['unit'])) $unit = "'".$_POST['unit']."'"; else unset($unit);

if(!empty($_POST['title'])) $title = "'".textType2($_POST['title'])."'"; else unset($title);


if(isset($_POST['photo_remove']) && $_POST['photo_remove'] == '1'){

	$path = "../assets/media/$main_table/";
	$fileName = $Query->getById($main_table, $id)['photo'];

	if(search_file($path, $fileName) && $fileName != 'blank-image.svg'){
		unlink($path.$fileName);
 		$photo_final = ",`photo` = 'blank-image.svg'";
	}
}

if($_FILES['photo']['error'] === 0){

	$path = "../assets/media/$main_table/";

	$fileName = $Query->getById($main_table, $id)['photo'];
	if(search_file($path, $fileName) && $fileName != 'blank-image.svg'){
		unlink($path.$fileName);
	}

	$valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp');

	$img = $_FILES['photo']['name'];
 	$tmp = $_FILES['photo']['tmp_name'];

	$ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

 	$photo = date("YmdHis").'.'.$ext;
 	$photo_final = ",`photo` = '$photo'";

	if(in_array($ext, $valid_extensions))  {
		$path = $path.strtolower($photo);

		if(!move_uploaded_file($tmp,$path)) {
			echo lot_kir("Rasm uchun fayl turi mos kelmadi");
			exit;
		}
	}
}

if(isset($_POST['name']) && !empty($_POST['name'])) $name = $_POST['name']; else unset($name);
if(isset($_POST['name'])) {if(!empty($_POST['name'])) {$name = "'".htmlspecialchars(trim(textType2($_POST['name'])))."'";}} else {unset($name);}

// Nullabel variables
if(isset($_POST['comment'])) {if(!empty($_POST['comment'])) {$comment = "'".htmlspecialchars(trim(textType2($_POST['comment'])))."'";}else{$comment = "NULL";}} else {unset($comment);}
if(isset($_POST['info'])) {if(!empty($_POST['info'])) {$info = "'".htmlspecialchars(trim(textType2($_POST['info'])))."'";}else{$info = "NULL";}} else {unset($info);}
if(isset($_POST['to_id']) && !empty($_POST['to_id'])) {$to_id = "'".intval($_POST['to_id'])."'";}else{$to_id = "NULL";}
if(isset($_POST['phone'])) {if(!empty($_POST['phone'])) {$phone = "'".removess(remove_undescore($_POST['phone']))."'";} else {$phone = "NULL";} } else {unset($phone);}
if(isset($_POST['attached_group'])) {if(!empty($_POST['attached_group'])) {$attached_group = "'".removess(remove_undescore($_POST['attached_group']))."'";} else {$attached_group = "NULL";} } else {unset($attached_group);}
if(isset($_POST['color'])) {if(!empty($_POST['color'])) {$color = "'".removess(remove_undescore($_POST['color']))."'";} else {$color = "NULL";} } else {unset($color);}

// Nullabel variables with number value
if(isset($_POST['worker_type']) && !empty($_POST['worker_type']) && $_POST['worker_type'] != '0') {$worker_type = "'".$_POST['worker_type']."'";}else{$worker_type = "NULL";}
if(isset($_POST['group_id']) && !empty($_POST['group_id']) && $_POST['group_id'] != '0') {$group_id = "'".$_POST['group_id']."'";}else{$group_id = "NULL";}

if(isset($_POST['qty'])) {if(!empty($_POST['qty'])) {$qty = removess($_POST['qty']);}} else {unset($qty);}
if(isset($_POST['quantity'])) {if(!empty($_POST['quantity'])) {$quantity = removess($_POST['quantity']);}} else {unset($quantity);}
if(isset($_POST['cost'])) {if(!empty($_POST['cost'])) {$cost = removess($_POST['cost']);}} else {unset($cost);}
if(isset($_POST['amount'])) {if(!empty($_POST['amount'])) {$amount = removess($_POST['amount']);}} else {unset($amount);}
if(isset($_POST['price'])) {if(!empty($_POST['price'])) {$price = removess($_POST['price']);}} else {unset($price);}

if(isset($_POST['link'])) {if(!empty($_POST['link'])) {$link = "'".strtolower(removess($_POST['link'], 'link'))."'";}} else {unset($link);}
if(isset($_POST['value'])) {if(!empty($_POST['value'])) {$value = "'".strtolower(removess($_POST['value'], 'value'))."'";}} else {unset($value);}
if(isset($_POST['payment_time'])) {if(!empty($_POST['payment_time'])) {$payment_time = "'".dateDefault($_POST['payment_time'], 'time')."'";}} else {unset($payment_time);}
if(isset($_POST['time'])) {if(!empty($_POST['time'])) {$time = "'".dateDefault($_POST['time'], 'time')."'";}} else {unset($time);}
if(isset($_POST['arrival_time'])) {if(!empty($_POST['arrival_time'])) {$arrival_time = "'".dateDefault($_POST['arrival_time'], 'time')."'";}} else {unset($arrival_time);}
if(isset($_POST['date'])) {if(!empty($_POST['date'])) {$date = "'".dateDefault($_POST['date'], 'date')."'";}} else {unset($date);}

if(isset($_POST['active'])) $active = "'1'"; else $active = "'0'";
if(isset($_POST['other_workhouse'])) $other_workhouse = "'1'"; else $other_workhouse = "'0'";
if(isset($_POST['affect_cost_price'])) $affect_cost_price = "'1'"; else $affect_cost_price = "'0'";
if(isset($_POST['is_worker'])) $is_worker = "'1'"; else $is_worker = "'0'";

// Validations
if($phone != NULL && $phone != "NULL") {if(strlen(removess(remove_undescore($_POST['phone']))) != 9) exit(lot_kir("Telefon noto'g'ri to'ldirildi!"));}

// Zero configs
$second_query = $third_query = $add_query = false;

// SQL queries
if($main_table === 'roles'){

	$main_sql = $Functions->createData("`title` = $title, `value` = $value, `active` = $active, `is_worker` = $is_worker, `worker_type` = $worker_type", 'u');

	if(isset($_POST['permission']) && !empty($_POST['permission'])){
		$second_query = true;
	}

}elseif($main_table === 'clients'){
	$main_sql = $Functions->createData("`name` = $name, `phone` = $phone, `comment` = $comment, `active` = $active", 'u');
}elseif($main_table === 'expenses'){

	$user_group_id = $Query->getN("users", [
		'fields' => 'group_id',
		'where' => [
			['column' => 'id', 'value' => "'{$_POST['to_id']}'"],
		],
	])[0]['group_id'];

	if(!empty($user_group_id)) {
		$to_group_id = "'$user_group_id'";
	} else {
		$to_group_id = "NULL";
	}

	if(isset($_POST['qty']) && !empty($_POST['qty'])) $qty_custom = remove_spaces($_POST['qty']); else $qty_custom = "NULL";
	$main_sql = $Functions->createData("`to_id` = $to_id, `to_group_id` = $to_group_id, `type_id` = $type_id, `qty` = $qty_custom, `cost` = $cost, `time` = $time, `comment` = $comment", 'u');
}elseif($main_table === 'expenses_cats'){
	$main_sql = $Functions->createData("`title` = $title, `active` = $active", 'u');
}elseif($main_table === 'expenses_types'){
	$main_sql = $Functions->createData("`cat_id` = $cat_id, `affect_cost_price` = $affect_cost_price, `color` = $color, `title` = $title, `active` = $active", 'u');
}elseif($main_table === 'import_raw_m'){
	$main_sql = $Functions->createData("`raw_m_id` = $raw_m_id, `quantity` = $quantity, `cost` = $cost, `arrival_time` = $arrival_time, `comment` = $comment", 'u');
}elseif($main_table === 'investing'){
	$main_sql = $Functions->createData("`user_id` = $_SESSION[id], `date` = $date, `comment` = $comment, `amount` = $amount", 'u');
}elseif($main_table === 'menu'){
	$main_sql = $Functions->createData("`title` = $title, `cat_id` = $cat_id, `parent_id` = $parent_id, `link` = $link, `sort` = $sort, `active` = $active", 'u');
}elseif($main_table === 'menu_categories'){
	$main_sql = $Functions->createData("`title` = $title, `sort` = $sort, `active` = $active", 'u');
}elseif($main_table === 'menu_parents'){
	$main_sql = $Functions->createData("`title` = $title, `sort` = $sort, `active` = $active", 'u');
}elseif($main_table === 'orders'){
	if(isset($_POST['archive'])){
		$main_sql = $Functions->createData("`archive` = $archive", 'u');
	}else{
		$main_sql = $Functions->createData("`net_cost` = ".removess($_POST['tannarx_edit']).", `qty` = $qty, `price` = $price", 'u');
	}
}elseif($main_table === 'payments'){
	if(isset($_POST['archive'])){
		$main_sql = $Functions->createData("`archive` = $archive", 'u');
	}else{
		$main_sql = $Functions->createData("`client_id` = $client_id, `payment_time` = $time, `amount` = $amount, `comment` = $comment", 'u');
	}
}elseif($main_table === 'permissions'){

	if(isset($_POST['type']) && !empty($_POST['type'])) {$type = "'".json_encode($_POST['type'])."'";}else{$type = "NULL";}

	$main_sql = $Functions->createData("`title` = $title, `value` = $value, `type` = $type, `active` = $active", 'u');
}elseif($main_table === 'products'){

	if(isset($_POST['season'])) $season = "'".removess($_POST['season'])."'"; else $season = "NULL";

	foreach ($_POST['raw_material'] as $key => $val) {
		if($val['qty'] == '') continue;
		$compound[$val['id']] = remove_spaces($val['qty']);
	}

	if(!$compound) exit(lot_kir("Hom ashyodan birortasi ham kirtilmadi!"));
	$compound = "'".json_encode($compound)."'";

	if(isset($_POST['reaper_1_price']) && !empty($_POST['reaper_1_price'])) $reaper_1_price = "'".removess($_POST['reaper_1_price'])."'"; else exit(lot_kir("1-bichuvchi summasini kiriting"));
	if(isset($_POST['reaper_2_price']) && !empty($_POST['reaper_2_price'])) $reaper_2_price = "'".removess($_POST['reaper_2_price'])."'"; else exit(lot_kir("2-bichuvchi summasini kiriting"));
	if(isset($_POST['reaper_helper_price']) && !empty($_POST['reaper_helper_price'])) $reaper_helper_price = "'".removess($_POST['reaper_helper_price'])."'"; else exit(lot_kir("Yordamchi bichuvchi summasini kiriting"));
	if(isset($_POST['tailor_price']) && !empty($_POST['tailor_price'])) $tailor_price = "'".removess($_POST['tailor_price'])."'"; else exit(lot_kir("Tikuvchi summasini kiriting"));
	if(isset($_POST['iron_man_price']) && !empty($_POST['iron_man_price'])) $iron_man_price = "'".removess($_POST['iron_man_price'])."'"; else exit(lot_kir("Dazmolchi summasini kiriting"));
	if(isset($_POST['selling_price']) && !empty($_POST['selling_price'])) $selling_price = removess($_POST['selling_price']); else exit(lot_kir("Sotish narhini kiriting"));
	if(isset($_POST['additional_expenses']) && !empty($_POST['additional_expenses'])) $additional_expenses = removess($_POST['additional_expenses']); else exit(lot_kir("Qo'shimcha harajatlarni kiriting"));

	$main_sql = $Functions->createData("`title` = $title, `compound` = $compound, `additional_expenses` = $additional_expenses, `reaper_1_price` = $reaper_1_price, `reaper_2_price` = $reaper_2_price, `reaper_helper_price` = $reaper_helper_price, `tailor_price` = $tailor_price, `iron_man_price` = $iron_man_price, `selling_price` = $selling_price, `info` = $info, `season` = $season, `active` = $active $photo_final ", 'u');

}elseif($main_table === 'raw_materials'){
	$main_sql = $Functions->createData("`title` = $title, `unit` = $unit, `info` = $info, `active` = $active $photo_final", 'u');
}elseif($main_table === 'residual'){
	$main_sql = $Functions->createData("`product_id` = $product_id, `qty` = $qty, `comment` = $comment, `send_time` = $time", 'u');
}elseif($main_table === 'role_permissions'){
	$main_sql = $Functions->createData("`role_id` = $role_id, `permission_id` = $permission_id, `view_access` = $view_access, `add_access` = $add_access, `edit_access` = $edit_access, `delete_access` = $delete_access, `function_access` = $function_access", 'u');
}elseif($main_table === 'suppliers'){
	$main_sql = $Functions->createData("`name` = $name, `phone` = $phone, `info` = $info, `active` = $active", 'u');
}elseif($main_table === 'soldout_by_diller'){
	$main_sql = $Functions->createData("`qty` = $qty, `product_id` = $product_id", 'u');
}elseif($main_table === 'users_groups'){
	$id_column = 'user_group_id';
	$main_sql = $Functions->createData("`title` = $title, `comment` = $comment, `other_workhouse` = $other_workhouse, `active` = $active", 'u');
}elseif($main_table === 'clients' || $main_table === 'users'){

	if($phone != "NULL" && $_POST['email'] == null && $_POST['newpassword'] == null){
		$old_phone = $Query->getN("users", ['fields' => 'id','where' => [['column' => 'phone', 'value' => $phone],],])[0]['id'];
		if(isset($old_phone) && $old_phone != $id) exit(lot_kir("Bu telefon raqam avval ro'yhatdan o'tkazilgan."));
	}

	if(isset($_POST['login'])) {if(!empty($_POST['login'])) {$login = "'".$_POST['login']."'";}else{$login = "NULL";}} else {unset($login);}
	if(isset($_POST['password'])) {if(!empty($_POST['password'])) {$password = "'".strrev(md5($_POST['password']))."'";}else{$password = "NULL";}} else {unset($password);}

	if(isset($_POST['fio'])) {if(!empty($_POST['fio'])) {$fio = "'".htmlspecialchars(trim(textType($_POST['fio'])))."'";}} else {unset($fio);}

	if(($fio && empty($fio))) {
		echo lot_kir("So'ralgan ma'lumotlarni to'liq va to'g'ri kiriting");
		exit;
	}

	if($main_table === 'clients'){

		$role_id = $Query->getById('roles', 'customer', 'value')['id'];
		if(!$role_id) {
			echo lot_kir("So'ralgan ma'lumotlarni to'liq va to'g'ri kiriting");
			exit;
		}
		$main_sql = $Functions->createData("`password` = $password, `fio` = $fio, `email` = $email, `phone` = $phone, `active` = $active, `group_id` = $group_id, `role` = $role_id", 'u');

		$main_table = 'users';
		$second_table = 'clients';
		$second_query = true;
	}else{

		$user_password = $Query->getById('users', $id)['password'];

		if($_POST['email'] != null){

			if(isset($_POST['email'])) {if(!empty($_POST['email'])) {$email = "'".$_POST['email']."'";}else{$email = "NULL";}} else {unset($email);}
			if ($email && $email != "NULL" && !preg_match("/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i", $email)) {exit(lot_kir("Email formati noto'g'ri"));}
			if(strpos($_POST['email'], "'") !== false) exit(lot_kir("Ta'qiqlangan belgi ishlatildi."));

			if($user_password === strrev(md5($_POST['input_password']))){
				$main_sql = $Functions->createData("`email` = $email", 'u');
			}else{
				exit(lot_kir("Parol noto'g'ri"));
			}

		}elseif($_POST['newpassword'] != null){

			if(strlen($_POST['newpassword']) < 4) exit(lot_kir("Parol eng kamida 4 ta belgidan iborat bo'lishi kerak!"));
			if($_POST['newpassword'] !== $_POST['confirmpassword']) exit(lot_kir("Parolni qayta terishda hatolik!"));

			if($_SESSION['role_value'] === 'admin'){
				$main_sql = $Functions->createData("`password` = '".strrev(md5($_POST['newpassword']))."'", 'u');
			}else{
				if($user_password === strrev(md5($_POST['currentpassword']))){
					$main_sql = $Functions->createData("`password` = '".strrev(md5($_POST['newpassword']))."'", 'u');
					if($id == $_SESSION['id']) session_unset();
				}else{
					exit(lot_kir("Eski parol noto'g'ri"));
				}
			}

		}else{

			if(empty($role_id)) {
				echo lot_kir("So'ralgan ma'lumotlarni to'liq va to'g'ri kiriting");
				exit;
			}

			if(isset($_POST['group_id'])){
				$main_sql = $Functions->createData("`login` = $login, `fio` = $fio, `phone` = $phone, `active` = $active, `role` = $role_id, `group_id` = $group_id", 'u');
			}else{
				$main_sql = $Functions->createData("`login` = $login, `fio` = $fio, `phone` = $phone, `active` = $active, `role` = $role_id", 'u');
			}

		}

	}
}

if($id_column){
	$query = $Query->update($main_table, $main_sql, "`user_group_id` = $id");
}else{
	$query = $Query->update($main_table, $main_sql, "`id` = $id");
}

if (!$query['error']) {

	if($second_query === true){

		if($main_table === 'roles'){

			$permissions = $Query->getN('role_permissions', ['fields' => 'permission_id', 'where' => [['column' => 'role_id', 'value' => "'$id'"]], 'fieldAsKey' => 'permission_id']);

			foreach ($_POST['permission'] as $key => $val) {

				if(isset($val['view'])) $view = "'1'"; else $view = "'0'";
				if(isset($val['add'])) $add = "'1'"; else $add = "'0'";
				if(isset($val['edit'])) $edit = "'1'"; else $edit = "'0'";
				if(isset($val['delete'])) $delete = "'1'"; else $delete = "'0'";
				if(isset($val['function'])) $function = "'1'"; else $function = "'0'";
				if(isset($val['history'])) $history = "'1'"; else $history = "'0'";
				if(isset($val['recent_history'])) $recent_history = "'1'"; else $recent_history = "'0'";

				if(!array_key_exists($key, $permissions)){
					$add_query = true;
					$add_sql .= "(".$Functions->createData("$id, $key, $view, $add, $edit, $delete, $function, $history, $recent_history")."), ";
				}else{
					$second_query = $Query->update($second_table, $Functions->createData("`view_access` = $view, `add_access` = $add, `edit_access` = $edit, `delete_access` = $delete, `function_access` = $function, `history_access` = $history, `recent_history_access` = $recent_history", 'u'), "`role_id` = $id AND `permission_id` = $key");
				}
			}

			if($add_query === true){
				$add_query_first = $Query->insert($second_table, rtrim($add_sql, ', '));
			}
		}

		if($second_table === 'clients'){
			$second_query = $Query->update($second_table, $Functions->createData("`active` = $active", 'u'), "id = $id");
		}

		if(!$second_query['error']){

			if($add_query === true){
				if(!$add_query_first['error']){
					echo 'success'; exit;
				} else {
					echo $add_query_first['error']; exit;
				}
			}

			echo 'success'; exit;
		} else {
			echo $query['error']; exit;
		}
	}else{
		echo 'success'; exit;
	}
} else {
	echo $query['error']; exit;
}