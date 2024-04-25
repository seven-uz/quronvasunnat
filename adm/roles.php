<?php

include 'core/index.php';

$page_title = lot_kir("Rollar");

include 'inc/head.php';

$header['title'] = lot_kir("Rollar");
$header['breadcrumb'] = [0 => ['val' => lot_kir("Asosiy sahifa"), 'link' => '/'], $header['title']];
$header['add_btn'] = '<div class="app-navbar-item ms-1 ms-md-3"><a data-bs-toggle="modal" data-bs-target="#addRoleModal" class="btn btn-sm btn-primary"><i class="fas fa-plus pe-0 d-sm-none d-inline-block"></i><span class="d-sm-inline-block d-none">'.lot_kir("Rol qo'shish").'</span></a></div>';

$main_query = $Query->getN("roles r", [
	'fields' => '
	r.id, r.title, r.active, r.is_worker, r.worker_type, r.value,
	p.id as p_id, p.title as p_title, p.value as p_value,
	rp.id as rp_id, rp.permission_id, rp.view_access, rp.add_access, rp.edit_access, rp.delete_access, rp.function_access, rp.history_access, rp.recent_history_access, rp.deleted as rp_deleted, p.deleted as p_deleted',
	'join' => [
		['table' => 'role_permissions rp', 'on' => 'r.id = rp.role_id'],
		['table' => 'permissions p', 'on' => 'p.id = rp.permission_id'],
	],
	'order' => ['title'],
	'ignore' => ['join' => " AND rp.deleted <> '1' AND p.deleted <> '1'"],
]);

$permissions = $Query->getN("permissions p", [
	'fields' => 'p.id, p.title, p.value, p.type, p.active',
	'order' => ['p.title'],
]);

foreach ($main_query as $key => $val) {
	if($val['rp_deleted'] === '1' || $val['p_deleted']) continue;
	$roles[$val['id']]['title'] = $val['title'];
	$roles[$val['id']]['active'] = $val['active'];
	$roles[$val['id']]['is_worker'] = $val['is_worker'];
	$roles[$val['id']]['value'] = $val['value'];
	$roles[$val['id']]['worker_type'] = $val['worker_type'];

	if($val['p_id'] !== null){
		$roles[$val['id']]['permissions'][$val['p_id']] = [
			'id' => $val['p_id'],
			'title' => $val['p_title'],
			'value' => $val['p_value'],
		];

		$roles[$val['id']]['permissions'][$val['p_id']]['actions']['view'] = ($val['view_access'] === '1') ? '1' : '0';
		$roles[$val['id']]['permissions'][$val['p_id']]['actions']['add'] = ($val['add_access'] === '1') ? '1' : '0';
		$roles[$val['id']]['permissions'][$val['p_id']]['actions']['edit'] = ($val['edit_access'] === '1') ? '1' : '0';
		$roles[$val['id']]['permissions'][$val['p_id']]['actions']['delete'] = ($val['delete_access'] === '1') ? '1' : '0';
		$roles[$val['id']]['permissions'][$val['p_id']]['actions']['function'] = ($val['function_access'] === '1') ? '1' : '0';
		$roles[$val['id']]['permissions'][$val['p_id']]['actions']['history'] = ($val['history_access'] === '1') ? '1' : '0';
		$roles[$val['id']]['permissions'][$val['p_id']]['actions']['recent_history'] = ($val['recent_history_access'] === '1') ? '1' : '0';
	}
}

include 'inc/begin_body.php';
?>

<div class="app-content flex-column-fluid">
	<div class="app-container container-fluid">
		<div class="card">
			<div class="card-body p-0">
				<div class="table-responsive">
					<table class="table align-middle table-row-dashed" data-table="roles">
						<thead>
							<tr class="text-primary fw-bold text-uppercase">
								<th class="text-center ps-5">№</th>
								<th><?php echo lot_kir("Nomi") ?></th>
								<th><?php echo lot_kir("Ruhsatlar") ?></th>
								<th class="text-center"><?php echo lot_kir("Aktivligi") ?></th>
								<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1' || $user_permissions[SCRIPTNAME]['delete_access'] === '1'): ?>
									<th class="text-end pe-5"><?php echo lot_kir("Amallar") ?></th>
								<?php endif; ?>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($roles as $id => $row) {
								echo '<tr data-id="'.$id.'">
									<td class="text-center ps-5">' . ++$roles_num . '</td>
									<td>' . $row['title'] . '</td>
									<td>';
										if($row['permissions']){
											$role_permissions_string = [];
											foreach ($row['permissions'] as $key => $val) {
												if($val['actions']['view'] === '0' && $val['actions']['add'] === '0' && $val['actions']['edit'] === '0' && $val['actions']['delete'] === '0' && $val['actions']['function'] === '0') continue;

												if($val['actions']['view'] === '1' && $val['actions']['add'] === '1' && $val['actions']['edit'] === '1' && $val['actions']['delete'] === '1' && $val['actions']['function'] === '0') $badge_type = ''; else $badge_type = 'light-';
												$role_permissions_string[] = '<a href="'.$val['value'].'" target="_blank"><span class="badge badge-'.$badge_type.'primary badge-lg mb-md-0 mb-3">'.$val['title'].'</span></a>';
											}
											echo implode(' ', array_slice($role_permissions_string, 0, 5));
											if(count($role_permissions_string) > 5) echo '<span class="badge badge-warning badge-lg ms-sm-2 ms-0 mb-md-0 mb-3">... +'.(count($role_permissions_string) - 5).'</span>';
										}
									echo '</td>
									<td align="center">'; echo ($row['active'] == 1) ? ' <i class="far fa-check-circle text-success fs-1"></i>' : '<i class="far fa-check-circle text-secondary fs-1"></i>'; echo '</td>
									' . actionsFunction('editRoleModal', [
										'data-id' => $id,
										'data-title' => $row['title'],
										'data-active' => $row['active'],
										'data-is_worker' => $row['is_worker'],
										'data-worker_type' => $row['worker_type'],
										'data-value' => $row['value'],
										'data-table' => 'roles',
									], $user_permissions['roles']).'
								</tr>';
							} ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include 'inc/end_body.php'; ?>

<?php if($user_permissions[SCRIPTNAME]['add_access'] === '1'): ?>
<!-- Add role with permissions -->
<div class="modal fade" id="addRoleModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-900px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Rol qo'shish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body scroll-y me-lg-3">
				<form id="addRoleForm" class="form">
					<div class="d-flex flex-column scroll-y me-n7 pe-7" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-offset="300px">
						<div class="row mb-10 align-items-center">
							<div class="row align-items-center">
								<label class="fs-5 fw-bold form-label col-4">
									<span class="required"><?php echo lot_kir("Rol nomi") ?></span>
								</label>
								<div class="col-8">
									<input class="form-control form-control-solid" placeholder="<?php echo lot_kir("Rol nomini kiriting") ?>" name="title" />
								</div>
							</div>
							<div class="row align-items-center mt-5">
								<label class="fs-5 fw-bold form-label col-4">
									<span class="required"><?php echo lot_kir("Rol qiymati") ?></span>
								</label>
								<div class="col-8">
									<input class="form-control form-control-solid" placeholder="<?php echo lot_kir("Rol qiymatini kiriting") ?>" name="value" />
								</div>
							</div>
							<div class="row align-items-center mt-5">
								<label class="fs-5 fw-bold form-label col-4">
									<span><?php echo lot_kir("Sozlamalar") ?></span>
								</label>
								<div class="col-8">
									<div class="d-flex flex-sm-row flex-column">
										<label class="form-check form-check-sm form-check-custom form-check-solid mb-sm-0 mb-3">
											<input class="form-check-input cursor-pointer" type="checkbox" name="active" />
											<span class="form-check-label cursor-pointer"><?php echo lot_kir("Aktiv") ?></span>
										</label>
										<label class="form-check form-check-sm form-check-custom form-check-solid ms-sm-10 mb-sm-0 mb-3">
											<input class="form-check-input cursor-pointer" type="checkbox" name="is_worker" />
											<span class="form-check-label cursor-pointer"><?php echo lot_kir("Ishchi hodim") ?></span>
										</label>
										<div class="d-flex align-items-center ms-sm-10 mb-sm-0 mb-3">
											<label class="me-3 text-nowrap">
												<span class="form-check-label cursor-pointer"><?php echo lot_kir("Ishchi turi") ?></span>
											</label>
											<select class="form-select form-select-solid form-select-sm" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Ishchi turi") ?>" name="worker_type">
												<option value="0"><?php echo lot_kir("Tanlanmagan") ?></option>
												<option value="main"><?php echo lot_kir("Asosiy")?></option>
												<option value="helper"><?php echo lot_kir("Yordamchi")?></option>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="fv-row">
							<div class="d-flex">
								<h4 class="fs-5 fw-bold form-label mb-2">
									<?php echo lot_kir("Rol ruhsatlari") ?>
								</h4>
								<label class="form-check form-check-sm form-check-custom form-check-solid d-block text-end select_all_inputs ms-5">
									<input type="hidden" name="table[]" value="roles" />
									<input type="hidden" name="table[]" value="role_permissions" />
									<input class="form-check-input cursor-pointer" type="checkbox" data-content=".permissions_table" />
									<span class="form-check-label cursor-pointer"><?php echo lot_kir("Barchasini tanlash") ?></span>
								</label>
							</div>
							<div class="table-responsive">
								<table class="table align-middle table-row-dashed permissions_table">
									<tbody>
										<?php foreach ($permissions as $key => $value) {
											if($value['active'] !== '1') continue;
											echo '<tr>
												<td class="text-gray-800">'.$value['title'].'</td>';
												echo '<td>'; if(json_decode($value['type']) !== null && in_array('view', json_decode($value['type']))) echo '<label class="form-check form-check-sm form-check-custom form-check-solid"><input class="form-check-input" type="checkbox" name="permission['.$value['id'].'][view]" /><span class="form-check-label">'. lot_kir("Ko'rish") .'</span></label>'; echo '</td>';
												echo '<td>'; if(json_decode($value['type']) !== null && in_array('add', json_decode($value['type']))) echo '<label class="form-check form-check-sm form-check-custom form-check-success form-check-solid"><input class="form-check-input" type="checkbox" name="permission['.$value['id'].'][add]" /><span class="form-check-label">'. lot_kir("Qo'shish") .'</span></label>'; echo '</td>';
												echo '<td>'; if(json_decode($value['type']) !== null && in_array('edit', json_decode($value['type']))) echo '<label class="form-check form-check-sm form-check-custom form-check-warning form-check-solid"><input class="form-check-input" type="checkbox" name="permission['.$value['id'].'][edit]" /><span class="form-check-label">'. lot_kir("O'zgartirish") .'</span></label>'; echo '</td>';
												echo '<td>'; if(json_decode($value['type']) !== null && in_array('delete', json_decode($value['type']))) echo '<label class="form-check form-check-sm form-check-custom form-check-danger form-check-solid"><input class="form-check-input" type="checkbox" name="permission['.$value['id'].'][delete]" /><span class="form-check-label">'. lot_kir("O'chirish") .'</span></label>'; echo '</td>';
												echo '<td>'; if(json_decode($value['type']) !== null && in_array('history', json_decode($value['type']))) echo '<label class="form-check form-check-sm form-check-custom form-check-dark form-check-solid"><input class="form-check-input" type="checkbox" name="permission['.$value['id'].'][history]" /><span class="form-check-label">'. lot_kir("Tarihini ko'rish") .'</span></label>'; echo '</td>';
												echo '<td>'; if(json_decode($value['type']) !== null && in_array('recent_history', json_decode($value['type']))) echo '<label class="form-check form-check-sm form-check-custom form-check-info form-check-solid"><input class="form-check-input" type="checkbox" name="permission['.$value['id'].'][recent_history]" /><span class="form-check-label">'. lot_kir("Qisman tarixini ko'rish") .'</span></label>'; echo '</td>';
												echo '<td>'; if(json_decode($value['type']) !== null && in_array('function', json_decode($value['type']))) echo '<label class="form-check form-check-sm form-check-custom form-check-info form-check-solid"><input class="form-check-input" type="checkbox" name="permission['.$value['id'].'][function]" /><span class="form-check-label">'. lot_kir("Bajarish") .'</span></label>'; echo '</td>';
											echo '</tr>';
										} ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="text-center pt-5">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
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

<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1'): ?>
<!-- Edit role with permissions -->
<div class="modal fade" id="editRoleModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-900px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body scroll-y me-lg-3">
				<form id="editRoleForm" class="form">
					<div class="d-flex flex-column scroll-y me-n7 pe-7" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-offset="300px">
						<div class="row mb-10 align-items-center">
							<div class="row align-items-center">
								<label class="fs-5 fw-bold form-label col-4">
									<span class="required"><?php echo lot_kir("Rol nomi") ?></span>
								</label>
								<div class="col-8">
									<input class="form-control form-control-solid" placeholder="<?php echo lot_kir("Rol nomini kiriting") ?>" name="title" />
								</div>
							</div>
							<div class="row align-items-center mt-5">
								<label class="fs-5 fw-bold form-label col-4">
									<span class="required"><?php echo lot_kir("Rol qiymati") ?></span>
								</label>
								<div class="col-8">
									<input class="form-control form-control-solid" placeholder="<?php echo lot_kir("Rol qiymatini kiriting") ?>" name="value" />
								</div>
							</div>
							<div class="row align-items-center mt-5">
								<label class="fs-5 fw-bold form-label col-4">
									<span><?php echo lot_kir("Sozlamalar") ?></span>
								</label>
								<div class="col-8">
									<div class="d-flex flex-sm-row flex-column">
										<label class="form-check form-check-sm form-check-custom form-check-solid mb-sm-0 mb-3">
											<input class="form-check-input cursor-pointer" type="checkbox" name="active" />
											<span class="form-check-label cursor-pointer"><?php echo lot_kir("Aktiv") ?></span>
										</label>
										<label class="form-check form-check-sm form-check-custom form-check-solid ms-sm-10 mb-sm-0 mb-3">
											<input class="form-check-input cursor-pointer" type="checkbox" name="is_worker" />
											<span class="form-check-label cursor-pointer"><?php echo lot_kir("Ishchi hodim") ?></span>
										</label>
										<div class="d-flex align-items-center ms-sm-10 mb-sm-0 mb-3">
											<label class="me-3 text-nowrap">
												<span class="form-check-label cursor-pointer"><?php echo lot_kir("Ishchi turi") ?></span>
											</label>
											<select class="form-select form-select-solid form-select-sm" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Ishchi turi") ?>" name="worker_type">
												<option value="0"><?php echo lot_kir("Tanlanmagan") ?></option>
												<option value="main"><?php echo lot_kir("Asosiy")?></option>
												<option value="helper"><?php echo lot_kir("Yordamchi")?></option>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="fv-row">
							<div class="d-flex">
								<h4 class="fs-5 fw-bold form-label mb-2">
									<?php echo lot_kir("Rol ruhsatlari") ?>
								</h4>
								<input type="hidden" name="table[]" value="roles" />
								<input type="hidden" name="table[]" value="role_permissions" />
							</div>
							<div class="table-responsive">
								<table class="table align-middle table-row-dashed permissions_table">
									<tbody>
										<?php foreach ($permissions as $key => $value) {
											if($value['active'] !== '1') continue;
											echo '<tr>
												<td class="text-gray-800">'.$value['title'].'</td>';
												echo '<td>'; if(json_decode($value['type']) !== null && in_array('view', json_decode($value['type']))) echo '<label class="form-check form-check-sm form-check-custom form-check-solid"><input class="form-check-input" type="checkbox" name="permission['.$value['id'].'][view]" /><span class="form-check-label">'. lot_kir("Ko'rish") .'</span></label>'; echo '</td>';
												echo '<td>'; if(json_decode($value['type']) !== null && in_array('add', json_decode($value['type']))) echo '<label class="form-check form-check-sm form-check-custom form-check-success form-check-solid"><input class="form-check-input" type="checkbox" name="permission['.$value['id'].'][add]" /><span class="form-check-label">'. lot_kir("Qo'shish") .'</span></label>'; echo '</td>';
												echo '<td>'; if(json_decode($value['type']) !== null && in_array('edit', json_decode($value['type']))) echo '<label class="form-check form-check-sm form-check-custom form-check-warning form-check-solid"><input class="form-check-input" type="checkbox" name="permission['.$value['id'].'][edit]" /><span class="form-check-label">'. lot_kir("O'zgartirish") .'</span></label>'; echo '</td>';
												echo '<td>'; if(json_decode($value['type']) !== null && in_array('delete', json_decode($value['type']))) echo '<label class="form-check form-check-sm form-check-custom form-check-danger form-check-solid"><input class="form-check-input" type="checkbox" name="permission['.$value['id'].'][delete]" /><span class="form-check-label">'. lot_kir("O'chirish") .'</span></label>'; echo '</td>';
												echo '<td>'; if(json_decode($value['type']) !== null && in_array('history', json_decode($value['type']))) echo '<label class="form-check form-check-sm form-check-custom form-check-dark form-check-solid"><input class="form-check-input" type="checkbox" name="permission['.$value['id'].'][history]" /><span class="form-check-label">'. lot_kir("Tarihini ko'rish") .'</span></label>'; echo '</td>';
												echo '<td>'; if(json_decode($value['type']) !== null && in_array('recent_history', json_decode($value['type']))) echo '<label class="form-check form-check-sm form-check-custom form-check-info form-check-solid"><input class="form-check-input" type="checkbox" name="permission['.$value['id'].'][recent_history]" /><span class="form-check-label">'. lot_kir("Qisman tarixini ko'rish") .'</span></label>'; echo '</td>';
												echo '<td>'; if(json_decode($value['type']) !== null && in_array('function', json_decode($value['type']))) echo '<label class="form-check form-check-sm form-check-custom form-check-info form-check-solid"><input class="form-check-input" type="checkbox" name="permission['.$value['id'].'][function]" /><span class="form-check-label">'. lot_kir("Bajarish") .'</span></label>'; echo '</td>';
												echo '<input type="hidden" name="permission['.$value['id'].'][p]">';
											echo '</tr>';
										} ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="text-center pt-5">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
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

<script>

	<?php if($user_permissions[SCRIPTNAME]['add_access'] === '1'): ?>
	$('#addRoleModal').on('shown.bs.modal', function (e) {
		$('#addRoleModal input').prop('checked', false);
	});

	ajaxForm("#addRoleForm", 'actions/add.php', true);
	<?php endif; ?>

	<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1'): ?>

	$('body').on("click", ".editBtn", function(){

		const roles = <?php echo json_encode($roles)?>;

		let role_id = $(this).data('id');
		let title = $(this).data('title');
		let value = $(this).data('value');
		let active = $(this).data('active');
		let is_worker = $(this).data('is_worker');
		let worker_type = $(this).data('worker_type');

		$(".modal#editRoleModal .modal-header h2").html('<?php echo lot_kir("Rolni o'zgartirish") ?>: '+title);
		$("#editRoleForm input[name='title']").val(title);
		$("#editRoleForm input[name='value']").val(value);
		if(worker_type){
			$("#editRoleForm [name='worker_type'] option[value="+worker_type+"]").prop('selected', true);
		}else{
			$("#editRoleForm [name='worker_type'] :selected").prop('selected', false);
		}
		$("#editRoleForm input[name='id']").val(role_id);

		if(active == '1'){$("#editRoleForm input[name='active']").prop('checked', true);}else{$("#editRoleForm input[name='active']").prop('checked', false);}
		if(is_worker == '1'){$("#editRoleForm input[name='is_worker']").prop('checked', true);}else{$("#editRoleForm input[name='is_worker']").prop('checked', false);}

		if(roles[role_id].permissions !== undefined) {
			Object.entries(roles[role_id].permissions).forEach(([i0, k0]) => {
				if(k0.actions.view === '1'){ $('.permissions_table input[name="permission['+k0.id+'][view]"]').prop('checked', true); }
				if(k0.actions.add === '1'){ $('.permissions_table input[name="permission['+k0.id+'][add]"]').prop('checked', true); }
				if(k0.actions.edit === '1'){ $('.permissions_table input[name="permission['+k0.id+'][edit]"]').prop('checked', true); }
				if(k0.actions.delete === '1'){ $('.permissions_table input[name="permission['+k0.id+'][delete]"]').prop('checked', true); }
				if(k0.actions.function === '1'){ $('.permissions_table input[name="permission['+k0.id+'][function]"]').prop('checked', true); }
				if(k0.actions.history === '1'){ $('.permissions_table input[name="permission['+k0.id+'][history]"]').prop('checked', true); }
				if(k0.actions.recent_history === '1'){ $('.permissions_table input[name="permission['+k0.id+'][recent_history]"]').prop('checked', true); }
			});
		}else{
			$('.permissions_table input').prop('checked', false);
		}

		$("#editRoleForm select").select2({minimumResultsForSearch: Infinity});

	});

	$(".select_all_inputs input").on("change", function(){

		let content = $(this).data('content');

		if($(this).is(':checked')){
			$(content).find('input').prop('checked', true);
		}else{
			$(content).find('input').prop('checked', false);
		}

	});

	ajaxForm("#editRoleForm", 'actions/edit.php', true);
	<?php endif; ?>

	<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1' || $user_permissions[SCRIPTNAME]['delete_access'] === '1'): ?>
	$('.permissions_table input').on("change", function(){
		if($(".select_all_inputs input").is(":checked") === true || $(".select_all_inputs input").is(":indeterminate") === true){
			if ($('.permissions_table input:checked').length == $('.permissions_table input').length) {
				$(".select_all_inputs input").prop("indeterminate", false);
			}else{
				$(".select_all_inputs input").prop("indeterminate", true);
			}
		}
	});
	<?php endif; ?>

</script>

</body>
</html>