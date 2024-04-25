<?php

if($_POST['table'] === 'production_works') {$_POST['table'] = 'production'; $_POST['second_table'] = 'works';}

include '../unique/config.php';
include '../core/brain.php';
include '../core/functions.php';

if (empty($_POST)) {
	header("Location: " . ADMIN_PAGE);
	exit;
}

if(isset($_POST['id'])) $id = intval($_POST['id']);

if(isset($_POST['table']) && !empty($_POST['table'])) {
	$main_table = removess(strtolower(htmlspecialchars(trim($_POST['table']))));
} else exit(lot_kir("Jadval tanlanmagan!"));

if(!array_key_exists($main_table, $user_permissions) || $user_permissions[$main_table]['delete_access'] !== '1') exit(lot_kir("Ushbu bo'limni o'chirish uchun ruhsatingiz yo'q!"));

if(isset($_POST['workers'])) $main_table = 'users';

if($_POST['second_table']) $main_table = $_POST['second_table'];

if($main_table === 'production'){
	$delete = mysqli_query($db, "UPDATE ".PREFIX."$main_table SET deleted = '1', deleted_at = '$now', deleted_user = '{$_SESSION['id']}' WHERE `{$_POST['column']}` = '$id' AND time = '{$_POST['time']}'");
}else{
	if($_POST['table'] === 'users_groups'){
		$delete = mysqli_query($db, "UPDATE ".PREFIX."$main_table SET deleted = '1', deleted_at = '$now', deleted_user = '{$_SESSION['id']}' WHERE user_group_id='$id'");
	}else{
		$delete = mysqli_query($db, "UPDATE ".PREFIX."$main_table SET deleted = '1', deleted_at = '$now', deleted_user = '{$_SESSION['id']}' WHERE id='$id'");
	}
}

if ($delete) {
	if($db->affected_rows === 0) exit(lot_kir("Amaliyot muvoffaqiyatli bajarildi, lekin natija 0 ga teng. Sozlamalar biroz no'to'g'ri bo'lishi mumkin. (Amaliyot turi: O'chirish. Xatolik ro'y bergan sahifa: ").' '.$_POST['table'].')');
	if($main_table == 'roles'){
		mysqli_query($db, "UPDATE ".PREFIX."role_permissions SET deleted = '1', deleted_at = '$now', deleted_user = '{$_SESSION['id']}' WHERE role_id='$id'");
	}
	echo 'success';
	exit;
} else {
	echo mysqli_errno($db).': '.mysqli_error($db);
	exit;
}