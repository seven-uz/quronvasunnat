<?php

include 'core/index.php';

$page_title = lot_kir("Import");

include 'inc/head.php';

$header['title'] = lot_kir("Ombor");
$header['breadcrumb'] = [0 => ['val' => lot_kir("Asosiy sahifa"), 'link' => '/'], $header['title']];
$header['add_btn'] = '<div class="app-navbar-item ms-1 ms-md-3"><a data-bs-toggle="modal" data-bs-target="#addImportModal" class="btn btn-sm btn-primary"><i class="fas fa-plus pe-0 d-sm-none d-inline-block"></i><span class="d-sm-inline-block d-none">'.lot_kir("Sotib olish").'</span></a></div>';

$import_raw_m = $Query->getN("import_raw_m irw", [
	'fields' => 'irw.id, irw.import_id, irw.raw_m_id, irw.quantity, irw.cost, irw.arrival_time, irw.comment,
	rw.title, rw.photo,rw.unit, (irw.quantity * irw.cost) as amount',
	'order' => ['arrival_time desc'],
	'join' => [
		['table' => 'raw_materials rw', 'on' => 'rw.id = irw.raw_m_id'],
	],
]);

$raw_materials = $Query->getN("raw_materials", [
	'fields' => 'id, title',
	'where' => [
		['column' => 'active', 'value' => "'1'"],
	],
	'order' => ['title'],
]);

include 'inc/begin_body.php';

?>
<div class="app-content flex-column-fluid">
	<div class="app-container container-fluid">
		<div class="card">
			<table class="table align-middle table-row-dashed" data-table="import_raw_m" datatable="true">
				<thead>
					<tr class="text-primary fw-bold text-uppercase">
						<th class="text-center ps-5">№</th>
						<th><?php echo lot_kir("Hom ashyo") ?></th>
						<th class="text-center"><?php echo lot_kir("Sotib olingan vaqti") ?></th>
						<th class="text-center"><?php echo lot_kir("Miqdori") ?></th>
						<th class="text-center"><?php echo lot_kir("Narhi") ?></th>
						<th class="text-center"><?php echo lot_kir("Summasi") ?></th>
						<th><?php echo lot_kir("Izoh") ?></th>
						<?php
						if($user_permissions[SCRIPTNAME]['edit_access'] === '1' || $user_permissions[SCRIPTNAME]['delete_access'] === '1'): ?>
							<th class="text-end pe-5"><?php echo lot_kir("Amallar") ?></th>
						<?php endif; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($import_raw_m as $key => $val) {
						if($val['unit'] === 'meter') $unit = lot_kir("m");else
						if($val['unit'] === 'qty') $unit = lot_kir("ta");else
						if($val['unit'] === 'kg') $unit = lot_kir("kg");

						echo '<tr>
							<td class="text-center ps-5">'.++$import_num.'</td>
							<td><div class="d-flex align-items-center">
								<a class="symbol symbol-50px"><span class="symbol-label" style="background-image:url(/assets/media/raw_materials/'.$val['photo'].');"></span></a>
								<div class="ms-5"><a class="text-gray-800 fs-5 fw-bold">'.$val['title'].'</a></div>
							</div></td>
							<td class="text-center" data-order="'.$val['arrival_time'].'">'.dwt($val['arrival_time']).'</td>
							<td class="text-center" data-order="'.$val['quantity'].'">'.nf($val['quantity']).' '.lot_kir($unit).'</td>
							<td class="text-center" data-order="'.$val['cost'].'">'.nf($val['cost']).'</td>
							<td class="text-center" data-order="'.$val['amount'].'">'.nf($val['amount']).'</td>
							<td>'.$val['comment'].'</td>
							'.actionsFunction('editImportModal', [
								'data-id' => $val['id'],
								'data-raw_m_id' => $val['raw_m_id'],
								'data-cost' => $val['cost'],
								'data-quantity' => $val['quantity'],
								'data-arrival_time' => dateSimple($val['arrival_time']),
								'data-comment' => $val['comment'],
								'data-table' => 'import_raw_m',
							], $user_permissions['import']).'
						</tr>';
					} ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php include 'inc/end_body.php'; ?>

<?php if($user_permissions[SCRIPTNAME]['add_access'] === '1'): ?>
<!-- Add Import -->
<div class="modal fade" id="addImportModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Sotib olish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="addImportForm">
					<div class="form-floating mb-7">
						<select class="form-select form-select-solid" name="raw_m_id" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Hom ashyoni tanlang") ?>" required>
						<option></option>
						<?php foreach ($raw_materials as $key => $val) {
							echo '<option value="'.$val['id'].'">'.lot_kir($val['title']).'</option>';
						} ?>
						</select>
						<label><?php echo lot_kir("Hom ashyo") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid mask_number change_input_cost" name="cost" placeholder="<?php echo lot_kir("Narhi") ?>" required />
						<label><?php echo lot_kir("Narhi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid mask_number change_input_quantity" name="quantity" placeholder="<?php echo lot_kir("Miqdori") ?>" required />
						<label><?php echo lot_kir("Miqdori") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid mask_number amount_input disabled" value="0" placeholder="<?php echo lot_kir("Jami narhi") ?>" readonly required />
						<label><?php echo lot_kir("Jami narhi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid time_format" name="arrival_time" value="<?php echo date("d.m.Y H:i", strtotime($now))?>" placeholder="<?php echo lot_kir("Kelgan vaqti") ?>" readonly required />
						<label><?php echo lot_kir("Kelgan vaqti") ?></label>
					</div>
					<div class="form-floating mb-7">
						<textarea class="form-control form-control-solid" name="comment" placeholder="Izoh" style="height: 100px"></textarea>
						<label><?php echo lot_kir("Izoh") ?></label>
					</div>
					<div class="text-center pt-5">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
							<input type="hidden" name="table" value="import">
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
<!-- Edit Import -->
<div class="modal fade" id="editImportModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Sotib olish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="editImportForm">
					<div class="form-floating mb-7">
						<select class="form-select form-select-solid" name="raw_m_id" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Hom ashyoni tanlang") ?>" required>
						<option></option>
						<?php foreach ($raw_materials as $key => $val) {
							echo '<option value="'.$val['id'].'">'.lot_kir($val['title']).'</option>';
						} ?>
						</select>
						<label><?php echo lot_kir("Hom ashyo") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid mask_number change_input_cost" name="cost" placeholder="<?php echo lot_kir("Narhi") ?>" required />
						<label><?php echo lot_kir("Narhi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid mask_number change_input_quantity" name="quantity" placeholder="<?php echo lot_kir("Miqdori") ?>" required />
						<label><?php echo lot_kir("Miqdori") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid mask_number amount_input disabled" value="0" placeholder="<?php echo lot_kir("Jami narhi") ?>" readonly required />
						<label><?php echo lot_kir("Jami narhi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid time_format" name="arrival_time" placeholder="<?php echo lot_kir("Kelgan vaqti") ?>" readonly required />
						<label><?php echo lot_kir("Kelgan vaqti") ?></label>
					</div>
					<div class="form-floating mb-7">
						<textarea class="form-control form-control-solid" name="comment" placeholder="Izoh" style="height: 100px"></textarea>
						<label><?php echo lot_kir("Izoh") ?></label>
					</div>
					<div class="text-center pt-5">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
							<input type="hidden" name="table" value="import">
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

	<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1' || $user_permissions[SCRIPTNAME]['delete_access'] === '1'): ?>
	$("body").on("change keyup", ".change_input_cost, .change_input_quantity", function () {

		let form_content = $(this).closest('form').attr('id');

		let cost = $("body #"+form_content+" .change_input_cost").val().replace(/\s/g, "");
		let quantity = $("body #"+form_content+" .change_input_quantity").val().replace(/\s/g, "");

		$("body #" +form_content +" .amount_input").val((cost * quantity));
	});
	<?php endif; ?>

	<?php if($user_permissions[SCRIPTNAME]['add_access'] === '1'): ?>
	ajaxForm("#addImportForm", 'actions/add.php', true);
	<?php endif; ?>

	<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1'): ?>
	$('body').on("click", ".editBtn", function(){

		let id = $(this).data('id');
		let raw_m_id = $(this).data('raw_m_id');
		let quantity = $(this).data('quantity');
		let cost = $(this).data('cost');
		let arrival_time = $(this).data('arrival_time');
		let comment = $(this).data('comment');

		$(".modal#editImportModal .modal-header h2").html('<?php echo lot_kir("O'zgartirish") ?>');
		$("#editImportForm [name='raw_m_id'] option[value='"+raw_m_id+"']").prop('selected', true);
		$("#editImportForm [name='quantity']").val(quantity);
		$("#editImportForm [name='cost']").val(cost);
		$("#editImportForm [name='comment']").val(comment);
		$("#editImportForm [name='arrival_time']").val(arrival_time);
		$("#editImportForm .amount_input").val((quantity * cost));
		$("#editImportForm [name='id']").val(id);
	});

	ajaxForm("#editImportForm", 'actions/edit.php', true);
	<?php endif; ?>
</script>

</body>
</html>