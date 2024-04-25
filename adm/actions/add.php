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

if($user_permissions[$main_table]['add_access'] !== '1') exit(lot_kir("Ushbu amaliyotni bajarish uchun ruhsatingiz yo'q!"));

if($_POST['table'] === 'workers') $main_table = 'users';
elseif($_POST['table'] === 'import') $main_table = 'import_raw_m';

if($second_table === 'menu_categories') $main_table = 'menu_categories';
elseif($second_table === 'menu_parents') $main_table = 'menu_parents';
elseif($second_table === 'expenses_cats') $main_table = 'expenses_cats';
elseif($second_table === 'expenses_types') $main_table = 'expenses_types';

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
if(isset($_POST['user_id'])) $user_id = intval($_POST['user_id']); else unset($user_id);
if(isset($_POST['client_id'])) $client_id = intval($_POST['client_id']); else unset($client_id);
if(isset($_POST['role_id'])) $role_id = intval($_POST['role_id']); else unset($role_id);
if(isset($_POST['permission_id'])) $permission_id = intval($_POST['permission_id']); else unset($permission_id);

if(!empty($_POST['unit'])) $unit = "'".$_POST['unit']."'"; else unset($unit);

if(!empty($_POST['title'])) $title = "'".textType2($_POST['title'])."'"; else unset($title);

if($_FILES['photo']['error'] === 0){

	$path = "../assets/media/$main_table/";

	$valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp');

	$img = $_FILES['photo']['name'];
 	$tmp = $_FILES['photo']['tmp_name'];

	$ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

 	$photo = date("YmdHis").'.'.$ext;
 	$photo_final = "'$photo'";

	if(in_array($ext, $valid_extensions))  {
		$path = $path.strtolower($photo);

		if(!move_uploaded_file($tmp,$path)) {
			echo lot_kir("Rasm uchun fayl turi mos kelmadi");
			exit;
		}
	}
}else{
 	$photo_final = "'blank-image.svg'";
}

if(isset($_POST['type'])) $type = "'". json_encode($_POST['type']) ."'"; else unset($type);

if(isset($_POST['name']) && !empty($_POST['name'])) $name = $_POST['name']; else unset($name);
if(isset($_POST['name'])) {if(!empty($_POST['name'])) {$name = "'".htmlspecialchars(trim(textType2($_POST['name'])))."'";}} else {unset($name);}

// Nullabel variables
if(isset($_POST['comment'])) {if(!empty($_POST['comment'])) {$comment = "'".htmlspecialchars(trim(textType2($_POST['comment'])))."'";}else{$comment = "NULL";}} else {unset($comment);}
if(isset($_POST['info'])) {if(!empty($_POST['info'])) {$info = "'".htmlspecialchars(trim(textType2($_POST['info'])))."'";}else{$info = "NULL";}} else {unset($info);}
if(isset($_POST['to_id']) && !empty($_POST['to_id'])) {$to_id = intval($_POST['to_id']);}else{$to_id = "NULL";}
if(isset($_POST['phone'])) {if(!empty($_POST['phone'])) {$phone = "'".removess(remove_undescore($_POST['phone']))."'";} else {$phone = "NULL";} } else {unset($phone);}
if(isset($_POST['attached_group'])) {if(!empty($_POST['attached_group'])) {$attached_group = "'".removess(remove_undescore($_POST['attached_group']))."'";} else {$attached_group = "NULL";} } else {unset($attached_group);}
if(isset($_POST['color'])) {if(!empty($_POST['color'])) {$color = "'".removess(remove_undescore($_POST['color']))."'";} else {$color = "NULL";} } else {$color = "NULL";}

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

if(isset($_POST['time'])) {if(!empty($_POST['time'])) {$time = "'".dateDefault($_POST['time'], 'time')."'";}} else {unset($time);}
if(isset($_POST['arrival_time'])) {if(!empty($_POST['arrival_time'])) {$arrival_time = "'".dateDefault($_POST['arrival_time'], 'time')."'";}} else {unset($arrival_time);}
if(isset($_POST['date'])) {if(!empty($_POST['date'])) {$date = "'".dateDefault($_POST['date'], 'date')."'";}} else {unset($date);}

if(isset($_POST['active'])) $active = "'1'"; else $active = "'0'";
if(isset($_POST['affect_cost_price'])) $affect_cost_price = "'1'"; else $affect_cost_price = "'0'";
if(isset($_POST['is_worker'])) $is_worker = "'1'"; else $is_worker = "'0'";

// Validations
if($phone != NULL && $phone != "NULL") {if(strlen(removess(remove_undescore($_POST['phone']))) != 9) exit(lot_kir("Telefon noto'g'ri to'ldirildi!"));}

// Zero configs
$second_query = $third_query = false;

// SQL queries
if($main_table === 'roles'){

	$main_sql = "(".$Functions->createData("NULL, $title, $value, $is_worker, $worker_type, $active").")";

	if(isset($_POST['permission']) && !empty($_POST['permission'])){
		$second_query = true;
	}

}elseif($main_table === 'attendance'){
	$get_attendance = $Query->getN("attendance", [
		'fields' => 'worker_id',
		'where' => [
			['column' => 'date', 'value' => $date],
		],
	]);

	$get_attendance = array_column($get_attendance, 'worker_id');

	foreach ($_POST['worker'] as $key => $val) {
		if(array_key_exists($val, $_POST['active'])) continue;
		if(in_array($val, $get_attendance)) continue;

		$comment_multi = ($_POST['comment_multi'][$val] == '') ? "NULL" : "'{$_POST['comment_multi'][$val]}'";

		$arr_sql .= "(".$Functions->createData("$val, $date, '1', $comment_multi")."),";
	}
	if($arr_sql == '') exit(lot_kir("Siz avval kiritilgan ma'lumot kiritdingiz!"));

	$main_sql = rtrim($arr_sql, ',');
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
	$main_sql = "(".$Functions->createData("$to_id, $to_group_id, $type_id, $qty_custom, $cost, $time, $comment").")";
}elseif($main_table === 'expenses_cats'){
	$main_sql = "(".$Functions->createData("$title, $active").")";
}elseif($main_table === 'expenses_types'){
	$main_sql = "(".$Functions->createData("$cat_id, $title, $color, $affect_cost_price, $active").")";
}elseif($main_table === 'import_raw_m'){
	if((intval(str_replace("'", '', $quantity)) * intval(str_replace("'", '', $cost))) > $balance) exit(lot_kir("Balansdagi qoldiqdan ko'p summada sotib olish mumkin emas!"));
	$main_sql = "(".$Functions->createData("NULL, $raw_m_id, $quantity, $cost, $arrival_time, 0, $comment").")";
}elseif($main_table === 'investing'){
	$main_sql = "(".$Functions->createData("$date, $_SESSION[id], $amount, $comment").")";
}elseif($main_table === 'menu'){
	$main_sql = "(".$Functions->createData("$title, $cat_id, $parent_id, $link, $sort, $active").")";
}elseif($main_table === 'menu_categories'){
	$main_sql = "(".$Functions->createData("$title, $sort, $active").")";
}elseif($main_table === 'menu_parents'){
	$main_sql = "(".$Functions->createData("$title, $sort, $active").")";
}elseif($main_table === 'orders'){
	$main_sql = '';
	foreach ($_POST['product'] as $key => $val) {
		if(empty($val['product_id']) || empty($val['qty']) || empty($val['price'])) exit(lot_kir("Bo'sh qolgan qatorlarni to'ldiring!"));
		$main_sql .= "(".$Functions->createData("$client_id, 0, {$val['product_id']}, ". remove_spaces($val['qty']) .", ".removess($val['price']).", ".removess($val['tannarx']).", 'new', '0', $comment")."),";
	}
	$main_sql = rtrim($main_sql, ',');
}elseif($main_table === 'payments'){
	$main_sql = "(".$Functions->createData("$client_id, $time, $amount, '0', $comment").")";
}elseif($main_table === 'permissions'){
	$main_sql = "(".$Functions->createData("$title, $value, $type, $active").")";
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

	$main_sql = "(".$Functions->createData("$title, $compound, $reaper_1_price, $reaper_2_price, $reaper_helper_price, $tailor_price, $iron_man_price, $selling_price, $additional_expenses, $photo_final, $info, $season, $active").")";
}elseif($main_table === 'production'){
	$status = "'ready'";

	if($second_table === 'leftover'){
		foreach ($_POST['product'] as $key => $value) {
			$main_sql .= "(".$Functions->createData("{$value['product_id']}, NULL, NULL, '$now', {$value['qty']}, $status, NULL")."),";
		}
		$main_sql = rtrim($main_sql, ',');
	}else{

		if(isset($_POST['ironman'])){

			foreach ($_POST['ironman'] as $keys => $val) {
				$main_sql .= "(".$Functions->createData("'{$val['user_id']}', 'ironman', $product_id, $date, {$val['qty']}, NULL, $comment")."),";
			}
			$main_sql = rtrim($main_sql, ',');
			$main_table = 'works';

		}elseif(isset($_POST['packer'])){

			foreach ($_POST['packer'] as $keys => $val) {
				$qty = $val['qty'].':00:00';
				$main_sql .= "(".$Functions->createData("'{$val['user_id']}', 'packer', NULL, $date, NULL, '$qty', $comment")."),";
			}
			// $main_sql .= "(".$Functions->createData("'{$_POST['packer']}', 'packer', NULL, $date, NULL, '$qty', $comment")."),";
			$main_sql = rtrim($main_sql, ',');
			$main_table = 'works';

		}elseif(isset($_POST['producer'])){

			$attached_group_id = null;
			$qty_reaper = 1;

			foreach ($_POST['producer'] as $keys => $val) {

				$time_this = "'".date("Y-m-d H:i:s", strtotime($_POST['time']." +".$keys." seconds"))."'";

				$users_ids = $Query->getN("users", [
					'fields' => 'id',
					'where' => [
						['column' => 'group_id', 'value' => $val['stream']],
					],
				]);

				$attached_group_id = $Query->getN("users_groups", ['fields' => 'attached_group','where' => [['column' => 'user_group_id', 'value' => $val['stream']],],])[0]['attached_group'];

				if($attached_group_id != ''){

					$reapers_ids = $Query->getN("users", [
						'fields' => 'id',
						'where' => [
							['column' => 'group_id', 'value' => $attached_group_id],
						],
					]);

					if(!empty($reapers_ids)) {
						$qty_reaper = removess($val['qty']) / count($reapers_ids);

						foreach ($reapers_ids as $key => $value) {
							$main_sql .= "(".$Functions->createData("$product_id, '{$value['id']}', $attached_group_id, $time_this, $qty_reaper, 'reaper', $comment")."),";
						}
					}
					// $qty_reaper = removess($val['qty']) / 1; else $qty_reaper = removess($val['qty']) / count($reapers_ids);

				}

				$qty = removess($val['qty']) / count($users_ids);

				foreach ($users_ids as $key => $value) {
					$main_sql .= "(".$Functions->createData("$product_id, '{$value['id']}', {$val['stream']}, $time_this, $qty, $status, $comment")."),";
				}
			}
			$main_sql = rtrim($main_sql, ',');
			// posts();
			// echo($main_sql);exit;
		}
	}
}elseif($main_table === 'raw_materials'){
	$main_sql = "(".$Functions->createData("$title, $unit, $photo_final, $info, $active").")";
}elseif($main_table === 'residual'){
// posts(1);
	if(isset($_POST['client'])){
		$client_post = $Query->getN("clients", ['fields' => 'id, user_id','where' => [['column' => 'id', 'value' => intval($_POST['client'])],],])[0];
		$client_id = $client_post['id'];
		$u_id = $client_post['user_id'];
	}else{
		$client_id = $Query->getN("clients", ['fields' => 'id','where' => [['column' => 'user_id', 'value' => $_SESSION['id']],],])[0]['id'];
		$u_id = $_SESSION['id'];
	}

	// $residual_payments = 0;
	foreach ($_POST['product'] as $key => $val) {

		if(remove_spaces($val['residual_goods_qty']) < remove_spaces($val['qty'])) exit(lot_kir("Kiritilgan raqam qoldiqdan ko'p bo'lishi mumkin emas"));

		@$real_qty = ($val['residual_goods_qty'] - intval($val['qty']));

		$Query->update('residual', $Functions->createData("`archive` = '1'", 'u'), "client_id = $client_id AND product_id = '{$val['product_id']}' AND archive = '0'");
		$Query->update('orders', $Functions->createData("`archive` = '1'", 'u'), "client_id = $client_id AND product_id = '{$val['product_id']}' AND archive = '0'");

		if(empty($val['product_id'])) exit(lot_kir("Bo'sh qolgan qatorlarni to'ldiring!"));

		$soldout_sql .= "(".$Functions->createData("$client_id, $u_id, {$val['product_id']}, $real_qty, ".remove_spaces($val['cost']).", '0'")."),";

		if(empty($val['qty']) || $val['qty'] == 0 || $val['qty'] == null) continue;

		$this_cost = ($val['cost'] == null || $val['cost'] == '') ? "NULL" : remove_spaces($val['cost']);

		$main_sql .= "(".$Functions->createData("$client_id, $u_id, {$val['product_id']}, {$val['qty']}, $this_cost, $time, '0', $comment")."),";
	}

	$asd = $Query->insert('soldout_by_diller', rtrim($soldout_sql, ','));

	if(!$asd) exit("Xatolik №: 95821");

	$main_sql = rtrim($main_sql, ',');
	if($main_sql == '') $sql_status = 'empty';

	$second_query = true;
	$third_query = true;

}elseif($main_table === 'role_permissions'){
	$main_sql = "(".$Functions->createData("$role_id, $permission_id, $view_access, $add_access, $edit_access, $delete_access, $function_access").")";
}elseif($main_table === 'suppliers'){
	$main_sql = "(".$Functions->createData("$name, $phone, $info, $active").")";
}elseif($main_table === 'users_groups'){
	if(isset($_POST['other_workhouse'])) $other_workhouse = "1"; else "0";
	$main_sql = "(".$Functions->createData("$title, 'tailor', NULL, '$other_workhouse', $comment, $active").")";
}elseif($main_table === 'clients' || $main_table === 'users'){

	if($phone != "NULL"){
		$old_phone = $Query->getN("users", ['fields' => 'id','where' => [['column' => 'phone', 'value' => $phone],],])[0]['id'];
		if(isset($old_phone)) exit(lot_kir("Bu telefon raqam avval ro'yhatdan o'tkazilgan."));
	}

	if(isset($_POST['email'])) {if(!empty($_POST['email'])) {$email = "'".$_POST['email']."'";}else{$email = "NULL";}} else {unset($email);}
	if(isset($_POST['login'])) {if(!empty($_POST['login'])) {$login = "'".$_POST['login']."'";}else{$login = "NULL";}} else {unset($login);}
	if(isset($_POST['password'])) {if(!empty($_POST['password'])) {$password = "'".strrev(md5($_POST['password']))."'";}else{$password = "NULL";}} else {unset($password);}

	if ($email != "NULL" && !preg_match("/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i", $email)) {exit(lot_kir("Email formati noto'g'ri"));}

	if(strpos($_POST['email'], "'") !== false) exit(lot_kir("Ta'qiqlangan belgi ishlatildi."));

	if(isset($_POST['fio'])) {if(!empty($_POST['fio'])) {$fio = "'".htmlspecialchars(trim(textType($_POST['fio'])))."'";}} else {unset($fio);}

	if(empty($fio)) {
		echo lot_kir("So'ralgan ma'lumotlarni to'liq va to'g'ri kiriting");
		exit;
	}

	if($main_table === 'clients'){

		$role_id = $Query->getById('roles', 'customer', 'value')['id'];
		if(!$role_id) {
			echo lot_kir("So'ralgan ma'lumotlarni to'liq va to'g'ri kiriting");
			exit;
		}
		$main_sql = "(".$Functions->createData("$login, $password, $fio, $email, $phone, '1', $role_id, $group_id, NULL, NULL, 'blank-image.svg'").")";

		$main_table = 'users';
		$second_table = 'clients';
		$second_query = true;
	}else{
		if(empty($role_id)) {
			echo lot_kir("So'ralgan ma'lumotlarni to'liq va to'g'ri kiriting");
			exit;
		}

		if(isset($_POST['group_id'])){
			$main_sql = "(".$Functions->createData("$login, $password, $fio, $email, $phone, $active, $role_id, $group_id, NULL, NULL, $photo_final").")";
		}else{
			$main_sql = "(".$Functions->createData("$login, $password, $fio, $email, $phone, $active, $role_id, NULL, NULL, NULL, $photo_final").")";
		}
	}
}

if($sql_status == 'empty'){
	$query = true;
}else{
	$query = $Query->insert($main_table, $main_sql);
}

if (!$query['error']) {

	if($second_query === true){

		$insert_id = mysqli_insert_id($db);

		if($main_table === 'roles'){

			foreach ($_POST['permission'] as $key => $val) {
				if(isset($val['view'])) $view = "'1'"; else $view = "'0'";
				if(isset($val['add'])) $add = "'1'"; else $add = "'0'";
				if(isset($val['edit'])) $edit = "'1'"; else $edit = "'0'";
				if(isset($val['delete'])) $delete = "'1'"; else $delete = "'0'";
				if(isset($val['function'])) $function = "'1'"; else $function = "'0'";
				if(isset($val['history'])) $history = "'1'"; else $history = "'0'";
				if(isset($val['recent_history'])) $recent_history = "'1'"; else $recent_history = "'0'";

				$second_sql .= "(".$Functions->createData("$insert_id, $key, $view, $add, $edit, $delete, $function, $history, $recent_history")."), ";

			}

			$second_query = $Query->insert($second_table, rtrim($second_sql, ', '));
		}

		if($main_table === 'residual'){
			$second_query = $Query->update('payments', $Functions->createData("`archive` = '1'", 'u'), "client_id = $client_id AND archive = '0'");
		}

		if($second_table === 'clients'){
			$second_query = $Query->insert($second_table, "(".$Functions->createData("$insert_id, '1'").")");
		}

		// if($third_query === true && $residual_payments > 0){
		// 	if($main_table === 'residual'){

		// 		if(isset($_POST['client'])){
		// 			$client_post = $Query->getN("clients", ['fields' => 'id, user_id','where' => [['column' => 'id', 'value' => intval($_POST['client'])],],])[0];
		// 			$client_id = $client_post['id'];
		// 			$u_id = $client_post['user_id'];
		// 		}else{
		// 			$client_id = $Query->getN("clients", ['fields' => 'id','where' => [['column' => 'user_id', 'value' => $_SESSION['id']],],])[0]['id'];
		// 			$u_id = $_SESSION['id'];
		// 		}

		// 		$third_query = $Query->insert('residual_payments', "(".$Functions->createData("'$now', $u_id, $client_id, $residual_payments, '0'").")");
		// 	}
		// }

		if(!$second_query['error']){
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