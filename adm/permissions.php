<?php

include 'core/index.php';

$page_title = lot_kir("Foydalanish ruhsatlari");

include 'inc/head.php';

$header['title'] = lot_kir("Ruhsatlar");
$header['breadcrumb'] = [0 => ['val' => lot_kir("Asosiy sahifa"), 'link' => '/'], $header['title']];
$header['add_btn'] = '<div class="app-navbar-item ms-1 ms-md-3"><a data-bs-toggle="modal" data-bs-target="#addPermissionModal" class="btn btn-sm btn-primary"><i class="fas fa-plus pe-0 d-sm-none d-inline-block"></i><span class="d-sm-inline-block d-none">'.lot_kir("Ruhsat qo'shish").'</span></a></div>';

$permissions = $Query->getN("permissions", [
	'fields' => 'id, title, value, type, active',
	'order' => ['title'],
]);


include 'inc/begin_body.php';
?>

<div class="app-content flex-column-fluid">
	<div class="app-container container-fluid">
		<div class="card">
			<div class="card-body p-0">
				<table class="table align-middle table-row-dashed" data-table="permissions">
					<thead>
						<tr class="text-primary fw-bold text-uppercase">
							<th class="text-center ps-5">№</th>
							<th><?php echo lot_kir("Nomi") ?></th>
							<th><?php echo lot_kir("Xususiyati") ?></th>
							<th class="text-center"><?php echo lot_kir("Aktivligi") ?></th>
							<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1' || $user_permissions[SCRIPTNAME]['delete_access'] === '1'): ?>
								<th class="text-end pe-5"><?php echo lot_kir("Amallar") ?></th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($permissions as $id => $row) {
							echo '<tr data-id="'.$row['id'].'">
								<td class="text-center ps-5">' . ++$permissions_num . '</td>
								<td>' . $row['title'] . '</td>
								<td>';
								if($row['type'] != ''){
									$permissions_string = '';
									foreach (json_decode($row['type']) as $key => $val) {
										if($val === 'view') {$badge_type = 'primary'; $badge_title = lot_kir("Ko'rish");} else
										if($val === 'add') {$badge_type = 'success'; $badge_title = lot_kir("Qo'shish");} else
										if($val === 'edit') {$badge_type = 'warning'; $badge_title = lot_kir("O'zgartirish");} else
										if($val === 'delete') {$badge_type = 'danger'; $badge_title = lot_kir("O'chirish");} else
										if($val === 'function') {$badge_type = 'info'; $badge_title = lot_kir("Bajarrish");}
										if($val === 'history') {$badge_type = 'dark'; $badge_title = lot_kir("Tarihini ko'rish");}
										if($val === 'recent_history') {$badge_type = 'info'; $badge_title = lot_kir("Qisman tarixini ko'rish");}

										echo '<span class="badge badge-light-'.$badge_type.' badge-lg me-2">'.$badge_title.'</span>';
									}
								}
								echo '</td><td align="center">';
								echo ($row['active'] == 1) ? ' <i class="far fa-check-circle text-success fs-1"></i>' : '<i class="far fa-check-circle text-secondary fs-1"></i>'; echo '</td>' .
								actionsFunction('editPermissionModal', [
									'data-id' => $row['id'],
									'data-title' => $row['title'],
									'data-value' => $row['value'],
									'data-type' => $row['type'],
									'data-active' => $row['active'],
									'edit_btn' => 'editPermission',
									'data-table' => 'permissions',
								], $user_permissions['permissions']).'
							</tr>';
						} ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php include 'inc/end_body.php'; ?>

<?php if($user_permissions[SCRIPTNAME]['add_access'] === '1'): ?>
<!-- Add permissions -->
<div class="modal fade" id="addPermissionModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-750px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Ruhsat qo'shish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="addPermissionForm" class="form">
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid" id="permission_add_title" name="title" placeholder="<?php echo lot_kir("Ruhsat nomini kiriting") ?>" />
						<label for="permission_add_title"><?php echo lot_kir("Ruhsat nomi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid" id="permission_add_value" name="value" placeholder="<?php echo lot_kir("Ruhsat qiymatini kiriting") ?>" />
						<label for="permission_add_value"><?php echo lot_kir("Ruhsat qiymati") ?></label>
					</div>
					<div class="form-floating mb-7">
						<select class="form-select form-select-solid" name="type[]" id="permission_add_type" data-allow-clear="true" data-close-on-select="false" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Ruhsat tur(lar)ini tanlang") ?>" multiple="multiple">
							<option value="view"><?php echo lot_kir("Ko'rish") ?></option>
							<option value="add"><?php echo lot_kir("Qo'shish") ?></option>
							<option value="edit"><?php echo lot_kir("O'zgartirish") ?></option>
							<option value="delete"><?php echo lot_kir("O'chirish") ?></option>
							<option value="history"><?php echo lot_kir("Tarihini ko'rish") ?></option>
							<option value="recent_history"><?php echo lot_kir("Qisman tarixini ko'rish") ?></option>
							<option value="function"><?php echo lot_kir("Bajarish") ?></option>
						</select>
						<label for="permission_add_type"><?php echo lot_kir("Ruhsat turi") ?></label>
						<input type="hidden" name="table" value="permissions">
					</div>
					<div class="col d-inline-block">
						<label class="form-check form-check-sm form-check-custom form-check-solid">
							<input class="form-check-input" type="checkbox" name="active">
							<span class="form-check-label"><?php echo lot_kir("Aktiv") ?></span>
						</label>
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
<!-- Edit permissions -->
<div class="modal fade" id="editPermissionModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-750px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Ruhsat qo'shish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="editPermissionForm" class="form">
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid" id="permission_edit_title" name="title" placeholder="<?php echo lot_kir("Ruhsat nomini kiriting") ?>" />
						<label for="permission_edit_title"><?php echo lot_kir("Ruhsat nomi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid" id="permission_edit_value" name="value" placeholder="<?php echo lot_kir("Ruhsat qiymatini kiriting") ?>" />
						<label for="permission_edit_value"><?php echo lot_kir("Ruhsat qiymati") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="hidden" name="table" value="permissions">
						<select class="form-select form-select-solid" name="type[]" id="permission_edit_type" data-allow-clear="true" data-close-on-select="false" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Ruhsat tur(lar)ini tanlang") ?>" multiple="multiple">
							<option value="view"><?php echo lot_kir("Ko'rish") ?></option>
							<option value="add"><?php echo lot_kir("Qo'shish") ?></option>
							<option value="edit"><?php echo lot_kir("O'zgartirish") ?></option>
							<option value="delete"><?php echo lot_kir("O'chirish") ?></option>
							<option value="history"><?php echo lot_kir("Tarihini ko'rish") ?></option>
							<option value="recent_history"><?php echo lot_kir("Qisman tarixini ko'rish") ?></option>
							<option value="function"><?php echo lot_kir("Bajarish") ?></option>
						</select>
						<label for="permission_edit_type"><?php echo lot_kir("Ruhsat turi") ?></label>
					</div>
					<input type="hidden" name="id" />
					<div class="col d-inline-block">
						<label class="form-check form-check-sm form-check-custom form-check-solid">
							<input class="form-check-input" type="checkbox" name="active">
							<span class="form-check-label"><?php echo lot_kir("Aktiv") ?></span>
						</label>
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

<?php include 'inc/javascript.php'; ?>

<script>

	<?php if($user_permissions[SCRIPTNAME]['add_access'] === '1'): ?>
		ajaxForm("#addPermissionForm", 'actions/add.php', true);
	<?php endif; ?>

	<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1'): ?>
	$('body').on("click", ".editPermission", function(){

		let id = $(this).data('id');
		let title = $(this).data('title');
		let value = $(this).data('value');
		let type = $(this).data('type');
		let active = $(this).data('active');

		$(".modal#editPermissionModal .modal-header h2").html('<?php echo lot_kir("Ruhsatni o'zgartirish") ?>: '+title);
		$("#editPermissionForm input[name='title']").val(title);
		$("#editPermissionForm input[name='value']").val(value);
		$("#editPermissionForm input[name='id']").val(id);

		if(active == '1'){
			$("#editPermissionForm input[name='active']").prop('checked', true);
		}else{
			$("#editPermissionForm input[name='active']").prop('checked', false);
		}

		$("#editPermissionForm select[name='type[]'] option").prop('selected', false);

		type.forEach((i, k) => {
			$("#editPermissionForm select[name='type[]'] option[value='"+i+"']").prop('selected', true);
		});

		$("#editPermissionForm select[name='type[]']").select2();

	});

	ajaxForm("#editPermissionForm", 'actions/edit.php', true);
	<?php endif; ?>

</script>

</body>
</html>