<?php

session_start();

if(!$_SESSION['id']){
	header("Location: auth");
}

include 'unique/config.php';
include 'core/connection.php';
include 'core/functions.php';

if(DEVELOPING_MODE === true){
	$roles_q = mysqli_query($db, "SELECT p.id as p_id, p.title as p_title, p.value as p_value,
	rp.view_access,rp.add_access,rp.edit_access,rp.delete_access,rp.function_access
	FROM ".PREFIX."role_permissions rp
	LEFT JOIN ".PREFIX."roles r ON r.id = rp.role_id
	LEFT JOIN ".PREFIX."permissions p ON p.id = rp.permission_id
	WHERE rp.role_id = '$_SESSION[role]' AND r.active = '1' AND p.active = '1'") or die("Getting role list: ".mysqli_error($db));
}else{
	$roles_q = mysqli_query($db, "SELECT p.id as p_id, p.title as p_title, p.value as p_value,
	rp.view_access,rp.add_access,rp.edit_access,rp.delete_access,rp.function_access
	FROM ".PREFIX."role_permissions rp
	LEFT JOIN ".PREFIX."roles r ON r.id = rp.role_id
	LEFT JOIN ".PREFIX."permissions p ON p.id = rp.permission_id
	WHERE rp.role_id = '$_SESSION[role]'") or die("Error getting role list");
}
while($row = $roles_q->fetch_assoc()){
	$user_permissions[$row['p_value']] = [
		'p_id' => $row['p_id'],
		'p_title' => $row['p_title'],
		'p_value' => $row['p_value'],
		'view_access' => $row['view_access'],
		'add_access' => $row['add_access'],
		'edit_access' => $row['edit_access'],
		'delete_access' => $row['delete_access'],
		'function_access' => $row['function_access'],
	];
}

include 'core/scripts_for_all_page.php';

$page_title = lot_kir("Mavjud bo'lmagan sahifa");

include 'inc/head.php';

include 'inc/begin_body.php';
?>
<style>
	.error-header {
		text-align: center;
		color: #616464;
		font-size: 20px;
		font-weight: 600;
	}

	.error-main {
		text-align: center;
		color: #DB0007;
	}

	.error-main span {
		display: inline-block;
		font-size: 200px;
		font-weight: 900;
	}

	.error-description {
		text-align: center;
		color: #616464;
		font-size: 20px;
		font-weight: 500;
	}
</style>
<div class="app-content flex-column-fluid">
	<div class="app-container container-fluid">
		<div class="card card-flush">
			<div class="card-body">
				<?php
					echo '<div class="error-header">' . lot_kir("Xatolik: 404. Mavjud bo‘lmagan sahifa") . '</div>';
					echo '<div class="error-main"><span>4</span><span>×</span><span>4</span></div>';
					echo '<div class="error-description">' . lot_kir("Bunday sahifa mavjud emas") . '</div>';
				?>
			</div>
		</div>
	</div>
</div>
<?php include 'inc/end_body.php'; ?>

</body>
</html>