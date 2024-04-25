<?php

include 'core/index.php';

$page_title = lot_kir("Menu");

include 'inc/head.php';

$header['title'] = lot_kir("Asosiy menyu");
$header['breadcrumb'] = [0 => ['val' => lot_kir("Asosiy sahifa"), 'link' => '/'], $header['title']];

$menu_categories = $Query->getN("menu_categories", [
	'fields' => 'id, title, sort, active',
	'order' => ['sort'],
]);

$menu_parents = $Query->getN("menu_parents", [
	'fields' => 'id, title, sort, active',
	'order' => ['sort'],
]);

include 'inc/begin_body.php';
?>

<div class="app-content flex-column-fluid">
	<div class="app-container container-fluid">
		<?php if(DEVELOPING_MODE === true && $user_permissions[SCRIPTNAME]['add_access'] === '1'): ?>
			<div class="d-flex justify-content-between align-items-center mb-5">
				<h3 class="fw-bold"><?php echo lot_kir("Asosiy menyu ro'yhati") ?></h3>
				<div class="d-flex">
					<div class="app-navbar-item ms-1 ms-md-3"><a data-bs-toggle="modal" data-bs-target="#addMenuModal" class="btn btn-sm btn-primary"><?php echo lot_kir("Menyu qo'shish") ?></a></div>
					<div class="app-navbar-item ms-1 ms-md-3"><a data-bs-toggle="modal" data-bs-target="#categoriesModal" class="btn btn-sm btn-primary"><?php echo lot_kir("Bo'limlar") ?></a></div>
					<div class="app-navbar-item ms-1 ms-md-3"><a data-bs-toggle="modal" data-bs-target="#parentsModal" class="btn btn-sm btn-primary"><?php echo lot_kir("Qaramliklar") ?></a></div>
				</div>
			</div>
		<?php endif; ?>
		<div class="card card-flush">
			<div class="card-body p-0">
				<div class="table-responsive">
					<table class="table align-middle table-row-dashed" data-table="menu">
						<thead class=" my-5">
							<tr class="text-primary fw-bold text-uppercase">
								<th class="text-center ps-5">№</th>
								<th><?php echo lot_kir("Nomi") ?></th>
								<th><?php echo lot_kir("Bo'limi") ?></th>
								<th><?php echo lot_kir("Qaramligi") ?></th>
								<th><?php echo lot_kir("Havolasi") ?></th>
								<th class="text-center"><?php echo lot_kir("Aktiv") ?></th>
								<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1' || $user_permissions[SCRIPTNAME]['delete_access'] === '1'): ?>
									<th class="text-end pe-5"><?php echo lot_kir("Amallar") ?></th>
								<?php endif; ?>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($menu as $row) {
								echo '<tr data-id="'.$row['id'].'">
									<td class="text-center ps-5">' . $row['sort'] . '</td>
									<td><a href="'.$row['link'].'" target="_blank">' . $row['title'] . '</a></td>
									<td>' . $row['mc_title'] . '</td>
									<td>' . $row['mp_title'] . '</td>
									<td>' . $row['link'] . '</td>
									<td align="center">'; echo ($row['active'] === '1') ? ' <i class="far fa-check-circle text-success fs-1"></i>' : '<i class="far fa-check-circle text-secondary fs-1"></i>'; echo '</td>
									'.actionsFunction('editMenuModal', [
										'data-id' => $row['id'],
										'data-title' => $row['title'],
										'data-sort' => $row['sort'],
										'data-cat_id' => $row['cat_id'],
										'data-parent_id' => $row['parent_id'],
										'data-link' => $row['link'],
										'data-active' => $row['active'],
										'data-table' => 'menu',
									], $user_permissions['menu']).'
								</tr>';
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include 'inc/end_body.php'; ?>

<!-- Menu categories -->
<div class="modal fade" id="categoriesModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<div class="d-flex">
					<h2 class="fw-bold mb-0"><?php echo lot_kir("Menyu bo'limlari") ?></h2>
					<span data-bs-stacked-modal="#addCategoryModal" class="ms-2 badge badge-primary cursor-pointer"><?php echo lot_kir("Qo'shish") ?></span>
				</div>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<table class="table align-middle table-row-dashed" data-table="menu_categories">
					<thead>
						<tr class="text-primary fw-bold text-uppercase">
							<th class="text-center">№</th>
							<th><?php echo lot_kir("Nomi") ?></th>
							<th class="text-center"><?php echo lot_kir("Aktiv") ?></th>
							<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1' || $user_permissions[SCRIPTNAME]['delete_access'] === '1'): ?>
								<th class="text-end"><?php echo lot_kir("Amallar") ?></th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($menu_categories as $row) {
							echo '<tr data-id="'.$row['id'].'">
								<td align="center">' . $row['sort'] . '</td>
								<td>' . $row['title'] . '</td>
								<td align="center">'; echo ($row['active'] === '1') ? ' <i class="far fa-check-circle text-success fs-1"></i>' : '<i class="far fa-check-circle text-secondary fs-1"></i>'; echo '</td>
								'.actionsFunction('editCategoryModal', [
									'data-id' => $row['id'],
									'data-title' => $row['title'],
									'data-sort' => $row['sort'],
									'data-active' => $row['active'],
									'data-table' => 'menu_categories',
									'modal' => 'stacked',
								], $user_permissions['menu']).'
							</tr>';
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- Menu parents -->
<div class="modal fade" id="parentsModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<div class="d-flex">
					<h2 class="fw-bold mb-0"><?php echo lot_kir("Menyu qaramliklari") ?></h2>
					<span data-bs-stacked-modal="#addParentModal" class="ms-2 badge badge-primary cursor-pointer"><?php echo lot_kir("Qo'shish") ?></span>
				</div>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<table class="table align-middle table-row-dashed" data-table="menu_parents">
					<thead>
						<tr class="text-primary fw-bold text-uppercase">
							<th class="text-center">№</th>
							<th><?php echo lot_kir("Nomi") ?></th>
							<th class="text-center"><?php echo lot_kir("Aktiv") ?></th>
							<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1' || $user_permissions[SCRIPTNAME]['delete_access'] === '1'): ?>
								<th class="text-end pe-5"><?php echo lot_kir("Amallar") ?></th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($menu_parents as $row) {
							echo '<tr data-id="'.$row['id'].'">
								<td align="center">' . $row['sort'] . '</td>
								<td>' . $row['title'] . '</td>
								<td align="center">'; echo ($row['active'] === '1') ? ' <i class="far fa-check-circle text-success fs-1"></i>' : '<i class="far fa-check-circle text-secondary fs-1"></i>'; echo '</td>
								'.actionsFunction('editParentModal', [
									'data-id' => $row['id'],
									'data-title' => $row['title'],
									'data-sort' => $row['sort'],
									'data-active' => $row['active'],
									'data-table' => 'menu_parents',
									'modal' => 'stacked',
								], $user_permissions['menu']).'
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
<!-- Add Menu -->
<div class="modal fade" id="addMenuModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Menyu qo'shish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="addMenuForm">
					<div class="form-floating mb-7">
						<select class="form-select form-select-solid" name="sort" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Tartib raqami") ?>" required>
							<?php for ($i=1; $i <= count(array_column($menu, 'sort')) + 1; $i++) {
								echo '<option value="'.$i.'"'; if($i == count(array_column($menu, 'sort')) + 1) echo ' selected'; echo '>'.$i.'</option>';
							} ?>
						</select>
						<label class="required"><?php echo lot_kir("Tartib raqami") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid" name="title"placeholder="<?php echo lot_kir("Nomi") ?>" required />
						<label class="required"><?php echo lot_kir("Nomi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<select class="form-select form-select-solid" name="cat_id" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Bo'limi") ?>" required>
							<?php foreach ($menu_categories as $key => $val) {
								if($val['active'] != '1') continue;
								echo '<option value="'.$val['id'].'">'.lot_kir($val['title']).'</option>';
							} ?>
						</select>
						<label class="required"><?php echo lot_kir("Bo'limi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<select class="form-select form-select-solid" name="parent_id" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Qaramligi") ?>">
						<option><?php echo lot_kir("Qaramligi yo'q") ?></option>
							<?php foreach ($menu_parents as $key => $val) {
								if($val['active'] != '1') continue;
								echo '<option value="'.$val['id'].'">'.lot_kir($val['title']).'</option>';
							} ?>
						</select>
						<label><?php echo lot_kir("Qaramligi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid" name="link" placeholder="<?php echo lot_kir("Havolasi") ?>" required />
						<label class="required"><?php echo lot_kir("Havolasi") ?></label>
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
							<input type="hidden" name="table" value="menu">
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
<div class="modal fade" id="addCategoryModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
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
					<div class="form-group row mb-7">
						<div class="col-7">
							<span class="input-group-text border-0 cursor-default" id="category_sort_input"><?php echo lot_kir("Tartib raqami") ?></span>
						</div>
						<div class="col-5">
							<div class="position-relative w-100" id="add_category_sort" aria-describedby="category_sort_input" data-max="<?php echo (count(array_column($menu_categories, 'sort')) + 1);?>">
								<button type="button" class="btn btn-icon btn-active-color-gray-700 position-absolute translate-middle-y top-50 start-0" data-kt-dialer-control="decrease">
									<i class="ki-duotone ki-minus-square fs-2"><span class="path1"></span><span class="path2"></span></i>
								</button>
								<input type="text" class="form-control form-control-solid border-0 text-center" data-kt-dialer-control="input" name="sort" readonly value="<?php echo (count(array_column($menu_categories, 'sort')) + 1);?>" />
								<button type="button" class="btn btn-icon btn-active-color-gray-700 position-absolute translate-middle-y top-50 end-0" data-kt-dialer-control="increase">
									<i class="ki-duotone ki-plus-square fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
								</button>
							</div>
						</div>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid" name="title"placeholder="<?php echo lot_kir("Nomi") ?>" required />
						<label class="required"><?php echo lot_kir("Nomi") ?></label>
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
							<input type="hidden" name="table[]" value="menu">
							<input type="hidden" name="table[]" value="menu_categories">
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

<!-- Add Parent -->
<div class="modal fade" id="addParentModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Qaramlik qo'shish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="addParentForm">
					<div class="form-group row mb-7">
						<div class="col-7">
							<span class="input-group-text border-0 cursor-default" id="parent_sort_input"><?php echo lot_kir("Tartib raqami") ?></span>
						</div>
						<div class="col-5">
							<div class="position-relative w-100" id="add_parent_sort" aria-describedby="parent_sort_input" data-max="<?php echo (count(array_column($menu_categories, 'sort')) + 1);?>">
								<button type="button" class="btn btn-icon btn-active-color-gray-700 position-absolute translate-middle-y top-50 start-0" data-kt-dialer-control="decrease">
									<i class="ki-duotone ki-minus-square fs-2"><span class="path1"></span><span class="path2"></span></i>
								</button>
								<input type="text" class="form-control form-control-solid border-0 text-center" data-kt-dialer-control="input" name="sort" readonly value="<?php echo (count(array_column($menu_categories, 'sort')) + 1);?>" />
								<button type="button" class="btn btn-icon btn-active-color-gray-700 position-absolute translate-middle-y top-50 end-0" data-kt-dialer-control="increase">
									<i class="ki-duotone ki-plus-square fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
								</button>
							</div>
						</div>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid" name="title"placeholder="<?php echo lot_kir("Nomi") ?>" required />
						<label class="required"><?php echo lot_kir("Nomi") ?></label>
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
							<input type="hidden" name="table[]" value="menu">
							<input type="hidden" name="table[]" value="menu_parents">
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
<!-- Edit Menu -->
<div class="modal fade" id="editMenuModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Menyuni o'zgartirish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="editMenuForm">
					<div class="form-floating mb-7">
						<select class="form-select form-select-solid" name="sort" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Tartib raqami") ?>" required>
							<?php for ($i=1; $i <= count(array_column($menu, 'sort')); $i++) {
								echo '<option value="'.$i.'">'.$i.'</option>';
							} ?>
						</select>
						<label class="required"><?php echo lot_kir("Tartib raqami") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid" name="title"placeholder="<?php echo lot_kir("Nomi") ?>" required />
						<label class="required"><?php echo lot_kir("Nomi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<select class="form-select form-select-solid" name="cat_id" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Bo'limi") ?>" required>
							<?php foreach ($menu_categories as $key => $val) {
								if($val['active'] != '1') continue;
								echo '<option value="'.$val['id'].'">'.lot_kir($val['title']).'</option>';
							} ?>
						</select>
						<label class="required"><?php echo lot_kir("Bo'limi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<select class="form-select form-select-solid" name="parent_id" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Qaramligi") ?>">
							<option><?php echo lot_kir("Qaramligi yo'q") ?></option>
							<?php foreach ($menu_parents as $key => $val) {
								if($val['active'] != '1') continue;
								echo '<option value="'.$val['id'].'">'.lot_kir($val['title']).'</option>';
							} ?>
						</select>
						<label><?php echo lot_kir("Qaramligi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid" name="link" placeholder="<?php echo lot_kir("Havolasi") ?>" required />
						<label class="required"><?php echo lot_kir("Havolasi") ?></label>
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
							<input type="hidden" name="table" value="menu">
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
<div class="modal fade" id="editCategoryModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
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
					<div class="form-group row mb-7">
						<div class="col-7">
							<span class="input-group-text border-0 cursor-default" id="category_sort_input"><?php echo lot_kir("Tartib raqami") ?></span>
						</div>
						<div class="col-5">
							<div class="position-relative w-100" id="edit_category_sort" aria-describedby="category_sort_input" data-max="<?php echo (count(array_column($menu_categories, 'sort')) + 1);?>">
								<button type="button" class="btn btn-icon btn-active-color-gray-700 position-absolute translate-middle-y top-50 start-0" data-kt-dialer-control="decrease">
									<i class="ki-duotone ki-minus-square fs-2"><span class="path1"></span><span class="path2"></span></i>
								</button>
								<input type="text" class="form-control form-control-solid border-0 text-center" data-kt-dialer-control="input" name="sort" readonly />
								<button type="button" class="btn btn-icon btn-active-color-gray-700 position-absolute translate-middle-y top-50 end-0" data-kt-dialer-control="increase">
									<i class="ki-duotone ki-plus-square fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
								</button>
							</div>
						</div>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid" name="title"placeholder="<?php echo lot_kir("Nomi") ?>" required />
						<label class="required"><?php echo lot_kir("Nomi") ?></label>
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
							<input type="hidden" name="table[]" value="menu">
							<input type="hidden" name="table[]" value="menu_categories">
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

<!-- Edit Parent -->
<div class="modal fade" id="editParentModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Qaramlikni o'zgartirish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="editParentForm">
					<div class="form-group row mb-7">
						<div class="col-7">
							<span class="input-group-text border-0 cursor-default" id="parent_sort_input"><?php echo lot_kir("Tartib raqami") ?></span>
						</div>
						<div class="col-5">
							<div class="position-relative w-100" id="edit_parent_sort" aria-describedby="parent_sort_input" data-max="<?php echo (count(array_column($menu_categories, 'sort')) + 1);?>">
								<button type="button" class="btn btn-icon btn-active-color-gray-700 position-absolute translate-middle-y top-50 start-0" data-kt-dialer-control="decrease">
									<i class="ki-duotone ki-minus-square fs-2"><span class="path1"></span><span class="path2"></span></i>
								</button>
								<input type="text" class="form-control form-control-solid border-0 text-center" data-kt-dialer-control="input" name="sort" readonly />
								<button type="button" class="btn btn-icon btn-active-color-gray-700 position-absolute translate-middle-y top-50 end-0" data-kt-dialer-control="increase">
									<i class="ki-duotone ki-plus-square fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
								</button>
							</div>
						</div>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid" name="title"placeholder="<?php echo lot_kir("Nomi") ?>" required />
						<label class="required"><?php echo lot_kir("Nomi") ?></label>
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
							<input type="hidden" name="table[]" value="menu">
							<input type="hidden" name="table[]" value="menu_parents">
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

		let add_category_Element = document.querySelector("#add_category_sort");
		let add_category_max = add_category_Element.dataset.max;
		new KTDialer(add_category_Element, { min: 1, max: add_category_max, step: 1});

		let add_parent_Element = document.querySelector("#add_parent_sort");
		let add_parent_max = add_parent_Element.dataset.max;
		new KTDialer(add_parent_Element, { min: 1, max: add_parent_max, step: 1});

		ajaxForm("#addMenuForm", 'actions/add.php', true);
		ajaxForm("#addCategoryForm", 'actions/add.php', true);
		ajaxForm("#addParentForm", 'actions/add.php', true);
	<?php endif; ?>

	<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1'): ?>
		$('body').on("click", ".editBtn", function(){

			let table_name = $(this).data('table');

			if(table_name === 'menu_categories'){
				let id = $(this).data('id');
				let title = $(this).data('title');
				let sort = $(this).data('sort');
				let active = $(this).data('active');

				$(".modal#editCategoryModal .modal-header h2").html('<?php echo lot_kir("O'zgartirish") ?>: '+title);
				$("#editCategoryForm input[name='title']").val(title);
				$("#editCategoryForm input[name='sort']").val(sort);
				$("#editCategoryForm input[name='id']").val(id);

				if(active == '1'){
					$("#editCategoryForm input[name='active']").prop('checked', true);
				}else{
					$("#editCategoryForm input[name='active']").prop('checked', false);
				}
			}

			if(table_name === 'menu_parents'){
				let id = $(this).data('id');
				let title = $(this).data('title');
				let sort = $(this).data('sort');
				let active = $(this).data('active');

				$(".modal#editParentModal .modal-header h2").html('<?php echo lot_kir("O'zgartirish") ?>: '+title);
				$("#editParentForm input[name='title']").val(title);
				$("#editParentForm input[name='sort']").val(sort);
				$("#editParentForm input[name='id']").val(id);

				if(active == '1'){
					$("#editParentForm input[name='active']").prop('checked', true);
				}else{
					$("#editParentForm input[name='active']").prop('checked', false);
				}
			}

			if(table_name === 'menu'){
				let id = $(this).data('id');
				let title = $(this).data('title');
				let cat_id = $(this).data('cat_id');
				let parent_id = $(this).data('parent_id');
				let sort = $(this).data('sort');
				let link = $(this).data('link');
				let active = $(this).data('active');

				$(".modal#editMenuModal .modal-header h2").html('<?php echo lot_kir("Rolni o'zgartirish") ?>: '+title);
				$("#editMenuForm input[name='title']").val(title);
				$("#editMenuForm input[name='link']").val(link);
				$("#editMenuForm input[name='id']").val(id);

				$("#editMenuForm [name='sort'] option[value='"+sort+"']").prop('selected', true);
				$("#editMenuForm [name='cat_id'] option[value='"+cat_id+"']").prop('selected', true);
				$("#editMenuForm [name='parent_id'] option[value='"+parent_id+"']").prop('selected', true);


				if(active == '1'){
					$("#editMenuForm input[name='active']").prop('checked', true);
				}else{
					$("#editMenuForm input[name='active']").prop('checked', false);
				}
			}

			$(".modal select").select2({minimumResultsForSearch: Infinity});

		});

		let edit_category_Element = document.querySelector("#edit_category_sort");
		let edit_category_max = edit_category_Element.dataset.max;
		new KTDialer(edit_category_Element, { min: 1, max: edit_category_max, step: 1});

		let edit_parent_Element = document.querySelector("#edit_parent_sort");
		let edit_parent_max = edit_parent_Element.dataset.max;
		new KTDialer(edit_parent_Element, { min: 1, max: edit_parent_max, step: 1});

		ajaxForm("#editMenuForm", 'actions/edit.php', true);
		ajaxForm("#editCategoryForm", 'actions/edit.php', true);
		ajaxForm("#editParentForm", 'actions/edit.php', true);

	<?php endif; ?>

</script>
</body>
</html>