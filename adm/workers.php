<?php

include 'core/index.php';

$page_title = lot_kir("Ishchilar");

include 'inc/head.php';

if(isset($_GET['id'])) $id = intval($_GET['id']);
if(isset($_GET['settings'])) $settings = intval($_GET['settings']);

$header['title'] = lot_kir("Ishchilar");
$header['breadcrumb'] = [0 => ['val' => lot_kir("Asosiy sahifa"), 'link' => '/'], $header['title']];

if($id || $settings){

	if($id) $user_id = $id; elseif($settings) $user_id = $settings;

	$user = $Query->getN('users u', [
		'fields' => 'u.id,u.login,u.fio,u.photo,u.email,u.phone,u.active,u.role,r.title as role_name,r.is_worker, IFNULL(g.title, "-") as g_title, u.group_id',
		'where'=> [
			['column'=>'u.id','value'=>$user_id],
			['column'=>'r.is_worker','value'=>"'1'"],
		],
		'join' => [
			['table' => 'roles r', 'on' => 'u.role = r.id'],
			['table' => 'users_groups g', 'on' => 'g.user_group_id = u.group_id'],
		],
		'ignore' => ['join' => " AND g.deleted <> '1'"],
		'fetch' => 'one',
	]);

	$roles = $Query->getN("roles", [
		'fields' => 'id, title',
		'where' => [
			['column' => 'active', 'value' => "'1'"],
		],
		'order' => ['title'],
	]);

	if($settings){
		$header['breadcrumb'] = [0 => ['val' => lot_kir("Asosiy sahifa"), 'link' => '/'], ['val' => $header['title'], 'link' => 'workers'], ['val' => $user['fio'], 'link' => 'workers?id='.$settings]];
	}else{
		$header['breadcrumb'] = [0 => ['val' => lot_kir("Asosiy sahifa"), 'link' => '/'], ['val' => $header['title'], 'link' => 'workers'], $user['fio']];
	}
}else{

	$users = $Query->getN("users u", [
		'fields' => 'u.id, u.fio, u.photo, r.title as role_title, r.value as role_value, IFNULL(g.title, "") as g_title, u.group_id',
		'join' => [
			['table' => 'roles r', 'on' => 'r.id = u.role'],
			['table' => 'users_groups g', 'on' => 'g.user_group_id = u.group_id'],
		],
		'ignore' => ['join' => " AND g.deleted <> '1'"],
		'order' => ['role_title', 'g_title', 'u.fio'],
		'group' => 'u.id',
		'idAsKey' => true,
		'where' => [
			['column' => 'r.is_worker', 'value' => "'1'"],
		],
	]);
}

$users_groups = $Query->getN("users_groups", [
	'fields' => 'user_group_id, title, active, type, attached_group, other_workhouse, comment',
	'order' => ['user_group_id'],
	'where' => [
		['column' => 'active', 'value' => "'1'"],
	],
	'fieldAsKey' => 'user_group_id',
]);

$worker_roles = $Query->getN("roles", [
	'fields' => 'id, title',
	'order' => ['title'],
	'where' => [
		['column' => 'is_worker', 'value' => "'1'"],
		['column' => 'active', 'value' => "'1'"],
	],
]);


include 'inc/begin_body.php';

?>

<div class="app-content flex-column-fluid">
	<?php if($id && !empty($user)): ?>
	<div class="app-container container-fluid">
		<div class="card mb-5 mb-lg-10">
			<div class="card-header">
				<div class="card-title m-0">
					<h3 class="fw-bold m-0"><?php echo lot_kir("Foydalanuvchi ma'lumoti") ?></h3>
				</div>
				<?php if($_SESSION['id'] === $id): ?>
					<a href="users?settings=<?php echo $id?>" class="btn btn-sm btn-primary align-self-center"><?php echo lot_kir("O'zgaritirish") ?></a>
				<?php elseif($user_permissions['workers']['edit_access'] === '1'): ?>
					<a href="users?settings=<?php echo $id?>" class="btn btn-sm btn-primary align-self-center"><?php echo lot_kir("O'zgaritirish") ?></a>
				<?php endif; ?>
			</div>
			<div class="card-body p-9">
				<div class="d-flex align-items-center">
					<div class="me-7">
						<div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
							<img src="assets/media/users/<?php echo $user['photo']?>" alt="image" />
						</div>
					</div>
					<div class="w-100">
						<div class="row mb-7">
							<label class="col-lg-6 fw-semibold text-muted"><?php echo lot_kir("To'liq ismi") ?></label>
							<div class="col-lg-6">
								<span class="fw-bold fs-6 text-gray-800"><?php echo $user['fio'] ?></span>
							</div>
						</div>
						<?php if($user['phone'] != ''): ?>
						<div class="row mb-7">
							<label class="col-lg-6 fw-semibold text-muted"><?php echo lot_kir("Telefon raqami") ?></label>
							<div class="col-lg-6 d-flex align-items-center">
								<a href="tel:+998<?php echo $user['phone']?>" class="fw-bold fs-6 text-primary-800 me-2"><?php echo phone_number9($user['phone']) ?></a>
							</div>
						</div>
						<?php endif; ?>
						<div class="row mb-7">
							<label class="col-lg-6 fw-semibold text-muted"><?php echo lot_kir("Kasbi") ?></label>
							<div class="col-lg-6 d-flex align-items-center">
								<span class="fw-bold fs-6 text-gray-800"><?php echo $user['role_name'] ?></span>
							</div>
						</div>
						<div class="row mb-7">
							<label class="col-lg-6 fw-semibold text-muted"><?php echo lot_kir("Guruhi (Potogi)") ?></label>
							<div class="col-lg-6 d-flex align-items-center">
								<span class="fw-bold fs-6 text-gray-800"><?php echo $user['g_title'] ?></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php if($show): ?>
			<div class="row">
				<div class="col-lg-8 mb-xl-10">
					<div class="card card-flush h-lg-100">
						<div class="card-header flex-nowrap pt-5">
							<h3 class="card-title align-items-start flex-column">
								<span class="card-label fw-bold text-dark"><?php echo lot_kir("Bajargan ishi bo'yicha umumiy statistika") ?></span>
							</h3>
						</div>
						<div class="card-body pt-5 ps-6">
							<div id="kt_charts_widget_5" class="min-h-auto"></div>
						</div>
					</div>
				</div>
				<div class="col-xl-4 mb-xl-10">
					<div class="card card-flush h-md-100">
						<div class="card-header pt-7">
							<h3 class="card-title align-items-start flex-column">
								<span class="card-label fw-bold text-dark"><?php echo lot_kir("Oxirgi ishlari") ?></span>
							</h3>
						</div>
						<div class="card-body">
							<div class="hover-scroll-overlay-y pe-6 me-n6" style="height: 415px">
								<div class="border border-dashed border-gray-300 rounded px-7 py-3 mb-6">
									<div class="d-flex flex-stack mb-3">
										<div class="me-3">
											<img src="assets/media/stock/ecommerce/210.gif" class="w-50px ms-n1 me-1" alt="" />
											<a href="../../demo1/dist/apps/ecommerce/catalog/edit-product.html" class="text-gray-800 text-hover-primary fw-bold">Elephant 1802</a>
										</div>
									</div>
									<div class="d-flex flex-stack">
										<span class="text-gray-400 fw-bold">To:
										<a href="../../demo1/dist/apps/ecommerce/sales/details.html" class="text-gray-800 text-hover-primary fw-bold">Jason Bourne</a></span>
										<span class="badge badge-light-success">Delivered</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row gy-5 g-xl-10">
				<div class="col">
					<div class="card card-flush h-xl-100">
						<div class="card-header pt-7">
							<h3 class="card-title align-items-start flex-column">
								<span class="card-label fw-bold text-dark"><?php echo lot_kir("Oylik hisoboti") ?></span>
							</h3>
						</div>
						<div class="card-body">
							<table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_table_widget_5_table">
								<thead>
									<tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
										<th class="min-w-150px"><?php echo lot_kir("Oy") ?></th>
										<th class="text-end pe-3 min-w-100px"><?php echo lot_kir("Bajarilgan ish") ?></th>
										<th class="text-end pe-3 min-w-150px"><?php echo lot_kir("Hisoblangan oylik") ?></th>
										<th class="text-end pe-3 min-w-100px"><?php echo lot_kir("To'langan pul") ?></th>
									</tr>
								</thead>
								<tbody class="fw-bold text-gray-600">
									<tr>
										<td>
											<a href="../../demo1/dist/apps/ecommerce/catalog/edit-product.html" class="text-dark text-hover-primary">Macbook Air M1</a>
										</td>
										<td class="text-end">#XGY-356</td>
										<td class="text-end">02 Apr, 2023</td>
										<td class="text-end">$1,230</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<?php elseif($settings && !empty($user)): ?>
		<div id="kt_app_content_container" class="app-container container-fluid">
			<div class="card mb-5 mb-xl-10">
				<div class="card-header border-0">
					<div class="card-title m-0">
						<h3 class="fw-bold m-0"><?php echo lot_kir("Ma'lumotlarni o'zgartirish") ?></h3>
					</div>
				</div>
				<form id="editUserForm">
					<div class="card-body border-top p-9">
						<div class="row mb-6">
							<label class="col-lg-4 col-form-label fw-semibold fs-6"><?php echo lot_kir("Rasmi") ?></label>
							<div class="col-lg-8">
								<div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('assets/media/users/blank-image.svg')">
									<div class="image-input-wrapper w-125px h-125px" style="background-image: url(assets/media/users/<?php echo $user['photo']?>)"></div>
									<label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
										<i class="ki-duotone ki-pencil fs-7">
											<span class="path1"></span>
											<span class="path2"></span>
										</i>
										<input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
										<input type="hidden" name="avatar_remove" />
									</label>
									<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
										<i class="ki-duotone ki-cross fs-2">
											<span class="path1"></span>
											<span class="path2"></span>
										</i>
									</span>
									<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
										<i class="ki-duotone ki-cross fs-2">
											<span class="path1"></span>
											<span class="path2"></span>
										</i>
									</span>
								</div>
								<div class="form-text"><?php echo lot_kir("Ruhsat etilgan fayl turlari") ?>: png, jpg, jpeg.</div>
							</div>
						</div>
						<div class="row mb-6">
							<label class="col-lg-4 col-form-label required fw-semibold fs-6"><?php echo lot_kir("To'liq ismi") ?></label>
							<div class="col-lg-8">
								<div class="fv-row">
									<input type="text" name="fio" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" value="<?php echo $user['fio'] ?>" />
								</div>
							</div>
						</div>
						<div class="row mb-6">
							<label class="col-lg-4 col-form-label fw-semibold fs-6">
								<span class="required"><?php echo lot_kir("Telefoni") ?></span>
							</label>
							<div class="col-lg-8 fv-row">
								<input type="text" name="phone" class="form-control form-control-lg form-control-solid mask_phone" value="<?php echo $user['phone']?>" />
							</div>
						</div>
						<div class="row mb-6">
							<label class="col-lg-4 col-form-label fw-semibold fs-6"><?php echo lot_kir("Login") ?></label>
							<div class="col-lg-8 fv-row">
								<input type="text" name="login" class="form-control form-control-lg form-control-solid" value="<?php echo $user['login'] ?>" />
							</div>
						</div>
						<div class="row mb-6">
							<label class="col-lg-4 col-form-label required fw-semibold fs-6"><?php echo lot_kir("Roli") ?></label>
							<div class="col-lg-8 fv-row">
								<select name="role_id" aria-label="<?php echo lot_kir("Rolni tanlang") ?>" data-control="select2" data-placeholder="<?php echo lot_kir("Rolni tanlang") ?>" class="form-select form-select-solid form-select-lg" data-hide-search="true">
									<option value=""><?php echo lot_kir("Rolni tanlang") ?></option>
									<?php foreach ($roles as $v) {
										echo '<option value="'.$v['id'].'"'; if($v['id'] == $user['role']) echo ' selected'; echo '>'. lot_kir($v['title']) .'</option>';
									} ?>
								</select>
							</div>
						</div>
						<div class="row mb-6">
							<label class="col-lg-4 col-form-label required fw-semibold fs-6"><?php echo lot_kir("Potogi (Guruhi)") ?></label>
							<div class="col-lg-8 fv-row">
								<select name="group_id" aria-label="<?php echo lot_kir("Potokni tanlang") ?>" data-control="select2" data-placeholder="<?php echo lot_kir("Potokni tanlang") ?>" class="form-select form-select-solid form-select-lg" data-hide-search="true">
									<option><?php echo lot_kir("Potokni tanlang") ?></option>
									<?php foreach ($users_groups as $v) {
										echo '<option value="'.$v['user_group_id'].'"'; if($v['user_group_id'] == $user['group_id']) echo ' selected'; echo '>'. lot_kir($v['title']) .'</option>';
									} ?>
								</select>
							</div>
						</div>
						<div class="row">
							<label class="col-lg-4 col-form-label required fw-semibold fs-6"><?php echo lot_kir("Aktivligi") ?></label>
							<div class="col-lg-8 fv-row fv-plugins-icon-container">
								<div class="d-flex align-items-center mt-3">
									<label class="form-check form-check-custom form-check-inline form-check-solid me-5">
										<input name="table" type="hidden" value="workers" />
										<input name="id" type="hidden" value="<?php echo $settings ?>" />
										<input class="form-check-input" name="active" type="checkbox" <?php if($user['active'] === '1') echo 'checked';?> />
										<span class="fw-semibold ps-2 fs-6"><?php echo lot_kir("Aktiv") ?></span>
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer d-flex justify-content-end py-6 px-9">
						<button type="reset" class="btn btn-light btn-active-light-primary me-2"><?php echo lot_kir("Inkor qilish") ?></button>
						<button type="submit" class="btn btn-primary"><?php echo lot_kir("Saqlash") ?></button>
					</div>
				</form>
			</div>
			<div class="card">
				<div class="card-header border-0">
					<div class="card-title m-0">
						<h3 class="fw-bold m-0"><?php echo lot_kir("Kirish sozlamalari") ?></h3>
					</div>
				</div>
				<div class="card-body border-top p-9">
					<div class="d-flex flex-wrap align-items-center">
						<div id="kt_signin_email">
							<div class="fs-6 fw-bold mb-1"><?php echo lot_kir("Pochta manzili") ?></div>
							<div class="fw-semibold text-gray-600"><?php echo $user['email'] ?></div>
						</div>
						<div id="kt_signin_email_edit" class="flex-row-fluid d-none">
							<form id="changeEmailForm">
								<div class="row mb-6">
									<div class="col-lg-6 mb-4 mb-lg-0">
										<div class="fv-row mb-0">
											<label class="form-label fs-6 fw-bold mb-3"><?php echo lot_kir("Pochta manzilini kiriting") ?></label>
											<input type="email" class="form-control form-control-lg form-control-solid" placeholder="<?php echo lot_kir("Pochta manzili") ?>" name="email" value="<?php echo $user['email'] ?>" />
										</div>
									</div>
									<div class="col-lg-6">
										<div class="fv-row mb-0">
											<label class="form-label fs-6 fw-bold mb-3"><?php echo lot_kir("Parolni tering") ?></label>
											<input type="password" class="form-control form-control-lg form-control-solid" name="input_password" />
										</div>
									</div>
								</div>
								<div class="d-flex">
									<input name="table" type="hidden" value="workers" />
									<input name="id" type="hidden" value="<?php echo $settings ?>" />
									<button type="submit" class="btn btn-primary me-2 px-6"><?php echo lot_kir("Pochtani yangilash") ?></button>
									<button id="kt_signin_cancel" type="button" class="btn btn-color-gray-400 btn-active-light-primary px-6"><?php echo lot_kir("Inkor qilish") ?></button>
								</div>
							</form>
						</div>
						<div id="kt_signin_email_button" class="ms-auto">
							<button class="btn btn-light btn-active-light-primary"><?php echo lot_kir("Pochtani o'zgartirish") ?></button>
						</div>
					</div>
					<div class="separator separator-dashed my-6"></div>
					<div class="d-flex flex-wrap align-items-center">
						<div id="kt_signin_password">
							<div class="fs-6 fw-bold mb-1"><?php echo lot_kir("Parol") ?></div>
							<div class="fw-semibold text-gray-600">************</div>
						</div>
						<div id="kt_signin_password_edit" class="flex-row-fluid d-none">
							<form id="changePasswordForm">
								<div class="row mb-1">
									<div class="col-lg-4">
										<div class="fv-row mb-0">
											<label class="form-label fs-6 fw-bold mb-3"><?php echo lot_kir("Joriy parol") ?></label>
											<input type="password" class="form-control form-control-lg form-control-solid" name="currentpassword" required />
										</div>
									</div>
									<div class="col-lg-4">
										<div class="fv-row mb-0">
											<label for="newpassword" class="form-label fs-6 fw-bold mb-3"><?php echo lot_kir("Yangi parol") ?></label>
											<input type="password" class="form-control form-control-lg form-control-solid" name="newpassword" required />
										</div>
									</div>
									<div class="col-lg-4">
										<div class="fv-row mb-0">
											<label for="confirmpassword" class="form-label fs-6 fw-bold mb-3"><?php echo lot_kir("Yangi parolni qayta tering") ?></label>
											<input type="password" class="form-control form-control-lg form-control-solid" name="confirmpassword" required />
										</div>
									</div>
								</div>
								<div class="form-text mb-5"><?php echo lot_kir("Parol kamida 4 ta belgidan iborat bo'lishi kerak") ?></div>
								<div class="d-flex">
									<input name="table" type="hidden" value="workers" />
									<input name="id" type="hidden" value="<?php echo $settings ?>" />
									<button type="submit" class="btn btn-primary me-2 px-6"><?php echo lot_kir("Parolni yangilash") ?></button>
									<button id="kt_password_cancel" type="button" class="btn btn-color-gray-400 btn-active-light-primary px-6"><?php echo lot_kir("Inkor qilish") ?></button>
								</div>
							</form>
						</div>
						<div id="kt_signin_password_button" class="ms-auto">
							<button class="btn btn-light btn-active-light-primary"><?php echo lot_kir("Parolni o'zgartirish") ?></button>
						</div>
					</div>
				</div>
			</div>
			<div class="card mt-10">
				<div class="card-header border-0">
					<div class="card-title m-0">
						<h3 class="fw-bold m-0"><?php echo lot_kir("Profilni o'chirish") ?></h3>
					</div>
				</div>
				<form id="account_deactivate_form">
					<div class="card-body border-top p-9">
						<div class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-9 p-6">
							<i class="ki-duotone ki-information fs-2tx text-warning me-4">
								<span class="path1"></span>
								<span class="path2"></span>
								<span class="path3"></span>
							</i>
							<div class="d-flex flex-stack flex-grow-1">
								<div class="fw-semibold">
									<h4 class="text-gray-900 fw-bold"><?php echo lot_kir("Ushbu qismda profilni butunlay o'chirasiz") ?></h4>
									<div class="fs-6 text-gray-700"><?php echo lot_kir("Diqqat!!! Profilni o'chirgandan so'ng uni ishlatib ham unga kirib ham bo'lmaydi! (Profilga tegishli eski ma'lumotlar saqlanib qoladi)") ?>
									</div>
								</div>
							</div>
						</div>
						<div class="form-check form-check-solid fv-row">
							<input name="table" type="hidden" value="users" />
							<input name="id" class="form-check-input" type="checkbox" id="agree_deactivate" value="<?php echo $settings; ?>" />
							<label class="form-check-label fw-semibold ps-2 fs-6" for="agree_deactivate"><?php echo lot_kir("Profilni o'chirishni tasdiqlayman") ?></label>
						</div>
					</div>
					<div class="card-footer d-flex justify-content-end py-6 px-9">
						<button type="submit" class="btn btn-danger fw-semibold" disabled="disabled"><?php echo lot_kir("Profilni o'chirish") ?></button>
					</div>
				</form>
			</div>
		</div>
	<?php elseif(!$settings && !$id): ?>
		<div class="app-container container-fluid">
			<?php if($user_permissions['attendance']['add_access'] === '1' || $user_permissions['users_groups']['add_access'] === '1' || $user_permissions[SCRIPTNAME]['add_access'] === '1'): ?>
			<div class="row d-flex justify-content-between align-items-center mb-5">
				<div class="col-md-4">
					<h3 class="fw-bold"><?php echo lot_kir("Ishchilar ro'yhati") ?></h3>
				</div>
				<div class="d-flex col-md-8 flex-md-end flex-sm-row flex-column">
					<?php if($user_permissions['attendance']['add_access'] === '1'): ?>
						<div class="app-navbar-item ms-md-3 mb-sm-0 mb-3"><a data-bs-toggle="modal" data-bs-target="#addAttendaceModal" class="btn btn-sm btn-primary w-100"><?php echo lot_kir("Yo'qlama") ?></a></div>
					<?php endif; ?>
					<?php if($user_permissions[SCRIPTNAME]['add_access'] === '1'): ?>
						<div class="app-navbar-item ms-md-3 mb-sm-0 mb-3"><a data-bs-toggle="modal" data-bs-target="#addWorkerModal" class="btn btn-sm btn-primary w-100"><?php echo lot_kir("Ishchi qo'shish") ?></a></div>
					<?php endif; ?>
					<?php if($user_permissions['users_groups']['view_access'] === '1'): ?>
						<div class="app-navbar-item ms-sm-3 mb-sm-0 mb-3"><a data-bs-toggle="modal" data-bs-target="#streamModal" class="btn btn-sm btn-primary w-100"><?php echo lot_kir("Potoklar") ?></a></div>
					<?php endif; ?>
				</div>
			</div>
			<?php endif; ?>
			<div class="card card-flush">
				<div class="accordion" id="workers_stream">
					<table class="table align-middle mt-5" data-table="orders">
						<thead>
							<?php echo '<tr class="text-primary fw-bold text-uppercase">
								<th class="ps-10 w-50">'. lot_kir("Ishchi").'</th>
								<th class="px-5 text-center">'. lot_kir("Lavozimi").'</th>';
								if($user_permissions[SCRIPTNAME]['edit_access'] === '1' || $user_permissions[SCRIPTNAME]['delete_access'] === '1'): echo '<th class="text-end pe-5 mw-100px"></th>'; endif;
							echo '</tr>';?>
						</thead>
					</table>
				<?php
				foreach ($users_groups as $v) {

					if(!in_array($v['user_group_id'], array_unique(array_column($users, 'group_id')))) continue;

					// if($v['type'] === 'reaper') $group_type_list = lot_kir("Bichuvchi");
					// if($v['type'] === 'tailor') $group_type_list = lot_kir("Tikuvchi");
					// elseif($v['type'] === 'ironman') $group_type_list = lot_kir("Dazmolchi");
					echo '<div class="accordion-item border-left-0 border-right-0 rounded-0">
						<h2 class="accordion-header" id="workers_stream_header_'.$v['user_group_id'].'">
							<button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#workers_stream_body_'.$v['user_group_id'].'" aria-expanded="true" aria-controls="workers_stream_body_'.$v['user_group_id'].'">
								'.++$num.'. '. lot_kir($v['title']) .'
							</button>
						</h2>
						<div id="workers_stream_body_'.$v['user_group_id'].'" class="accordion-collapse collapse" aria-labelledby="workers_stream_header_'.$v['user_group_id'].'" data-bs-parent="#workers_stream">
							<div class="accordion-body p-0">
								<div class="table-responsive">
									<table class="table align-middle table-row-dashed mb-0" data-table="orders">
										<tbody>';
											foreach ($users as $key => $val) {
												if($val['group_id'] == $v['user_group_id']){
													echo '<tr>
														<td class="w-50 ps-5">
															<div class="d-flex align-items-center">
																<a href="?id='.$val['id'].'" class="symbol symbol-50px">
																	<span class="symbol-label" style="background-image:url(/assets/media/users/'.$val['photo'].');"></span>
																</a>
																<div class="ms-5">
																	<a href="?id='.$val['id'].'" class="text-gray-800 text-hover-primary fs-5 fw-bold">'. lot_kir($val['fio']) .'</a>
																</div>
															</div>
														</td>
														<td align="center">'. lot_kir($val['role_title']) .'</td>';
														if ($user_permissions[SCRIPTNAME]['edit_access'] === '1'):
														echo '<td class="text-end pe-5 mw-100px"><a href="users?settings='.$val['id'].'" class="btn btn-icon btn-light-warning w-30px h-30px me-3">
															<i class="ki-duotone ki-notepad-edit fs-3">
																<span class="path1"></span>
																<span class="path2"></span>
																<span class="path3"></span>
																<span class="path4"></span>
															</i>
														</a></td>';
														endif;
													echo '</tr>';
												}
											}
										echo '</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>';
				} ?>
				</div>
				<?php echo '
				<table class="table align-middle table-row-dashed mt-3" data-table="orders">
					<tbody>';
						foreach ($users as $key => $val) {
							if($val['group_id'] == ''){
								echo '<tr>
									<td class="ps-5">
										<div class="d-flex align-items-center">
											<a href="?id='.$val['id'].'" class="symbol symbol-50px">
												<span class="symbol-label" style="background-image:url(/assets/media/users/'.$val['photo'].');"></span>
											</a>
											<div class="ms-5">
												<a href="?id='.$val['id'].'" class="text-gray-800 text-hover-primary fs-5 fw-bold">'. lot_kir($val['fio']) .'</a>
											</div>
										</div>
									</td>
									<td align="center">'. lot_kir($val['role_title']) .'</td>';
									if ($user_permissions[SCRIPTNAME]['edit_access'] === '1'):
									echo '<td class="text-end pe-5"><a href="users?settings='.$val['id'].'" class="btn btn-icon btn-light-warning w-30px h-30px me-3">
										<i class="ki-duotone ki-notepad-edit fs-3">
											<span class="path1"></span>
											<span class="path2"></span>
											<span class="path3"></span>
											<span class="path4"></span>
										</i>
									</a></td>';
									endif;
								echo '</tr>';
							}
						}
					echo '</tbody>
				</table>';
				?>
			</div>
		</div>
	<?php endif ?>
</div>
<?php include 'inc/end_body.php'; ?>

<?php if($user_permissions['users_groups']['view_access'] === '1'): ?>
<!-- Streams -->
<div class="modal fade" id="streamModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-750px">
		<div class="modal-content">
			<div class="modal-header <?php if(count($users_groups) < 10) echo 'pb-0 border-0'; ?>">
				<div class="d-flex">
					<h2 class="fw-bold mb-0"><?php echo lot_kir("Potoklar") ?></h2>
					<?php if($user_permissions['users_groups']['add_access'] === '1'): ?>
					<?php endif; ?>
					<span data-bs-stacked-modal="#addStreamModal" class="ms-2 badge badge-primary cursor-pointer"><?php echo lot_kir("Qo'shish") ?></span>
				</div>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body p-0">
				<table class="table align-middle table-row-dashed mt-5" data-table="users_groups" <?php if(count($users_groups) > 10) echo 'datatable="modal_type"'; ?>>
					<thead>
						<tr class="text-primary fw-bold">
							<th class="text-center ps-5">№</th>
							<th><?php echo lot_kir("Nomi") ?></th>
							<th><?php echo lot_kir("Izoh") ?></th>
							<th class="text-center"><?php echo lot_kir("Aktiv") ?></th>
							<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1' || $user_permissions[SCRIPTNAME]['delete_access'] === '1'): ?>
								<th class="text-end pe-5"><?php echo lot_kir("Amallar") ?></th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($users_groups as $row) {
							echo '<tr data-id="'.$row['user_group_id'].'">
								<td align="center">' . ++$cats_num . '</td>
								<td>' . lot_kir($row['title']) . '</td>
								<td>' . $row['comment'] . '</td>
								<td align="center">'; echo ($row['active'] === '1') ? ' <i class="far fa-check-circle text-success fs-1"></i>' : '<i class="far fa-check-circle text-secondary fs-1"></i>'; echo '</td>
								'.actionsFunction('editStreamModal', [
									'data-id' => $row['user_group_id'],
									'data-title' => $row['title'],
									'data-type' => $row['type'],
									'data-other_workhouse' => $row['other_workhouse'],
									'data-attached_group' => $row['attached_group'],
									'data-active' => $row['active'],
									'data-comment' => $row['comment'],
									'data-table' => 'users_groups',
									'modal' => 'stacked',
								], $user_permissions['users_groups']).'
							</tr>';
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php if($user_permissions[SCRIPTNAME]['add_access'] === '1' || $user_permissions['attendance']['add_access'] === '1' || $user_permissions['users_groups']['add_access'] === '1'): ?>
<?php if($user_permissions[SCRIPTNAME]['add_access'] === '1'): ?>
<!-- Add Worker -->
<div class="modal fade" id="addWorkerModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Ishchi qo'shish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="addWorkerForm">
					<div class="text-center">
						<div class="image-input image-input-circle image-input-empty image-input-outline image-input-placeholder mb-10" data-kt-image-input="true">
							<div class="image-input-wrapper w-150px h-150px"></div>
							<label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" aria-label="Change avatar" data-bs-original-title="Change avatar" data-kt-initialized="1">
								<i class="ki-duotone ki-pencil fs-7"><span class="path1"></span><span class="path2"></span></i>
								<input type="file" name="photo" accept=".png, .jpg, .jpeg">
							</label>
							<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" aria-label="Cancel avatar" data-bs-original-title="Cancel avatar" data-kt-initialized="1">
								<i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
							</span>
						</div>
					</div>
					<div class="form-floating mb-7">
						<select class="form-select form-select-solid job_select" name="role_id" data-placeholder="<?php echo lot_kir("Kasbini tanlang")?>" data-hide-search="true" data-control="select2" required>
						<option></option>
						<?php foreach ($worker_roles as $key => $val) {
							echo '<option value="'.$val['id'].'">'.lot_kir($val['title']).'</option>';
						} ?>
						</select>
						<label class="required"><?php echo lot_kir("Kasbi") ?></label>
					</div>
					<div class="form-floating mb-7 d-none stream_select">
						<select data-control="select2" data-hide-search="true" class="form-select form-select-solid">
							<option value="0"><?php echo lot_kir("Biriktirilmagan") ?></option>
							<?php foreach ($users_groups as $v) {
								// if($v['type'] === 'reaper') $group_type = lot_kir("Bichuvchi");
								// if($v['type'] === 'tailor') $group_type = lot_kir("Tikuvchi");
								// elseif($v['type'] === 'ironman') $group_type = lot_kir("Dazmolchi");
								if($v['type'] !== 'tailor') continue;

								echo '<option value="'.$v['user_group_id'].'">'. lot_kir($v['title']) .'</option>';
							} unset($group_type)?>
						</select>
						<label><?php echo lot_kir("Potogi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid" name="fio" placeholder="<?php echo lot_kir("To'liq ism") ?>" required />
						<label class="required"><?php echo lot_kir("To'liq ism") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid" name="login" placeholder="<?php echo lot_kir("Login") ?>" />
						<label><?php echo lot_kir("Login") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid mask_phone" name="phone" placeholder="<?php echo lot_kir("Telefon") ?>" />
						<label><?php echo lot_kir("Telefon") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="email" class="form-control form-control-solid mask_email" name="email" placeholder="<?php echo lot_kir("Pochta (E-mail)") ?>" />
						<label><?php echo lot_kir("Pochta (E-mail)") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid" name="password" placeholder="<?php echo lot_kir("Parol (Kamida 4 ta simvol)") ?>" />
						<label><?php echo lot_kir("Parol (Kamida 4 ta simvol)") ?></label>
					</div>
					<div class="col d-inline-block">
						<label class="form-check form-check-sm form-check-custom form-check-solid">
							<input class="form-check-input" type="checkbox" name="active" checked>
							<span class="form-check-label"><?php echo lot_kir("Aktiv") ?></span>
						</label>
					</div>
					<div class="text-center pt-5">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
							<input type="hidden" name="table" value="workers">
							<span class="indicator-label"><?php echo lot_kir("Saqlash") ?></span>
							<span class="indicator-progress"><?php echo lot_kir("Amaliyot bajarilmoqda"); ?>...
							<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
<?php if($user_permissions['attendance']['add_access'] === '1'): ?>
<!-- Add Attendace -->
<div class="modal fade" id="addAttendaceModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-550px">
		<div class="modal-content">
			<div class="modal-header">
				<h2 class="fw-bold"><?php echo lot_kir("Yo'qlama") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<form id="addAttendaceForm">
				<div class="modal-body" style="max-height: 500px; overflow:none; overflow-y:scroll">
					<?php foreach ($users as $key => $row) {
						// if($row['role_value'] === 'packer' || $row['role_value'] === 'ironman' || $row['role_value'] === 'reaper') continue;
						echo '<div class="mb-5">
							<div class="row">
								<div class="col-6 my-auto">'. lot_kir($row['fio']) .'</div>
								<div class="col-6 text-end">
									<div class="d-flex align-items-center">
										<div class="col d-inline-block">
											<label class="form-check form-check-sm form-check-custom form-check-solid flex-end">
												<input type="hidden" name="worker[]" value="'.$row['id'].'">
												<input class="form-check-input" type="checkbox" name="active['.$row['id'].']" checked>
											</label>
										</div>
										<div class="form-floating ms-3 comment_input d-none">
											<input type="text" class="form-control form-control-solid" name="comment_multi['.$row['id'].']" placeholder="'.lot_kir("Izoh").'" />
											<label>'.lot_kir("Izoh").'</label>
										</div>
									</div>
								</div>
							</div>
						</div>';
					} ?>
				</div>
				<div class="modal-footer align-items-center justify-content-between">
					<div class="form-floating">
						<input type="text" class="form-control form-control-sm form-control-solid date_format" name="date" placeholder="<?php echo lot_kir("Kuni") ?> "value="<?php echo date("d.m.Y", strtotime($now)) ?>" required />
						<label class="required"><?php echo lot_kir("Kuni") ?></label>
					</div>
					<div>
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary" disabled="disabled">
							<input type="hidden" name="table" value="attendance">
							<span class="indicator-label"><?php echo lot_kir("Saqlash") ?></span>
							<span class="indicator-progress"><?php echo lot_kir("Amaliyot bajarilmoqda"); ?>...
							<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<?php endif; ?>
<?php if($user_permissions['users_groups']['add_access'] === '1'): ?>
<!-- Add Stream -->
<div class="modal fade" id="addStreamModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Potok qo'shish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="addStreamForm">
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid" name="title" placeholder="<?php echo lot_kir("Nomi") ?>" required />
						<label class="required"><?php echo lot_kir("Nomi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<textarea class="form-control form-control-solid" name="comment" placeholder="Izoh" style="height: 100px"></textarea>
						<label><?php echo lot_kir("Izoh") ?></label>
					</div>
					<div class="row">
						<div class="col d-inline-block">
							<label class="form-check form-check-sm form-check-custom form-check-solid">
								<input class="form-check-input" type="checkbox" name="active" checked>
								<span class="form-check-label"><?php echo lot_kir("Aktiv") ?></span>
							</label>
						</div>
						<div class="col d-inline-block">
							<label class="form-check form-check-sm form-check-custom form-check-solid">
								<input class="form-check-input" type="checkbox" name="other_workhouse">
								<span class="form-check-label"><?php echo lot_kir("Boshqa tseh") ?></span>
							</label>
						</div>
					</div>
					<div class="text-center pt-5">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
							<input type="hidden" name="table" value="users_groups">
							<span class="indicator-label"><?php echo lot_kir("Saqlash") ?></span>
							<span class="indicator-progress"><?php echo lot_kir("Amaliyot bajarilmoqda"); ?>...
							<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
<?php endif; ?>

<?php if($user_permissions['users_groups']['edit_access'] === '1'): ?>
<!-- Edit Stream -->
<div class="modal fade" id="editStreamModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Qo'shish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="editStreamForm">
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid" name="title" placeholder="<?php echo lot_kir("Nomi") ?>" required />
						<label class="required"><?php echo lot_kir("Nomi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<textarea class="form-control form-control-solid" name="comment" placeholder="Izoh" style="height: 100px"></textarea>
						<label><?php echo lot_kir("Izoh") ?></label>
					</div>
					<div class="row">
						<div class="col d-inline-block">
							<label class="form-check form-check-sm form-check-custom form-check-solid">
								<input class="form-check-input" type="checkbox" name="active" checked>
								<span class="form-check-label"><?php echo lot_kir("Aktiv") ?></span>
							</label>
						</div>
						<div class="col d-inline-block">
							<label class="form-check form-check-sm form-check-custom form-check-solid">
								<input class="form-check-input" type="checkbox" name="other_workhouse">
								<span class="form-check-label"><?php echo lot_kir("Boshqa tseh") ?></span>
							</label>
						</div>
					</div>
					<div class="text-center pt-5">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
							<input type="hidden" name="table" value="users_groups">
							<input type="hidden" name="id">
							<span class="indicator-label"><?php echo lot_kir("Saqlash") ?></span>
							<span class="indicator-progress"><?php echo lot_kir("Amaliyot bajarilmoqda"); ?>...
							<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php include 'inc/javascript.php'; ?>
<script src="assets/js/custom/account/settings/signin-methods.js"></script>
<script src="assets/js/custom/account/settings/profile-details.js"></script>
<script src="assets/js/custom/account/settings/deactivate-account.js"></script>
<script src="assets/js/custom/pages/user-profile/general.js"></script>

<script>

	<?php if($user_permissions[SCRIPTNAME]['add_access'] === '1' || $user_permissions['users_groups']['add_access'] === '1' || $user_permissions['users_groups']['edit_access'] === '1'): ?>
		$("#addWorkerModal, #addStreamModal, #editStreamModal").on("shown.bs.modal", function(){
			let ID = $(this).attr('id');
			$('select').select2({minimumResultsForSearch: Infinity, dropdownParent: "#"+ID });
		});
	<?php endif; ?>

	<?php if($user_permissions['workers']['add_access'] === '1' || $user_permissions['users_groups']['add_access'] === '1' || $user_permissions['attendance']['add_access'] === '1'): ?>
	<?php if($user_permissions['workers']['add_access'] === '1'): ?>
		ajaxForm("#addWorkerForm", 'actions/add.php', true);

		$('.job_select').on("change", function(){
			let job_value = $('.job_select option[value="'+$(this).val()+'"]').text();

			if(job_value === 'Tikuvchi' || job_value === 'Тикувчи'){
				$('.stream_select').removeClass("d-none");
				$('.stream_select select').attr("name", 'group_id').attr('multiple', false).val('0').select2();
				$('.stream_select').find("label").text('<?php echo lot_kir("Potogi")?>');
			}else if(job_value === 'Bichuvchi' || job_value === 'Бичувчи'){
				$('.stream_select').removeClass("d-none");
				$('.stream_select select').attr("name", 'tailor_ids[]').attr('multiple', true).val('').select2();
				$('.stream_select').find("label").text('<?php echo lot_kir("Tikuvchilari")?>');
			}else{
				$('.stream_select').addClass("d-none");
				$('.stream_select select').removeAttr("name").attr('multiple', false).val('').select2();
			}
		});
		<?php endif; ?>

		<?php if($user_permissions['users_groups']['add_access'] === '1'): ?>
		ajaxForm("#addStreamForm", 'actions/add.php', true);
		<?php endif; ?>

		<?php if($user_permissions['attendance']['add_access'] === '1'): ?>
			ajaxForm("#addAttendaceForm", 'actions/add.php', true);

			$("#addAttendaceForm [type='checkbox']").on('change', function(){
				if($(this).prop("checked") === true){
					$(this).parent().parent().parent().find('.comment_input').addClass('d-none');
				}else{
					$(this).parent().parent().parent().find('.comment_input').removeClass('d-none');
					// $(this).parent().parent().parent().find('.comment_input input').focus();
				}

				if($("#addAttendaceForm .comment_input").not('.d-none').length > 0){
					$('#addAttendaceForm button[type="submit"]').prop('disabled', false);
				}else{
					$('#addAttendaceForm button[type="submit"]').prop('disabled', true);
				}
			});
		<?php endif; ?>
	<?php endif; ?>

	<?php if($user_permissions['workers']['edit_access'] === '1' || $user_permissions['users_groups']['edit_access'] === '1'): ?>
		<?php if($user_permissions['users_groups']['edit_access'] === '1'): ?>
			$('body').on("click", ".editBtn", function(){

				let table_name = $(this).data('table');

				if(table_name === 'users_groups'){
					let id = $(this).data('id');
					let title = $(this).data('title');
					let other_workhouse = $(this).data('other_workhouse');
					let type = $(this).data('type');
					let comment = $(this).data('comment');
					let active = $(this).data('active');

					$(".modal#editStreamModal .modal-header h2").html('<?php echo lot_kir("O'zgartirish") ?>');
					$("#editStreamForm [name='title']").val(title);
					$("#editStreamForm [name='comment']").val(comment);
					$("#editStreamForm [name='id']").val(id);

					if(active == '1'){$("#editStreamForm [name='active']").prop('checked', true);}else{$("#editStreamForm [name='active']").prop('checked', false);}
					if(other_workhouse == '1'){$("#editStreamForm [name='other_workhouse']").prop('checked', true);}else{$("#editStreamForm [name='other_workhouse']").prop('checked', false);}
				}
			});

			ajaxForm("#editStreamForm", 'actions/edit.php', true);
		<?php endif; ?>
		<?php if($show): ?>
			// ajaxForm("#editWorkerForm", 'actions/edit.php', true);
			// ajaxForm("#changeEmailForm", 'actions/edit.php', true);
			// ajaxForm("#changePasswordForm", 'actions/edit.php', true);
		<?php endif; ?>
	<?php endif; ?>

	<?php if($show): ?>
		$("#agree_deactivate").on("change", function(){
			if($(this).is(":checked")){
				$("#account_deactivate_form").find(":submit").prop("disabled", false);
			}else{
				$("#account_deactivate_form").find(":submit").prop("disabled", true);
			}
		});

		$("#account_deactivate_form").on('submit', function(e){

			e.preventDefault();
			let form_button = $(this);

			swal.fire({
				html: '<?php echo lot_kir("Ushbu ma'lumotni o'chirishni tasdiqlang!")?>',
				icon: "question",
				showCancelButton: true,
				customClass: {
					confirmButton: "btn btn-success m-btn m-btn--wide",
					cancelButton: "btn btn-danger m-btn m-btn--wide",
				},
				confirmButtonText: '<?php echo lot_kir("Tasdiqlash")?>',
				cancelButtonText: '<?php echo lot_kir("Bekor qilish")?>',
			})
			.then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: 'actions/delete.php',
						type: 'POST',
						data: new FormData(this),
						contentType: false,
						cache: false,
						processData: false,
						beforeSend: function() {
							$(form_button).attr('disabled', 'disabled');
							$(form_button).find('.indicator-label').addClass('d-none');
							$(form_button).find('.indicator-progress').addClass('d-block');
						},
						success: function (data) {
							$(form_button).attr('disabled', false);
							$(form_button).find('.indicator-label').removeClass('d-none');
							$(form_button).find('.indicator-progress').removeClass('d-block');
							if (data == "success") {
								swal.fire({
									title: '<?php echo lot_kir("Amaliyot muvoffaqiyatli yakunlandi") ?>',
									icon: "success",
									customClass: {confirmButton: "btn btn-success m-btn m-btn--wide",}
								})
								.then(function () {
									setTimeout(() => {
										window.location.href = 'workers';
									}, 100);
								});
							} else {
								swal.fire({
									html: data,
									icon: "error",
									customClass: {confirmButton: "btn btn-success m-btn m-btn--wide",}
								});
							}
						},
						error: function (data) {
							$(form_button).attr('disabled', false);
							$(form_button).find('.indicator-label').removeClass('d-none');
							$(form_button).find('.indicator-progress').removeClass('d-block');
							if (data.status === 403) {
								swal.fire({
									html: '<?php echo lot_kir("Sizda ushbu amaliyotni bajarish ruhsati mavjud emas!") ?>',
									icon: "error",
									customClass: {confirmButton: "btn btn-success m-btn m-btn--wide",}
								});
							} else if (data.status === 404) {
								swal.fire({
									html: wordsArr.notfoundpage,
									icon: "error",
									customClass: {confirmButton: "btn btn-success m-btn m-btn--wide",}
								});
							}
						},
					});
				}
			});
		});
	<?php endif; ?>
</script>

</body>
</html>