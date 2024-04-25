<?php

include 'core/index.php';

$page_title = lot_kir("Hom ashyolar");

include 'inc/head.php';

$header['title'] = lot_kir("Hom ashyo");
$header['breadcrumb'] = [0 => ['val' => lot_kir("Asosiy sahifa"), 'link' => '/'], $header['title']];
$header['add_btn'] = '<div class="app-navbar-item ms-1 ms-md-3"><a data-bs-toggle="modal" data-bs-target="#addRaw_materialModal" class="btn btn-sm btn-primary"><i class="fas fa-plus pe-0 d-sm-none d-inline-block"></i><span class="d-sm-inline-block d-none">'.lot_kir("Hom ashyo qo'shish").'</span></a></div>';

$raw_materials = $Query->getN("raw_materials rw", [
	'fields' => 'rw.id, rw.title, rw.unit, rw.photo, rw.info, rw.active',
	'order' => ['rw.title'],
	'group' => 'rw.id',
]);

include 'inc/begin_body.php';

?>

<div class="app-content flex-column-fluid">
	<div class="app-container container-fluid">
		<div class="card card-flush">
			<table class="table align-middle table-row-dashed" data-table="raw_materials" datatable="true">
				<thead>
					<tr class="text-primary fw-bold text-uppercase">
						<th class="ps-5"><?php echo lot_kir("Hom ashyo") ?></th>
						<th class="text-center"><span class="d-md-none"><?php echo lot_kir("O'l. bir.") ?></span><span class="d-none d-md-block"><?php echo lot_kir("O'lchov birligi") ?></span></th>
						<th><?php echo lot_kir("Izoh") ?></th>
						<th class="text-center"><?php echo lot_kir("Aktivligi") ?></th>
						<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1' || $user_permissions[SCRIPTNAME]['delete_access'] === '1'): ?>
							<th class="text-end pe-5"><?php echo lot_kir("Amallar") ?></th>
						<?php endif; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($raw_materials as $key => $val) {
						if($val['unit'] === 'meter') $unit = lot_kir("Metr");else
						if($val['unit'] === 'qty') $unit = lot_kir("Dona");else
						if($val['unit'] === 'kg') $unit = lot_kir("KG");

						echo '<tr data-id="'.$val['id'].'">
							<td class="ps-5">
								<div class="d-flex align-items-center">
									<a class="symbol symbol-50px d-none d-md-block me-5">
										<span class="symbol-label" style="background-image:url(/assets/media/raw_materials/'.$val['photo'].');"></span>
									</a>
									<a class="text-gray-800 fs-5 fw-bold">'. lot_kir($val['title']) .'</a>
								</div>
							</td>
							<td align="center">'.$unit.'</td>
							<td>'. $val['info'] .'</td>
							<td align="center" data-order="'.$val['active'].'">'; echo ($val['active'] === '1') ? ' <i class="far fa-check-circle text-success fs-1"></i>' : '<i class="far fa-check-circle text-secondary fs-1"></i>'; echo '</td>
							' . actionsFunction('editRaw_materialModal', [
								'data-id' => $val['id'],
								'data-title' => $val['title'],
								'data-photo' => $val['photo'],
								'data-unit' => $val['unit'],
								'data-info' => $val['info'],
								'data-active' => $val['active'],
								'data-table' => 'raw_materials',
							], $user_permissions['raw_materials']).'
						</tr>';
					} ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php include 'inc/end_body.php'; ?>

<?php if($user_permissions[SCRIPTNAME]['add_access'] === '1'): ?>
<!-- Add Raw_material -->
<div class="modal fade" id="addRaw_materialModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Hom ashyo qo'shish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="addRaw_materialForm">
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
						<input type="text" class="form-control form-control-solid" name="title"placeholder="<?php echo lot_kir("Nomi") ?>" required />
						<label><?php echo lot_kir("Nomi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<select class="form-select form-select-solid" name="unit" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("O'lchov biriligi") ?>" required>
							<option value="meter"><?php echo lot_kir("Metr") ?></option>
							<option value="kg"><?php echo lot_kir("KG") ?></option>
							<option value="qty"><?php echo lot_kir("Dona") ?></option>
						</select>
						<label><?php echo lot_kir("O'lchov birligi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<textarea class="form-control form-control-solid" name="info" placeholder="Izoh" style="height: 100px"></textarea>
						<label><?php echo lot_kir("Izoh") ?></label>
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
							<input type="hidden" name="table" value="raw_materials">
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
<!-- Edit Raw_material -->
<div class="modal fade" id="editRaw_materialModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
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
				<form id="editRaw_materialForm">
					<div class="text-center mb-10">
						<div class="image-input image-input-circle image-input-outline" data-kt-image-input="true" style="background-image: url(/assets/media/raw_materials/blank-image.svg)">
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

					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid" name="title" placeholder="<?php echo lot_kir("Nomi") ?>" required />
						<label><?php echo lot_kir("Nomi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<select class="form-select form-select-solid" name="unit" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("O'lchov biriligi") ?>" required>
							<option value="meter"><?php echo lot_kir("Metr") ?></option>
							<option value="kg"><?php echo lot_kir("KG") ?></option>
							<option value="qty"><?php echo lot_kir("Dona") ?></option>
						</select>
						<label><?php echo lot_kir("O'lchov birligi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<textarea class="form-control form-control-solid" name="info" placeholder="Izoh" style="height: 100px"></textarea>
						<label><?php echo lot_kir("Izoh") ?></label>
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
							<input type="hidden" name="table" value="raw_materials">
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

<script>

	<?php if($user_permissions[SCRIPTNAME]['add_access'] === '1'): ?>
	ajaxForm("#addRaw_materialForm", 'actions/add.php', true);
	<?php endif; ?>

	<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1'): ?>
	$('body').on("click", ".editBtn", function(){

		let id = $(this).data('id');
		let title = $(this).data('title');
		let unit = $(this).data('unit');
		let photo = $(this).data('photo');
		let info = $(this).data('info');
		let active = $(this).data('active');

		$(".modal#editRaw_materialModal .modal-header h2").html('<?php echo lot_kir("O'zgartirish") ?>: '+title);
		$("#editRaw_materialForm [name='title']").val(title);
		$("#editRaw_materialForm select[name=unit] option[value=" + unit + "]").prop('selected', true);
		$("#editRaw_materialForm [name='info']").val(info);
		$("#editRaw_materialForm [name='id']").val(id);

		$("#editRaw_materialForm .image-input-wrapper").css('background-image', 'url(/assets/media/raw_materials/'+photo+')');

		$(".modal select").select2({minimumResultsForSearch: Infinity});

		if(photo == 'blank-image.svg'){
			$('#editRaw_materialForm [data-kt-image-input-action="remove"]').addClass('d-none');
		}else{
			$('#editRaw_materialForm [data-kt-image-input-action="remove"]').removeClass('d-none');
		}

		if(active == '1'){ $("#editRaw_materialForm [name='active']").prop('checked', true); }else{ $("#editRaw_materialForm [name='active']").prop('checked', false); }
	});

	ajaxForm("#editRaw_materialForm", 'actions/edit.php', true);
	<?php endif; ?>

</script>

</body>
</html>