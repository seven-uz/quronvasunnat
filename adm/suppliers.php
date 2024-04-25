<?php

include 'core/index.php';

$page_title = lot_kir("Yetkazib beruvchilar");

include 'inc/head.php';

$header['title'] = lot_kir("Tovarlar");
$header['breadcrumb'] = [0 => ['val' => lot_kir("Asosiy sahifa"), 'link' => '/'], $header['title']];
$header['add_btn'] = '<div class="app-navbar-item ms-1 ms-md-3"><a data-bs-toggle="modal" data-bs-target="#addSupplierModal" class="btn btn-sm btn-primary"><i class="fas fa-plus pe-0 d-sm-none d-inline-block"></i><span class="d-sm-inline-block d-none">'.lot_kir("Kontragent qo'shish").'</span></a></div>';

$suppliers = $Query->getN("suppliers", [
	'fields' => 'id, name, phone, info, active',
	'order' => ['name'],
]);

include 'inc/begin_body.php';

?>

<div class="app-content flex-column-fluid">
	<div class="app-container container-fluid">
		<div class="card card-flush">
			<table class="table align-middle table-row-dashed" data-table="suppliers" datatable="true">
				<thead>
					<tr class="text-primary fw-bold text-uppercase">
						<th>№</th>
						<th><?php echo lot_kir("Nomi") ?></th>
						<th class="text-center"><?php echo lot_kir("Telefon raqami") ?></th>
						<th><?php echo lot_kir("Izoh") ?></th>
						<th class="text-center"><?php echo lot_kir("Aktivligi") ?></th>
						<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1' || $user_permissions[SCRIPTNAME]['delete_access'] === '1'): ?>
							<th class="text-end pe-5"><?php echo lot_kir("Amallar") ?></th>
						<?php endif; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($suppliers as $key => $val) {
						echo '<tr>
							<td>'.++$suppliers_num.'</td>
							<td>
								<div class="d-flex align-items-center">
									<a href="?id='.$val['id'].'" class="text-gray-800 text-hover-primary fs-5 fw-bold">'.$val['name'].'</a>
								</div>
							</td>
							<td class="text-center" data-order="'.$val['phone'].'"><a href="tel:+998'.$val['phone'].'">'.phone_number9($val['phone']).'</a></td>
							<td>'.$val['info'].'</td>
							<td align="center" data-order="'.$val['active'].'">'; echo ($val['active'] === '1') ? ' <i class="far fa-check-circle text-success fs-1"></i>' : '<i class="far fa-check-circle text-secondary fs-1"></i>'; echo '</td>
							' . actionsFunction('editSupplierModal', [
								'data-id' => $val['id'],
								'data-name' => $val['name'],
								'data-phone' => $val['phone'],
								'data-info' => $val['info'],
								'data-active' => $val['active'],
								'data-table' => 'suppliers',
							], $user_permissions['suppliers']).'
						</tr>';
					} ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php include 'inc/end_body.php'; ?>

<?php if($user_permissions[SCRIPTNAME]['add_access'] === '1'): ?>
<!-- Add Supplier -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Tovar qo'shish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="addSupplierForm">
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid" name="name" placeholder="<?php echo lot_kir("Nomi") ?>" required />
						<label><?php echo lot_kir("Nomi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid mask_phone" name="phone" placeholder="<?php echo lot_kir('Telefon raqami') ?>" />
						<label><?php echo lot_kir('Telefon raqami')?></label>
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
							<input type="hidden" name="table" value="suppliers">
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
<!-- Edit Supplier -->
<div class="modal fade" id="editSupplierModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
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
				<form id="editSupplierForm">
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid" name="name" placeholder="<?php echo lot_kir("Nomi") ?>" required />
						<label><?php echo lot_kir("Nomi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid mask_phone" name="phone" placeholder="<?php echo lot_kir('Telefon raqami') ?>" />
						<label><?php echo lot_kir('Telefon raqami')?></label>
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
							<input type="hidden" name="table" value="suppliers">
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
	ajaxForm("#addSupplierForm", 'actions/add.php', true);
	<?php endif; ?>

	<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1'): ?>
	$('body').on("click", ".editBtn", function(){

		let id = $(this).data('id');
		let name = $(this).data('name');
		let phone = $(this).data('phone');
		let info = $(this).data('info');
		let active = $(this).data('active');

		$(".modal#editSupplierModal .modal-header h2").html('<?php echo lot_kir("O'zgartirish") ?>: '+name);
		$("#editSupplierForm [name='name']").val(name);
		$("#editSupplierForm [name='phone']").val(phone);
		$("#editSupplierForm [name='info']").val(info);
		$("#editSupplierForm [name='id']").val(id);

		if(active == '1'){ $("#editSupplierForm [name='active']").prop('checked', true); }else{ $("#editSupplierForm [name='active']").prop('checked', false); }
	});

	ajaxForm("#editSupplierForm", 'actions/edit.php', true);
	<?php endif; ?>

</script>

</body>
</html>