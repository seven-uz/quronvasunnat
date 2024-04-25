<?php

include 'core/index.php';

$page_title = lot_kir("Mahsulotlar");

include 'inc/head.php';

$first_day_this_month = date("Y-m-01", strtotime($now));

$header['title'] = lot_kir("Modellar");
$header['breadcrumb'] = [0 => ['val' => lot_kir("Asosiy sahifa"), 'link' => '/'], $header['title']];
$header['add_btn'] = '<div class="app-navbar-item ms-1 ms-md-3"><a data-bs-toggle="modal" data-bs-target="#addProductModal" class="btn btn-sm btn-primary"><i class="fas fa-plus pe-0 d-sm-none d-inline-block"></i><span class="d-sm-inline-block d-none">'.lot_kir("Model qo'shish").'</span></a></div>';

$expenses_amount_for_previous_month = $Query->getN("expenses e", [
	'fields' => 'SUM(IFNULL(e.qty, 1) * e.cost) as cost',
	'join' => [
		['table' => 'expenses_types et', 'on' => "et.id = e.type_id AND affect_cost_price = '1'"],
	],
	'where' => [
		['column' => 'YEAR(e.time)', 'value' => "'".date("Y", strtotime("-1 month", strtotime($first_day_this_month)))."'"],
		['column' => 'MONTH(e.time)', 'value' => "'".date("m", strtotime("-1 month", strtotime($first_day_this_month)))."'"],
	],
	'getOnlyRow' => 'cost'
])[0];

if(isset($_GET['not_active_models'])){
	$products_with_tannarx = $Query->getN("products", [
		'fields' => 'id, title, compound, season, reaper_1_price, reaper_2_price, reaper_helper_price, tailor_price, iron_man_price, selling_price, additional_expenses, info, photo, active',
		'order' => ['title'],
		'where' => [
			['column' => 'active', 'value' => "'0'"],
		],
	]);
}else{
	$products_with_tannarx = $Query->getN("products", [
		'fields' => 'id, title, compound, season, reaper_1_price, reaper_2_price, reaper_helper_price, tailor_price, iron_man_price, selling_price, additional_expenses, info, photo, active',
		'order' => ['title'],
		'where' => [
			['column' => 'active', 'value' => "'1'"],
		],
	]);
}

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

$products_arr = [];

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

	$products_arr[$val['id']] = [
		'title' => $val['title'],
		'photo' => $val['photo'],
		'season' => $val['season'],
		'compound' => $val['compound'],
		'selling_price' => $val['selling_price'],
		'reaper_1_price' => $val['reaper_1_price'],
		'reaper_2_price' => $val['reaper_2_price'],
		'reaper_helper_price' => $val['reaper_helper_price'],
		'tailor_price' => $val['tailor_price'],
		'iron_man_price' => $val['iron_man_price'],
		'additional_expenses' => $val['additional_expenses'],
		'info' => $val['info'],
		'active' => $val['active'],
		'tannarx' => $price_raw_materials+$price_services,
		'tannarx_with_expense' => $price_raw_materials+$price_services,
	];
}
unset($products_with_tannarx, $raw_materials_q);


include 'inc/begin_body.php';

?>

<div class="app-content flex-column-fluid">
	<div class="app-container container-fluid">
		<div class="card card-flush">
			<div class="table-responsive">
				<table class="table align-middle table-row-dashed" data-table="products" datatable="custom">
					<thead>
						<tr class="text-primary fw-bold text-uppercase">
							<th class="ps-5"><?php echo lot_kir("Mahsulot") ?></th>
							<th class="text-center"><?php echo lot_kir("Mavsumi") ?></th>
							<th class="text-center"><?php echo lot_kir("Tan narhi") ?></th>
							<!-- <th class="text-center"><?php echo lot_kir("Tan narhi (ikkilamchi harajat bilan)") ?></th> -->
							<th class="text-center"><?php echo lot_kir("Sotish narhi") ?></th>
							<th><?php echo lot_kir("Haqida") ?></th>
							<!-- <th class="text-center"><?php echo lot_kir("Aktivligi") ?></th> -->
							<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1' || $user_permissions[SCRIPTNAME]['delete_access'] === '1'): ?>
								<th class="text-end pe-5"><?php echo lot_kir("Amallar") ?></th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($products_arr as $key => $val) {
							echo '<tr data-id="'.$key.'">
								<td class="ps-5">
									<div class="d-flex align-items-center">
										<a class="symbol symbol-50px d-none d-md-block me-5">
											<span class="symbol-label" style="background-image:url(/assets/media/products/'.$val['photo'].');"></span>
										</a>
										<a class="text-gray-800 fs-5 fw-bold">'. lot_kir($val['title']) .'</a>
									</div>
								</td>
								<td align="center">'; if($val['season'] === 'summer') echo lot_kir("Yozgi"); elseif($val['season'] === 'winter') echo lot_kir("Qishki"); echo '</td>
								<td align="center" data-order="'.$val['tannarx'].'">'.nf($val['tannarx']).'</td>
								<td align="center" data-order="'.$val['selling_price'].'">'.nf($val['selling_price']).'</td>
								<td>'. $val['info'] .'</td>
								' . actionsFunction('editProductModal', [
									'data-id' => $key,
									'data-title' => $val['title'],
									'data-season' => $val['season'],
									'data-photo' => $val['photo'],
									'data-compound' => $val['compound'],
									'data-reaper_1_price' => $val['reaper_1_price'],
									'data-reaper_2_price' => $val['reaper_2_price'],
									'data-reaper_helper_price' => $val['reaper_helper_price'],
									'data-tailor_price' => $val['tailor_price'],
									'data-iron_man_price' => $val['iron_man_price'],
									'data-selling_price' => $val['selling_price'],
									'data-additional_expenses' => $val['additional_expenses'],
									'data-info' => $val['info'],
									'data-active' => $val['active'],
									'data-table' => 'products',
								], $user_permissions['products']).'
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
<!-- Add Product -->
<div class="modal fade" id="addProductModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Model qo'shish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="addProductForm">
					<div class="row align-items-center">
						<div class="col-sm-6 mb-sm-0 mb-5 text-center">
							<div class="image-input image-input-circle image-input-empty image-input-outline image-input-placeholder" data-kt-image-input="true">
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
						<div class="col-sm-6">
							<div class="form-floating mb-3">
								<input type="text" class="form-control form-control-sm form-control-solid" name="title" placeholder="<?php echo lot_kir("Model nomi") ?>" required />
								<label class="required"><?php echo lot_kir("Model nomi") ?></label>
							</div>
							<div class="form-floating mb-3">
								<input type="text" class="form-control form-control-sm form-control-solid mask_number" autocomplete="off" name="reaper_1_price" placeholder="<?php echo lot_kir('1-bichuvchi narhi') ?>" />
								<label class="required"><?php echo lot_kir('1-bichuvchi narhi')?></label>
							</div>
							<div class="form-floating mb-3">
								<input type="text" class="form-control form-control-sm form-control-solid mask_number" autocomplete="off" name="reaper_2_price" placeholder="<?php echo lot_kir('2-bichuvchi narhi') ?>" />
								<label class="required"><?php echo lot_kir('2-bichuvchi narhi')?></label>
							</div>
							<div class="form-floating mb-3">
								<input type="text" class="form-control form-control-sm form-control-solid mask_number" autocomplete="off" name="reaper_helper_price" placeholder="<?php echo lot_kir('Yordamchi bichuvchi narhi') ?>" />
								<label class="required"><?php echo lot_kir('Yordamchi bichuvchi narhi')?></label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control form-control-sm form-control-solid mask_number" autocomplete="off" name="tailor_price" placeholder="<?php echo lot_kir('Tikuvchi narhi') ?>" />
								<label class="required"><?php echo lot_kir('Tikuvchi narhi')?></label>
							</div>
						</div>
						<div class="col-sm-6 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control form-control-sm form-control-solid mask_number" autocomplete="off" name="iron_man_price" placeholder="<?php echo lot_kir('Dazmolchi narhi') ?>" />
								<label class="required"><?php echo lot_kir('Dazmolchi narhi')?></label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-floating mb-7">
								<input type="text" class="form-control form-control-sm form-control-solid mask_number" autocomplete="off" name="additional_expenses" placeholder="<?php echo lot_kir("Qo'shimcha harajatlar") ?>" />
								<label class="required"><?php echo lot_kir("Qo'shimcha harajatlar") ?></label>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-floating">
								<input type="text" class="form-control form-control-sm form-control-solid mask_number" autocomplete="off" name="selling_price" placeholder="<?php echo lot_kir('Sotish narhi') ?>" />
								<label class="required"><?php echo lot_kir('Sotish narhi')?></label>
							</div>
						</div>
					</div>
					<span class="separator separator-dashed mb-5"></span>
					<h5 class="mb-5"><?php echo lot_kir("Hom ashyo harajatlari") ?></h5>
					<div id="raw_material_add">
						<div class="form-group">
							<div data-repeater-list="raw_material">
								<div data-repeater-item>
									<div class="form-group row">
										<div class="col-6 mb-7">
											<select class="form-select form-select-solid form-select-sm select_content" data-hide-search="true" name="id" data-control="select2" data-placeholder="<?php echo lot_kir("Birini tanlang") ?>" data-dropdown-parent="#addProductModal" required>
											<option></option>
												<?php foreach ($raw_materials as $key => $val) {
												if($val['unit'] === 'kg') $unit = lot_kir("kg");else
												if($val['unit'] === 'meter') $unit = lot_kir("metr");else
												if($val['unit'] === 'qty') $unit = lot_kir("dona");
												echo '<option value="'.$val['id'].'">'.$val['title'].' ('.$unit.')</option>';
												} ?>
											</select>
										</div>
										<div class="col-6 mb-7 d-flex">
											<div class="form-input">
												<input type="text" class="form-control form-control-sm form-control-solid mask_number" autocomplete="off" name="qty" placeholder="<?php echo lot_kir("Miqdori")?>" required />
											</div>
											<a data-repeater-delete class="btn btn-sm btn-light-danger ms-3">
												<i class="ki-duotone ki-trash fs-5 pe-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group mb-5">
							<a href="javascript:;" data-repeater-create class="btn btn-sm btn-light-primary">
								<i class="ki-duotone ki-plus fs-3"></i><?php echo lot_kir("Qator qo'shish") ?>
							</a>
						</div>
					</div>
					<span class="separator separator-dashed mb-7"></span>
					<div class="form-floating mb-7">
						<textarea class="form-control form-control-solid" name="info" placeholder="Model haqida" style="height: 70px"></textarea>
						<label><?php echo lot_kir("Model haqida") ?></label>
					</div>
					<div class="d-flex justify-content-between">
						<div class="d-inline-block">
							<label class="form-check form-check-sm form-check-custom form-check-solid">
								<input class="form-check-input" type="checkbox" name="active" checked>
								<span class="form-check-label"><?php echo lot_kir("Aktiv") ?></span>
							</label>
						</div>
						<div class="d-flex">
							<div class="form-check form-check-custom form-check-solid me-5">
								<input class="form-check-input cursor-pointer" type="radio" name="season" value="summer" id="season_summer"/>
								<label class="form-check-label cursor-pointer" for="season_summer"><?php echo lot_kir("Yozgi") ?></label>
							</div>
							<div class="form-check form-check-custom form-check-solid">
								<input class="form-check-input cursor-pointer" type="radio" name="season" value="winter" id="season_winter"/>
								<label class="form-check-label cursor-pointer" for="season_winter"><?php echo lot_kir("Qishki") ?></label>
							</div>
						</div>
					</div>
					<div class="text-center pt-10">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
							<input type="hidden" name="table" value="products">
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
<!-- Edit Product -->
<div class="modal fade" id="editProductModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
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
				<form id="editProductForm">
					<div class="row align-items-center">
						<div class="col-sm-6 mb-sm-0 mb-5 text-center">
							<div class="image-input image-input-circle image-input-outline" data-kt-image-input="true" style="background-image: url(/assets/media/products/blank-image.svg)">
								<div class="image-input-wrapper w-150px h-150px"></div>
								<label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
									data-kt-image-input-action="change"
									data-bs-toggle="tooltip"
									data-bs-dismiss="click"
									title="<?php echo lot_kir("Rasmni o'zgartirish")?>">
										<i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>
										<input type="file" name="photo" accept=".png, .jpg, .jpeg" />
										<input type="hidden" name="photo_remove" />
								</label>
								<span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
								data-kt-image-input-action="cancel"
								data-bs-toggle="tooltip"
								data-bs-dismiss="click"
								title="<?php echo lot_kir("O'zgarishni bekor qilish")?>">
										<i class="ki-outline ki-cross fs-3"></i>
								</span>
								<span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
								data-kt-image-input-action="remove"
								data-bs-toggle="tooltip"
								data-bs-dismiss="click"
								title="<?php echo lot_kir("Rasmni o'chirish")?>">
										<i class="ki-outline ki-cross fs-3"></i>
								</span>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-floating mb-3">
								<input type="text" class="form-control form-control-sm form-control-solid" name="title" placeholder="<?php echo lot_kir("Model nomi") ?>" required />
								<label class="required"><?php echo lot_kir("Model nomi") ?></label>
							</div>
							<div class="form-floating mb-3">
								<input type="text" class="form-control form-control-sm form-control-solid mask_number" autocomplete="off" name="reaper_1_price" placeholder="<?php echo lot_kir('1-bichuvchi narhi') ?>" />
								<label class="required"><?php echo lot_kir('1-bichuvchi narhi')?></label>
							</div>
							<div class="form-floating mb-3">
								<input type="text" class="form-control form-control-sm form-control-solid mask_number" autocomplete="off" name="reaper_2_price" placeholder="<?php echo lot_kir('2-bichuvchi narhi') ?>" />
								<label class="required"><?php echo lot_kir('2-bichuvchi narhi')?></label>
							</div>
							<div class="form-floating mb-3">
								<input type="text" class="form-control form-control-sm form-control-solid mask_number" autocomplete="off" name="reaper_helper_price" placeholder="<?php echo lot_kir('Yordamchi bichuvchi narhi') ?>" />
								<label class="required"><?php echo lot_kir('Yordamchi bichuvchi narhi')?></label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control form-control-sm form-control-solid mask_number" autocomplete="off" name="tailor_price" placeholder="<?php echo lot_kir('Tikuvchi narhi') ?>" />
								<label class="required"><?php echo lot_kir('Tikuvchi narhi')?></label>
							</div>
						</div>
						<div class="col-sm-6 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control form-control-sm form-control-solid mask_number" autocomplete="off" name="iron_man_price" placeholder="<?php echo lot_kir('Dazmolchi narhi') ?>" />
								<label class="required"><?php echo lot_kir('Dazmolchi narhi')?></label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-floating mb-7">
								<input type="text" class="form-control form-control-sm form-control-solid mask_number" autocomplete="off" name="additional_expenses" placeholder="<?php echo lot_kir("Qo'shimcha harajatlar") ?>" />
								<label class="required"><?php echo lot_kir("Qo'shimcha harajatlar") ?></label>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-floating">
								<input type="text" class="form-control form-control-sm form-control-solid mask_number" autocomplete="off" name="selling_price" placeholder="<?php echo lot_kir('Sotish narhi') ?>" />
								<label class="required"><?php echo lot_kir('Sotish narhi')?></label>
							</div>
						</div>
					</div>
					<span class="separator separator-dashed mb-5"></span>
					<h5 class="mb-5"><?php echo lot_kir("Hom ashyo harajatlari") ?></h5>
					<div id="raw_material_edit">
						<div class="form-group">
							<div data-repeater-list="raw_material">
								<div data-repeater-item>
									<div class="form-group row">
										<div class="col-6 mb-7">
											<select class="form-select form-select-solid form-select-sm select_content" name="id" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Birini tanlang") ?>" data-dropdown-parent="#editProductModal" required>
											<option></option>
												<?php foreach ($raw_materials as $key => $val) {
												if($val['unit'] === 'kg') $unit = lot_kir("kg");else
												if($val['unit'] === 'meter') $unit = lot_kir("metr");else
												if($val['unit'] === 'qty') $unit = lot_kir("dona");
												echo '<option value="'.$val['id'].'">'.$val['title'].' ('.$unit.')</option>';
												} ?>
											</select>
										</div>
										<div class="col-6 mb-7 d-flex">
											<div class="form-input">
												<input type="text" class="form-control form-control-sm form-control-solid mask_number" autocomplete="off" name="qty" placeholder="<?php echo lot_kir("Miqdori")?>" required />
											</div>
											<a data-repeater-delete class="btn btn-sm btn-light-danger ms-3">
												<i class="ki-duotone ki-trash fs-5 pe-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group mb-5">
							<a href="javascript:;" data-repeater-create class="btn btn-sm btn-light-primary">
								<i class="ki-duotone ki-plus fs-3"></i><?php echo lot_kir("Qator qo'shish") ?>
							</a>
						</div>
					</div>
					<span class="separator separator-dashed my-5"></span>
					<div class="form-floating mb-7">
						<textarea class="form-control form-control-solid" name="info" placeholder="Model haqida" style="height: 70px"></textarea>
						<label><?php echo lot_kir("Model haqida") ?></label>
					</div>
					<div class="d-flex justify-content-between">
						<div class="d-inline-block">
							<label class="form-check form-check-sm form-check-custom form-check-solid">
								<input class="form-check-input" type="checkbox" name="active" checked>
								<span class="form-check-label"><?php echo lot_kir("Aktiv") ?></span>
							</label>
						</div>
						<div class="d-flex">
							<div class="form-check form-check-custom form-check-solid me-5">
								<input class="form-check-input cursor-pointer" type="radio" name="season" value="summer" id="edit_season_summer"/>
								<label class="form-check-label cursor-pointer" for="edit_season_summer"><?php echo lot_kir("Yozgi") ?></label>
							</div>
							<div class="form-check form-check-custom form-check-solid">
								<input class="form-check-input cursor-pointer" type="radio" name="season" value="winter" id="edit_season_winter"/>
								<label class="form-check-label cursor-pointer" for="edit_season_winter"><?php echo lot_kir("Qishki") ?></label>
							</div>
						</div>
					</div>
					<div class="text-center pt-5">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
							<input type="hidden" name="table" value="products">
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
<?php endif; ?>

<?php include 'inc/javascript.php'; ?>
<script src="assets/plugins/custom/formrepeater/formrepeater.bundle.js"></script>

<script>

	<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1' || $user_permissions[SCRIPTNAME]['delete_access'] === '1'): ?>
		$("body").on("change", ".modal .select_content", function(){
			setTimeout(() => {
				$(this).parent().next().find('input').focus();
			}, 10);
		});
	<?php endif; ?>

	<?php if($user_permissions[SCRIPTNAME]['add_access'] === '1'): ?>
	ajaxForm("#addProductForm", 'actions/add.php', true);

	let repeater = $('#raw_material_add').repeater({
		show: function () {
			$(this).slideDown();
			$('#raw_material_add select').select2({minimumResultsForSearch: Infinity});
			Inputmask({
				alias: "numeric",
				groupSeparator: " ",
				rightAlign: false,
			}).mask('#raw_material_add .mask_number');

		},
		hide: function (deleteElement) {
			$(this).slideUp(deleteElement);
		}
	});
	<?php endif; ?>

	<?php if($user_permissions[SCRIPTNAME]['view_access'] === '1'): ?>
		$('.table[datatable="custom"]').DataTable({
			language: {
				search: '',
				searchPlaceholder: "<?php echo lot_kir("Qidirish...");?>",
				emptyTable: "<?php echo lot_kir("Ma'lumot topilmadi!");?>"
			},
			buttons: [{extend:'print', text:'<i class="fas fa-print text-primary"></i>'}],
			"aaSorting": [],
			dom: "<'card-header border-bottom-0 align-items-center'<'card-title'<'d-flex align-items-center flex-column flex-sm-row'<'d-none d-sm-block'l>p>><'d-flex align-items-center'<'not_actives'>B>><'card-body pt-0 px-0 pb-0'<'table-responsive't>>"
		});
		<?php if(!isset($_GET['not_active_models'])){ ?>
			$(".not_actives").html(`<a href="products?not_active_models" class="btn btn-sm btn-secondary me-5"><?php echo lot_kir("Aktivmas modellarga o'tish")?></a>`);
		<?php } elseif(isset($_GET['not_active_models'])){ ?>
			$(".not_actives").html(`<a href="products" class="btn btn-sm btn-success me-5"><?php echo lot_kir("Aktiv modellarga o'tish")?></a>`);
		<?php } ?>
	<?php endif; ?>

	<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1'): ?>
	let repeater_edit = $('#raw_material_edit').repeater({
		show: function () {
			$(this).slideDown();
			$('#raw_material_edit select').select2({minimumResultsForSearch: Infinity});
			Inputmask({
				alias: "numeric",
				groupSeparator: " ",
				rightAlign: false,
			}).mask('#raw_material_edit .mask_number');
		},
		hide: function (deleteElement) {
			$(this).slideUp(deleteElement);
		}
	});

	$('body').on("click", ".editBtn", function(){

		let id = $(this).data('id');
		let title = $(this).data('title');
		let unit = $(this).data('unit');
		let season = $(this).data('season');
		let photo = $(this).data('photo');
		let compound = $(this).data('compound');
		let reaper_1_price = $(this).data('reaper_1_price');
		let reaper_2_price = $(this).data('reaper_2_price');
		let reaper_helper_price = $(this).data('reaper_helper_price');
		let tailor_price = $(this).data('tailor_price');
		let iron_man_price = $(this).data('iron_man_price');
		let selling_price = $(this).data('selling_price');
		let additional_expenses = $(this).data('additional_expenses');
		let info = $(this).data('info');
		let active = $(this).data('active');

		let raw_materials_list = [];
		Object.entries(compound).forEach((el, i) => {
			raw_materials_list.push({ 'id': el[0], 'qty': el[1]});
		});
		repeater_edit.setList(raw_materials_list);

		$(".modal#editProductModal .modal-header h2").html('<?php echo lot_kir("O'zgartirish") ?>: '+title);
		$("#editProductForm [name='title']").val(title);
		$("#editProductForm [name='reaper_1_price']").val(reaper_1_price);
		$("#editProductForm [name='reaper_2_price']").val(reaper_2_price);
		$("#editProductForm [name='reaper_helper_price']").val(reaper_helper_price);
		$("#editProductForm [name='tailor_price']").val(tailor_price);
		$("#editProductForm [name='iron_man_price']").val(iron_man_price);
		$("#editProductForm [name='selling_price']").val(selling_price);
		$("#editProductForm [name='additional_expenses']").val(additional_expenses);
		$("#editProductForm [name='info']").val(info);
		$("#editProductForm [name='id']").val(id);

		$("#editProductForm .image-input-wrapper").css('background-image', 'url(/assets/media/products/'+photo+')');

		$("#editProductForm select").select2();

		if(photo == 'blank-image.svg'){
			$('#editProductForm [data-kt-image-input-action="remove"]').addClass('d-none');
		}else{
			$('#editProductForm [data-kt-image-input-action="remove"]').removeClass('d-none');
		}

		if(active == '1'){$("#editProductForm [name='active']").prop('checked', true);}else{$("#editProductForm [name='active']").prop('checked', false);}
		if(season == ''){
			$("#editProductForm [name='season']").prop('checked', false);
		}else{
			$("#editProductForm [name='season'][value='"+season+"']").prop('checked', true);
		}
	});


	ajaxForm("#editProductForm", 'actions/edit.php', true);
	<?php endif; ?>

</script>

</body>
</html>