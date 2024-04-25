<?php

include 'core/index.php';

$page_title = lot_kir("Harajatlar");

include 'inc/head.php';

$header['title'] = lot_kir("Harajatlar");
$header['breadcrumb'] = [0 => ['val' => lot_kir("Asosiy sahifa"), 'link' => '/'], $header['title']];

$expenses = $Query->getN("expenses e", [
	'fields' => 'e.id, e.to_id, e.to_group_id, e.qty, e.type_id, e.cost, e.time, e.comment,
	c.title as c_title, t.title as t_title',
	'join' => [
		['table' => 'expenses_types t', 'on' => 't.id = e.type_id'],
		['table' => 'expenses_cats c', 'on' => 'c.id = t.cat_id'],
	],
	'order' => ['time desc'],
]);

$expenses_cats = $Query->getN("expenses_cats", [
	'fields' => 'id, title, active',
	'order' => ['title'],
]);

$expenses_types = $Query->getN("expenses_types t", [
	'fields' => 't.id, t.cat_id, t.affect_cost_price, t.title, t.active, c.title as c_title, t.color',
	'join' => [
		['table' => 'expenses_cats c', 'on' => 'c.id = t.cat_id'],
	],
	'order' => ['title desc'],
]);

$users_groups = $Query->getN("users_groups", [
	'fields' => 'user_group_id, title, type',
	'order' => ['title'],
	'where' => [
		['column' => 'active', 'value' => "'1'"],
	],
]);

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


include 'inc/begin_body.php';


?>

<div class="app-content flex-column-fluid">
	<div class="app-container container-fluid">
		<?php if($user_permissions[SCRIPTNAME]['add_access'] === '1'): ?>
			<div class="row d-flex justify-content-between align-items-center mb-5">
				<div class="col-md-4">
					<h3 class="fw-bold"><?php echo lot_kir("Harajatlar ro'yhati") ?></h3>
				</div>
				<div class="d-flex col-md-8 flex-md-end flex-sm-row flex-column">
					<div class="app-navbar-item ms-md-3 mb-sm-0 mb-3"><a data-bs-toggle="modal" data-bs-target="#addExpenseModal" class="btn btn-sm btn-primary w-100"><?php echo lot_kir("Harajat qo'shish") ?></a></div>
					<div class="app-navbar-item ms-sm-3 mb-sm-0 mb-3"><a data-bs-toggle="modal" data-bs-target="#catsModal" class="btn btn-sm btn-primary w-100"><?php echo lot_kir("Bo'limlar") ?></a></div>
					<div class="app-navbar-item ms-sm-3 mb-sm-0 mb-3"><a data-bs-toggle="modal" data-bs-target="#typesModal" class="btn btn-sm btn-primary w-100"><?php echo lot_kir("Turlar") ?></a></div>
				</div>
			</div>
		<?php endif; ?>
		<div class="card">
			<table class="table align-middle table-row-dashed" data-table="expenses" datatable="true">
				<thead>
					<tr class="text-primary fw-bold text-uppercase">
						<th class="text-center ps-5">№</th>
						<th class="text-center"><?php echo lot_kir("Vaqti") ?></th>
						<th><?php echo lot_kir("Harajat") ?></th>
						<th class="text-center"><?php echo lot_kir("Miqdori") ?></th>
						<th class="text-center"><?php echo lot_kir("Narhi") ?></th>
						<th><?php echo lot_kir("Izoh") ?></th>
						<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1' || $user_permissions[SCRIPTNAME]['delete_access'] === '1'): ?>
							<th class="text-end pe-5"><?php echo lot_kir("Amallar") ?></th>
						<?php endif; ?>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($expenses as $row) {
						echo '<tr data-id="'.$row['id'].'">
							<td class="text-center pe-0 ps-5">' . ++$expenses_num . '</td>
							<td class="text-center pe-0">' . dwt($row['time']) . '</td>
							<td>' . lot_kir($row['t_title']); echo $users[$row['to_id']] != '' ? ' - ' . lot_kir($users[$row['to_id']]['fio']) : ''; echo '</td>
							<td class="text-center pe-0" data-order="'.$row['qty'].'">' . nf($row['qty']) . '</td>
							<td class="text-center pe-0" data-order="'.$row['cost'].'">' . nf($row['cost']) . '</td>
							<td>' . $row['comment'] . '</td>
							'.actionsFunction('editExpenseModal', [
								'data-id' => $row['id'],
								'data-to_id' => $row['to_id'],
								'data-to_group_id' => $row['to_group_id'],
								'data-type_id' => $row['type_id'],
								'data-cat_id' => $row['cat_id'],
								'data-qty' => $row['qty'],
								'data-cost' => $row['cost'],
								'data-comment' => $row['comment'],
								'data-time' => date("d.m.Y H:i",strtotime($row['time'])),
								'data-table' => 'expenses',
							], $user_permissions['expenses']).'
						</tr>';
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php include 'inc/end_body.php'; ?>

<!-- Expense cats -->
<div class="modal fade" id="catsModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<div class="d-flex">
					<h2 class="fw-bold mb-0"><?php echo lot_kir("Harajat bo'limlari") ?></h2>
					<span data-bs-stacked-modal="#addCategoryModal" class="ms-2 badge badge-primary cursor-pointer"><?php echo lot_kir("Qo'shish") ?></span>
				</div>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body p-0">
				<table class="table align-middle table-row-dashed mt-5" data-table="expenses_cats">
					<thead>
						<tr class="text-primary fw-bold text-uppercase">
							<th class="text-center ps-5">№</th>
							<th><?php echo lot_kir("Nomi") ?></th>
							<th class="text-center"><?php echo lot_kir("Aktiv") ?></th>
							<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1' || $user_permissions[SCRIPTNAME]['delete_access'] === '1'): ?>
								<th class="text-end pe-5"><?php echo lot_kir("Amallar") ?></th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($expenses_cats as $row) {
							echo '<tr data-id="'.$row['id'].'">
								<td align="center">' . ++$cats_num . '</td>
								<td>' . lot_kir($row['title']) . '</td>
								<td align="center">'; echo ($row['active'] === '1') ? ' <i class="far fa-check-circle text-success fs-1"></i>' : '<i class="far fa-check-circle text-secondary fs-1"></i>'; echo '</td>
								'.actionsFunction('editCategoryModal', [
									'data-id' => $row['id'],
									'data-title' => $row['title'],
									'data-active' => $row['active'],
									'data-table' => 'expenses_cats',
									'modal' => 'stacked',
								], $user_permissions['expenses']).'
							</tr>';
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- Expense types -->
<div class="modal fade" id="typesModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-550px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<div class="d-flex">
					<h2 class="fw-bold mb-0"><?php echo lot_kir("Harajat qaramliklari") ?></h2>
					<span data-bs-stacked-modal="#addTypeModal" class="ms-2 badge badge-primary cursor-pointer"><?php echo lot_kir("Qo'shish") ?></span>
				</div>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body p-0">
				<table class="table align-middle table-row-dashed mt-5" data-table="expenses_types">
					<thead>
						<tr class="text-primary fw-bold text-uppercase">
							<th class="text-center ps-5">№</th>
							<th><?php echo lot_kir("Nomi") ?></th>
							<th><?php echo lot_kir("Bo'limi") ?></th>
							<th class="text-center"><?php echo lot_kir("Aktiv") ?></th>
							<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1' || $user_permissions[SCRIPTNAME]['delete_access'] === '1'): ?>
								<th class="text-end pe-5"><?php echo lot_kir("Amallar") ?></th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($expenses_types as $row) {
							echo '<tr data-id="'.$row['id'].'">
								<td class="text-center ps-5">' . ++$types_num . '</td>
								<td>' . lot_kir($row['title']) . '</td>
								<td>' . lot_kir($row['c_title']) . '</td>
								<td align="center">'; echo ($row['active'] === '1') ? ' <i class="far fa-check-circle text-success fs-1"></i>' : '<i class="far fa-check-circle text-secondary fs-1"></i>'; echo '</td>
								'.actionsFunction('editTypeModal', [
									'data-id' => $row['id'],
									'data-title' => $row['title'],
									'data-color' => $row['color'],
									'data-affect_cost_price' => $row['affect_cost_price'],
									'data-cat_id' => $row['cat_id'],
									'data-active' => $row['active'],
									'data-table' => 'expenses_types',
									'modal' => 'stacked',
								], $user_permissions['expenses']).'
							</tr>';
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<?php if($user_permissions[SCRIPTNAME]['add_access'] === '1'): ?>
<!-- Add Expense -->
<div class="modal fade" id="addExpenseModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Harajat qo'shish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="addExpenseForm">
					<div class="form-floating mb-7">
						<select class="form-select form-select-solid" name="type_id" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Xarajat turini tanlang") ?>" required>
							<option></option>
							<?php
							foreach ($expenses_cats as $k => $v) {
								if($v['active'] != '1') continue;
								echo '<optgroup label="' . lot_kir($v['title']) . '">';
								foreach ($expenses_types as $key => $val) {
									if($val['active'] != '1') continue;
									if ($val['cat_id'] == $v['id']) {
										echo '<option value="'.$val['id'].'">'.lot_kir($val['title']).'</option>';
									}
								}
								echo '</optgroup>';
							}
							 ?>
						</select>
						<label class="required"><?php echo lot_kir("Turi") ?></label>
					</div>
					<div class="form-floating mb-7 d-none">
						<select class="form-select form-select-solid to_id" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Ishchi") ?>" required>
							<?php foreach ($users as $value) {
								echo '<option value="'.$value['id'].'">'.lot_kir($value['fio']).'</option>';
							} ?>
						</select>
						<label class="required"><?php echo lot_kir("Ishchi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid mask_number qty" name="qty" placeholder="<?php echo lot_kir("Soni") ?>" />
						<label><?php echo lot_kir("Soni") ?></label=>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid mask_number" name="cost" placeholder="<?php echo lot_kir("Narhi") ?>" required />
						<label class="required"><?php echo lot_kir("Narhi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid time_format" name="time" placeholder="<?php echo lot_kir("Vaqti") ?>" value="<?php echo date("d.m.Y H:i", strtotime($now)) ?>" required readonly />
						<label class="required"><?php echo lot_kir("Vaqti") ?></label>
					</div>
					<div class="form-floating mb-7">
						<textarea class="form-control form-control-solid" name="comment" placeholder="Izoh" style="height: 100px"></textarea>
						<label><?php echo lot_kir("Izoh") ?></label>
					</div>
					<div class="text-center pt-5">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
							<input type="hidden" name="table" value="expenses">
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

<!-- Add Category -->
<div class="modal fade" id="addCategoryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
	<div class="modal-dialog modal-dialog modal-dialog-centered mw-550px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Bo'lim qo'shish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="addCategoryForm">
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid" name="title" placeholder="<?php echo lot_kir("Nomi") ?>" required />
						<label class="required"><?php echo lot_kir("Nomi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<div class="col d-inline-block">
							<label class="form-check form-check-sm form-check-custom form-check-solid">
								<input class="form-check-input" type="checkbox" name="active" checked>
								<span class="form-check-label"><?php echo lot_kir("Aktiv") ?></span>
							</label>
						</div>
					</div>
					<div class="text-center pt-5">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
							<input type="hidden" name="table[]" value="expenses">
							<input type="hidden" name="table[]" value="expenses_cats">
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

<!-- Add Type -->
<div class="modal fade" id="addTypeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Tur qo'shish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="addTypeForm">
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid" name="title" placeholder="<?php echo lot_kir("Nomi") ?>" required />
						<label class="required"><?php echo lot_kir("Nomi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<select class="form-select form-select-solid" name="cat_id" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Bo'limi") ?>" required>
							<?php foreach ($expenses_cats as $key => $val) {
								if($val['active'] != '1') continue;
								echo '<option value="'.$val['id'].'">'.lot_kir($val['title']).'</option>';
							} ?>
						</select>
						<label class="required"><?php echo lot_kir("Bo'limi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<?php echo lot_kir("Rangi") ?>
						<div class="d-flex justify-content-between pt-3">
							<div class="custom_radio">
								<label class="bg-primary" for="flexCheckbox_primary"></label>
								<input type="radio" name="color" value="primary" id="flexCheckbox_primary"/>
							</div>
							<div class="custom_radio">
								<label class="bg-dark" for="flexCheckbox_dark"></label>
								<input type="radio" name="color" value="dark" id="flexCheckbox_dark"/>
							</div>
							<div class="custom_radio">
								<label class="bg-success" for="flexCheckbox_success"></label>
								<input type="radio" name="color" value="success" id="flexCheckbox_success"/>
							</div>
							<div class="custom_radio">
								<label class="bg-info" for="flexCheckbox_info"></label>
								<input type="radio" name="color" value="info" id="flexCheckbox_info"/>
							</div>
							<div class="custom_radio">
								<label class="bg-warning" for="flexCheckbox_warning"></label>
								<input type="radio" name="color" value="warning" id="flexCheckbox_warning"/>
							</div>
							<div class="custom_radio">
								<label class="bg-danger" for="flexCheckbox_danger"></label>
								<input type="radio" name="color" value="danger" id="flexCheckbox_danger"/>
							</div>
						</div>
					</div>
					<div class="form-floating mb-7">
						<div class="col d-inline-block">
							<label class="form-check form-check-sm form-check-custom form-check-solid">
								<input class="form-check-input" type="checkbox" name="affect_cost_price" checked>
								<span class="form-check-label"><?php echo lot_kir("Tannarx ga ta'sir qilishi") ?></span>
							</label>
						</div>
					</div>
					<div class="form-floating mb-7">
						<div class="col d-inline-block">
							<label class="form-check form-check-sm form-check-custom form-check-solid">
								<input class="form-check-input" type="checkbox" name="active" checked>
								<span class="form-check-label"><?php echo lot_kir("Aktiv") ?></span>
							</label>
						</div>
					</div>
					<div class="text-center pt-5">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
							<input type="hidden" name="table[]" value="expenses">
							<input type="hidden" name="table[]" value="expenses_types">
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
<!-- Edit Expense -->
<div class="modal fade" id="editExpenseModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Harajatni o'zgartirish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="editExpenseForm">
					<div class="form-floating mb-7">
						<select class="form-select form-select-solid" name="type_id" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Turi") ?>" required>
							<?php
							foreach ($expenses_cats as $k => $v) {
								if($v['active'] != '1') continue;
								echo '<optgroup label="' . lot_kir($v['title']) . '">';
								foreach ($expenses_types as $key => $val) {
									if($val['active'] != '1') continue;
									if ($val['cat_id'] == $v['id']) {
										echo '<option value="'.$val['id'].'">'.lot_kir($val['title']).'</option>';
									}
								}
								echo '</optgroup>';
							}
							 ?>
						</select>
						<label class="required"><?php echo lot_kir("Turi") ?></label>
					</div>
					<div class="form-floating mb-7 d-none">
						<select class="form-select form-select-solid to_id" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Ishchi") ?>" required>
							<?php foreach ($users as $value) {
								echo '<option value="'.$value['id'].'">'.lot_kir($value['fio']).'</option>';
							} ?>
						</select>
						<label class="required"><?php echo lot_kir("Ishchi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid mask_number qty" name="qty" placeholder="<?php echo lot_kir("Soni") ?>" />
						<label><?php echo lot_kir("Soni") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid mask_number" name="cost" placeholder="<?php echo lot_kir("Narhi") ?>" required />
						<label class="required"><?php echo lot_kir("Narhi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid time_format" name="time" placeholder="<?php echo lot_kir("Vaqti") ?> "value="<?php echo date("d.m.Y H:i", strtotime($now)) ?>" required />
						<label class="required"><?php echo lot_kir("Vaqti") ?></label>
					</div>
					<div class="form-floating mb-7">
						<textarea class="form-control form-control-solid" name="comment" placeholder="Izoh" style="height: 100px"></textarea>
						<label><?php echo lot_kir("Izoh") ?></label>
					</div>
					<div class="text-center pt-5">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
							<input type="hidden" name="table" value="expenses">
							<input type="hidden" name="id" />
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

<!-- Edit Category -->
<div class="modal fade" id="editCategoryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-550px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Bo'limni o'zgartirish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="editCategoryForm">
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid" name="title" placeholder="<?php echo lot_kir("Nomi") ?>" required />
						<label class="required"><?php echo lot_kir("Nomi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<div class="col d-inline-block">
							<label class="form-check form-check-sm form-check-custom form-check-solid">
								<input class="form-check-input" type="checkbox" name="active">
								<span class="form-check-label"><?php echo lot_kir("Aktiv") ?></span>
							</label>
						</div>
					</div>
					<div class="text-center pt-5">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
							<input type="hidden" name="table[]" value="expenses">
							<input type="hidden" name="table[]" value="expenses_cats">
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

<!-- Edit Type -->
<div class="modal fade" id="editTypeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Turni o'zgartirish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="editTypeForm">
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid" name="title" placeholder="<?php echo lot_kir("Nomi") ?>" required />
						<label class="required"><?php echo lot_kir("Nomi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<select class="form-select form-select-solid" name="cat_id" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Bo'limi") ?>" required>
							<?php foreach ($expenses_cats as $key => $val) {
								if($val['active'] != '1') continue;
								echo '<option value="'.$val['id'].'">'.lot_kir($val['title']).'</option>';
							} ?>
						</select>
						<label class="required"><?php echo lot_kir("Bo'limi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<?php echo lot_kir("Rangi") ?>
						<div class="d-flex justify-content-between pt-3">
							<div class="custom_radio">
								<label class="bg-primary" for="flexCheckbox_primary"></label>
								<input type="radio" name="color" value="primary" id="flexCheckbox_primary"/>
							</div>
							<div class="custom_radio">
								<label class="bg-dark" for="flexCheckbox_dark"></label>
								<input type="radio" name="color" value="dark" id="flexCheckbox_dark"/>
							</div>
							<div class="custom_radio">
								<label class="bg-success" for="flexCheckbox_success"></label>
								<input type="radio" name="color" value="success" id="flexCheckbox_success"/>
							</div>
							<div class="custom_radio">
								<label class="bg-info" for="flexCheckbox_info"></label>
								<input type="radio" name="color" value="info" id="flexCheckbox_info"/>
							</div>
							<div class="custom_radio">
								<label class="bg-warning" for="flexCheckbox_warning"></label>
								<input type="radio" name="color" value="warning" id="flexCheckbox_warning"/>
							</div>
							<div class="custom_radio">
								<label class="bg-danger" for="flexCheckbox_danger"></label>
								<input type="radio" name="color" value="danger" id="flexCheckbox_danger"/>
							</div>
						</div>
					</div>
					<div class="form-floating mb-7">
						<div class="col d-inline-block">
							<label class="form-check form-check-sm form-check-custom form-check-solid">
								<input class="form-check-input" type="checkbox" name="affect_cost_price" checked>
								<span class="form-check-label"><?php echo lot_kir("Tannarx ga ta'sir qilishi") ?></span>
							</label>
						</div>
					</div>
					<div class="form-floating mb-7">
						<div class="col d-inline-block">
							<label class="form-check form-check-sm form-check-custom form-check-solid">
								<input class="form-check-input" type="checkbox" name="active" checked>
								<span class="form-check-label"><?php echo lot_kir("Aktiv") ?></span>
							</label>
						</div>
					</div>
					<div class="text-center pt-5">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
							<input type="hidden" name="table[]" value="expenses">
							<input type="hidden" name="table[]" value="expenses_types">
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

	$("body").on('change', '.custom_radio input', function(){
		$('.custom_radio label').removeClass('active');
		$('.custom_radio label[class="bg-'+this.value+'"]').addClass('active');
		$('.custom_radio input[value="'+this.value+'"]').prop('checked', true);
	});

	<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1' || $user_permissions[SCRIPTNAME]['delete_access'] === '1'): ?>
		$("#addTypeModal, #addExpenseModal, #editTypeModal, #editExpenseModal").on("shown.bs.modal", function(){
			let ID = $(this).attr('id');
			$('select').select2({minimumResultsForSearch: Infinity, dropdownParent: "#"+ID });
		});
	<?php endif; ?>

	<?php if($user_permissions[SCRIPTNAME]['add_access'] === '1'): ?>

		ajaxForm("#addExpenseForm", 'actions/add.php', true);
		ajaxForm("#addCategoryForm", 'actions/add.php', true);
		ajaxForm("#addTypeForm", 'actions/add.php', true);

		$("#addExpenseForm [name=type_id]").on("change", function(){
			let this_text = $("#addExpenseForm [name=type_id] option[value="+$(this).val()+"]").text();
			if(this_text === 'Oylik' || this_text === 'Ойлик'){
				$("#addExpenseForm .to_id").attr('name', 'to_id').parent().removeClass('d-none');
				$("#addExpenseForm .qty").removeAttr('name', 'qty').parent().addClass('d-none');
				$("#addExpenseForm [name=cost]").attr('placeholder', '<?php echo lot_kir("Summasi")?>').parent().find('label').html('<?php echo lot_kir("Summasi")?>');
			}else{
				$("#addExpenseForm .to_id").removeAttr('name', 'to_id').parent().addClass('d-none');
				$("#addExpenseForm .qty").attr('name', 'qty').parent().removeClass('d-none');
				$("#addExpenseForm [name=cost]").attr('placeholder', '<?php echo lot_kir("Narhi")?>').parent().find('label').html('<?php echo lot_kir("Narhi")?>');
			}
		});
	<?php endif; ?>

	<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1'): ?>
		$('body').on("click", ".editBtn", function(){

			let table_name = $(this).data('table');

			if(table_name === 'expenses_cats'){
				let id = $(this).data('id');
				let title = $(this).data('title');
				let sort = $(this).data('sort');
				let active = $(this).data('active');

				$(".modal#editCategoryModal .modal-header h2").html('<?php echo lot_kir("O'zgartirish") ?>: '+title);
				$("#editCategoryForm input[name='title']").val(title);
				$("#editCategoryForm input[name='sort']").val(sort);
				$("#editCategoryForm input[name='id']").val(id);

				if(active == '1'){ $("#editCategoryForm input[name='active']").prop('checked', true); }else{ $("#editCategoryForm input[name='active']").prop('checked', false); }
			}

			if(table_name === 'expenses_types'){
				let id = $(this).data('id');
				let title = $(this).data('title');
				let affect_cost_price = $(this).data('affect_cost_price');
				let color = $(this).data('color');
				let sort = $(this).data('sort');
				let active = $(this).data('active');
				console.log(color);

				$(".modal#editTypeModal .modal-header h2").html('<?php echo lot_kir("O'zgartirish") ?>: '+title);
				$("#editTypeForm input[name='title']").val(title);
				$("#editTypeForm .custom_radio label").removeClass('active');
				$("#editTypeForm .custom_radio label[class='bg-"+color+"']").addClass('active');
				$("#editTypeForm .custom_radio input[value='"+color+"']").prop('checked', true);
				$("#editTypeForm input[name='id']").val(id);

				if(active == '1'){ $("#editTypeForm input[name='active']").prop('checked', true); }else{ $("#editTypeForm input[name='active']").prop('checked', false); }
				if(affect_cost_price == '1'){ $("#editTypeForm input[name='affect_cost_price']").prop('checked', true); }else{ $("#editTypeForm input[name='affect_cost_price']").prop('checked', false); }
			}

			if(table_name === 'expenses'){

				let id = $(this).data('id');
				let qty = $(this).data('qty');
				let type_id = $(this).data('type_id');
				let to_id = $(this).data('to_id');
				let cost = $(this).data('cost');
				let time = $(this).data('time');
				let comment = $(this).data('comment');
				let active = $(this).data('active');

				$(".modal#editExpenseModal .modal-header h2").html('<?php echo lot_kir("O'zgartirish") ?>');
				$("#editExpenseForm [name='qty']").val(qty);
				$("#editExpenseForm [name='cost']").val(cost);
				$("#editExpenseForm [name='time']").val(time);
				$("#editExpenseForm [name='comment']").val(comment);
				$("#editExpenseForm [name='id']").val(id);

				$("#editExpenseForm [name='type_id'] option[value='"+type_id+"']").prop('selected', true);

				$("#editExpenseForm [name=type_id]").on("change", function(){
					let this_text = $("#editExpenseForm [name=type_id] option[value="+$(this).val()+"]").text();
					if(this_text === 'Oylik' || this_text === 'Ойлик'){
						$("#editExpenseForm .to_id").attr('name', 'to_id').parent().removeClass('d-none');
						$("#editExpenseForm .qty").removeAttr('name', 'qty').parent().addClass('d-none');
						$("#editExpenseForm [name=cost]").attr('placeholder', '<?php echo lot_kir("Summasi")?>').parent().find('label').html('<?php echo lot_kir("Summasi")?>');
					}else{
						$("#editExpenseForm .to_id").removeAttr('name', 'to_id').parent().addClass('d-none');
						$("#editExpenseForm .qty").attr('name', 'qty').parent().removeClass('d-none');
						$("#editExpenseForm [name=cost]").attr('placeholder', '<?php echo lot_kir("Narhi")?>').parent().find('label').html('<?php echo lot_kir("Narhi")?>');
					}
				});

				setTimeout(() => {
					if($("#editExpenseForm [name='type_id'] option[value='"+type_id+"']").text() === 'Oylik' || $("#editExpenseForm [name='type_id'] option[value='"+type_id+"']").text() === 'Ойлик'){
						$("#editExpenseForm .to_id").attr('name', 'to_id').parent().removeClass('d-none');
						$("#editExpenseForm .qty").removeAttr('name', 'qty').parent().addClass('d-none');
						$("#editExpenseForm [name=cost]").attr('placeholder', '<?php echo lot_kir("Summasi")?>').parent().find('label').html('<?php echo lot_kir("Summasi")?>');
					}else{
						$("#editExpenseForm .to_id").removeAttr('name', 'to_id').parent().addClass('d-none');
						$("#editExpenseForm .qty").attr('name', 'qty').parent().removeClass('d-none');
						$("#editExpenseForm [name=cost]").attr('placeholder', '<?php echo lot_kir("Narhi")?>').parent().find('label').html('<?php echo lot_kir("Narhi")?>');
					}
				}, 100);

				if(active == '1'){$("#editExpenseForm [name='active']").prop('checked', true);}else{$("#editExpenseForm [name='active']").prop('checked', false);}
			}
		});

		ajaxForm("#editExpenseForm", 'actions/edit.php', true);
		ajaxForm("#editCategoryForm", 'actions/edit.php', true);
		ajaxForm("#editTypeForm", 'actions/edit.php', true);

	<?php endif; ?>

</script>
</body>
</html>