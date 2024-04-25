<?php

include 'core/index.php';

$page_title = lot_kir("Ombor");

include 'inc/head.php';

if(isset($_GET['pro_id'])) $pro_id = intval($_GET['pro_id']);
if(isset($_GET['raw_mat_id'])) $raw_mat_id = intval($_GET['raw_mat_id']);

$page_permission = ['status' => true, 'permission' => 'import'];

$header['title'] = lot_kir("Ombor");

if($raw_mat_id){
	$import_raw_m = $Query->getN("import_raw_m irw", [
		'fields' => 'irw.id, irw.import_id, irw.raw_m_id, irw.quantity, irw.cost, irw.arrival_time, irw.comment,
		rw.title, rw.photo,rw.unit, (irw.quantity * irw.cost) as amount',
		'order' => ['arrival_time desc'],
		'join' => [
			['table' => 'raw_materials rw', 'on' => 'rw.id = irw.raw_m_id'],
		],
		'where' => [
			['column' => 'rw.id', 'value' => "$raw_mat_id"],
		],
	]);

	$header['breadcrumb'] = [0 => ['val' => lot_kir("Asosiy sahifa"), 'link' => '/'], 1 => ['val' => $header['title'], 'link' => 'warehouse'], $import_raw_m[0]['title']];

}else{
	$header['breadcrumb'] = [0 => ['val' => lot_kir("Asosiy sahifa"), 'link' => '/'], $header['title']];
}

if($show) $header['add_btn'] = '<div class="app-navbar-item ms-1 ms-md-3"><a data-bs-toggle="modal" data-bs-target="#addLeftoverModal" class="btn btn-sm btn-primary"><i class="fas fa-plus pe-0 d-sm-none d-inline-block"></i><span class="d-sm-inline-block d-none">'.lot_kir("Mavjud qoldiq qo'shish").'</span></a></div>';

$raw_materials = $Query->getN("raw_materials rw", [
	'fields' => 'rw.id, rw.title, rw.unit, rw.photo, rw.info, SUM(irw.quantity) as income',
	'where' => [
		['column' => 'rw.active', 'value' => "'1'"],
	],
	'join' => [
		['table' => 'import_raw_m irw', 'on' => 'rw.id = irw.raw_m_id'],
	],
	'ignore' => ['where' => [" AND irw.deleted <> '1'"]],
	'order' => ['rw.title'],
	'group' => 'rw.id',
]);

$sold = $Query->getN("orders", [
	'fields' => 'product_id, SUM(qty) as amount',
	'group' => 'product_id',
	'fieldAsKey' => 'product_id',
]);
$produced = $Query->getN("production", [
	'fields' => 'product_id, SUM(qty) as amount',
	'group' => 'product_id',
	'fieldAsKey' => 'product_id',
]);

$products = $Query->getN("products p", [
	'fields' => 'p.id, p.title, p.photo, p.info, p.compound, p.selling_price, p.season',
	'where' => [
		['column' => 'p.active', 'value' => "'1'"],
	],
	'order' => ['p.title'],
]);

$residual = $Query->getN("residual", [
	'fields' => 'product_id, SUM(qty) as amount',
	'group' => 'product_id',
	'fieldAsKey' => 'product_id',
]);

$curr_month = date("m", strtotime($now));

$season_column = array_column($products, 'season');

include 'inc/begin_body.php';

?>

<div class="app-content flex-column-fluid">
	<div class="app-container container-fluid">
		<?php if($raw_mat_id): ?>
			<div class="card">
				<table class="table align-middle table-row-dashed" data-table="import_raw_m" datatable="true">
					<thead>
						<tr class="text-primary fw-bold text-uppercase">
							<th class="text-center">№</th>
							<th class="text-center"><?php echo lot_kir("Sotib olingan vaqti") ?></th>
							<th class="text-center"><?php echo lot_kir("Miqdori") ?></th>
							<th class="text-center"><?php echo lot_kir("Narhi") ?></th>
							<th class="text-center"><?php echo lot_kir("Summasi") ?></th>
							<th><?php echo lot_kir("Izoh") ?></th>
							<?php
							if($user_permissions['import']['add_access'] === '1' || $user_permissions['import']['edit_access'] === '1'): ?>
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
								<td class="pe-0 text-center">'.++$import_num.'</td>
								<td class="pe-0 text-center" data-order="'.$val['arrival_time'].'">'.dwt($val['arrival_time']).'</td>
								<td class="pe-0 text-center" data-order="'.$val['quantity'].'">'.nf($val['quantity']).' '.lot_kir($unit).'</td>
								<td class="pe-0 text-center" data-order="'.$val['cost'].'">'.nf($val['cost']).'</td>
								<td class="pe-0 text-center" data-order="'.$val['amount'].'">'.nf($val['amount']).'</td>
								<td class="pe-0">'.$val['comment'].'</td>
								'.actionsFunction('editImportModal', [
									'data-id' => $val['id'],
									'data-raw_m_id' => $val['raw_m_id'],
									'data-cost' => $val['cost'],
									'data-quantity' => $val['quantity'],
									'data-arrival_time' => date("d.m.Y H:i", strtotime($val['arrival_time'])),
									'data-comment' => $val['comment'],
									'data-table' => 'import_raw_m',
								], $user_permissions['import']).'
							</tr>';
						} ?>
					</tbody>
				</table>
			</div>
		<?php elseif($pro_id):
			$import_raw_m = $Query->getN("import_raw_m irw", [
				'fields' => 'irw.id, irw.import_id, irw.raw_m_id, irw.quantity, irw.cost, irw.arrival_time, irw.comment,
				rw.title, rw.photo,rw.unit, (irw.quantity * irw.cost) as amount',
				'order' => ['arrival_time desc'],
				'join' => [
					['table' => 'raw_materials rw', 'on' => 'rw.id = irw.raw_m_id'],
				],
				'where' => [
					['column' => 'rw.id', 'value' => "$raw_m_id"],
				],
			]);
			?>
			<div class="card">
				<table class="table align-middle table-row-dashed" data-table="import_raw_m" datatable="true">
					<thead>
						<tr class="text-primary fw-bold text-uppercase">
							<th>№</th>
							<th><?php echo lot_kir("Hom ashyo") ?></th>
							<th class="text-center"><?php echo lot_kir("Sotib olingan vaqti") ?></th>
							<th class="text-center"><?php echo lot_kir("Miqdori") ?></th>
							<th class="text-center"><?php echo lot_kir("Narhi") ?></th>
							<th class="text-center"><?php echo lot_kir("Summasi") ?></th>
							<th><?php echo lot_kir("Izoh") ?></th>
							<?php
							if($user_permissions['import']['add_access'] === '1' || $user_permissions['import']['edit_access'] === '1'): ?>
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
								<td>'.++$import_num.'</td>
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
		<?php else: ?>
			<div class="col-12 mt-md-0 mt-10">
				<div class="d-flex justify-content-between align-items-center mb-5 flex-column flex-sm-row">
					<h3 class="fw-bold"><?php echo lot_kir("Tayyor mahsulot ombori") ?></h3>
				</div>
				<div class="card">
					<table class="table align-middle table-row-dashed" data-table="raw_materials" datatable="true">
						<thead>
							<tr class="text-primary fw-bold text-uppercase">
								<th class="ps-5"><?php echo lot_kir("Mahsulot (Model)") ?></th>
								<th class="text-end pe-5"><?php echo lot_kir("Ishlab chiqarilgan") ?></th>
								<th class="text-end pe-5"><?php echo lot_kir("Sotilgan") ?></th>
								<th class="text-end pe-5"><?php echo lot_kir("Junatilgan qoldiq") ?></th>
								<th class="text-end pe-5"><?php echo lot_kir("Qoldiq") ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($products as $key => $val) {
								echo '<tr>
									<td class="ps-5">
										<a class="text-gray-800 fs-5 fw-bold">'. lot_kir($val['title']) .'</a>
									</td>
									<td class="text-end pe-5">'.nf(($produced[$val['id']]['amount']), '0').'</td>
									<td class="text-end pe-5">'.nf(($sold[$val['id']]['amount']), '0').'</td>
									<td class="text-end pe-5">'.nf(($residual[$val['id']]['amount']), '0').'</td>
									<td class="text-end pe-5">'.nf((($produced[$val['id']]['amount'] - $sold[$val['id']]['amount']) + $residual[$val['id']]['amount']), '0').'</td>
								</tr>';
							} ?>
						</tbody>
						<tfoot>
							<tr>
								<td class="fw-bolder fs-5 ps-5"><?php echo lot_kir("Jami: ") ?></td>
								<td class="fw-bolder fs-5 text-end pe-5"><?php echo nf(array_sum(array_column($produced, 'amount')), '0'); ?></td>
								<td class="fw-bolder fs-5 text-end pe-5"><?php echo nf(array_sum(array_column($sold, 'amount')), '0'); ?></td>
								<td class="fw-bolder fs-5 text-end pe-5"><?php echo nf(array_sum(array_column($residual, 'amount')), '0'); ?></td>
								<td class="fw-bolder fs-5 text-end pe-5"><?php echo nf(array_sum(array_column($produced, 'amount')) - array_sum(array_column($sold, 'amount')) + array_sum(array_column($residual, 'amount')), '0'); ?></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<div class="col-12 mt-20">
				<div class="d-flex justify-content-between align-items-center mb-5">
					<h3 class="fw-bold"><?php echo lot_kir("Hom ashyo ombori") ?></h3>
					<div class="app-navbar-item ms-md-3 mb-sm-0 mb-3"><a data-bs-toggle="modal" data-bs-target="#addImportModal" class="btn btn-sm btn-primary w-100"><?php echo lot_kir("Sotib olish") ?></a></div>
				</div>
				<div class="card">
					<table class="table align-middle table-row-dashed" data-table="raw_materials" datatable="true">
						<thead>
							<tr class="text-primary fw-bold text-uppercase">
								<th class="ps-5"><?php echo lot_kir("Hom ashyo") ?></th>
								<th class="text-end pe-5"><?php echo lot_kir("Qoldiq") ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($raw_materials as $key => $val) {
								if($val['unit'] === 'meter') $unit = lot_kir("Metr");else
								if($val['unit'] === 'qty') $unit = lot_kir("Dona");else
								if($val['unit'] === 'kg') $unit = lot_kir("KG");

								$sold_materials = 0;
								foreach ($products as $v) {
									$compound = json_decode($v['compound'], true);

									if(array_key_exists($val['id'], $compound)){
										$sold_materials += $compound[$val['id']] * $produced[$v['id']]['amount'];
									}
								}

								echo '<tr>
									<td class="ps-5">
										<div class="d-flex align-items-center">
											<a href="?raw_mat_id='.$val['id'].'" class="text-gray-800 text-hover-primary fs-5 fw-bold">'. lot_kir($val['title']) .'</a>
										</div>
									</td>
									<td class="text-end pe-5" data-order="'.$val['income'].'"><span class="fw-bold">'.nf(($val['income'] - $sold_materials), '0').' '.$unit.'</span></td>
								</tr>';
							} ?>
						</tbody>
					</table>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>
<?php include 'inc/end_body.php'; ?>

<?php if(!$raw_mat_id && $user_permissions['import']['add_access'] === '1'): ?>
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

<?php if($show && $user_permissions['warehouse']['add_access'] === '1'): ?>
<!-- Add Leftover -->
<div class="modal fade" id="addLeftoverModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Mavjud qoldiq kiritish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="addLeftoverForm">
					<div id="leftover_repeator">
						<div class="form-group">
							<div data-repeater-list="product">
								<div data-repeater-item>
									<div class="row mb-5">
										<div class="col-6">
											<select class="form-select form-select-solid form-select-sm select_content w-100" name="product_id" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Tovarni tanlang") ?>" required>
												<option></option>
												<?php foreach ($products as $key => $val) {
													echo '<option value="'.$val['id'].'">'.lot_kir($val['title']).'</option>';
												} ?>
											</select>
										</div>
										<div class="col-6 text-end">
											<div class="d-flex">
												<input type="text" class="form-control form-control-sm form-control-solid mask_number" name="qty" placeholder="<?php echo lot_kir("Miqdori")?>" required />
												<a data-repeater-delete class="btn btn-sm btn-light-danger ms-3">
													<i class="ki-duotone ki-trash fs-5 pe-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
												</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group mb-5">
							<a data-repeater-create class="btn btn-sm btn-light-primary">
								<i class="ki-duotone ki-plus fs-3"></i><?php echo lot_kir("Qator qo'shish") ?>
							</a>
						</div>
					</div>
					<div class="text-center pt-5">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
							<input type="hidden" name="table[]" value="production">
							<input type="hidden" name="table[]" value="leftover">
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

<?php if($raw_mat_id && $user_permissions['import']['edit_access'] === '1'): ?>
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
<script src="assets/plugins/custom/formrepeater/formrepeater.bundle.js"></script>

<script>

	<?php if($user_permissions['import']['add_access'] === '1' || $user_permissions['import']['edit_access'] === '1'): ?>
	$("body").on("change keyup", ".change_input_cost, .change_input_quantity", function () {

		let form_content = $(this).closest('form').attr('id');

		let cost = $("body #"+form_content+" .change_input_cost").val().replace(/\s/g, "");
		let quantity = $("body #"+form_content+" .change_input_quantity").val().replace(/\s/g, "");

		$("body #" +form_content +" .amount_input").val((cost * quantity));
	});
	<?php endif; ?>

	<?php if($show && $user_permissions['warehouse']['add_access'] === '1'): ?>
	ajaxForm("#addLeftoverForm", 'actions/add.php', true);

	let repeater = $('#leftover_repeator').repeater({
		show: function () {
			$(this).slideDown();
			$('#leftover_repeator select').select2({minimumResultsForSearch: Infinity});
			Inputmask({
				numericInput: true,
				alias: "numeric",
				groupSeparator: " ",
				digits: 0,
				rightAlign: false,
			}).mask('#leftover_repeator .mask_number');

		},
		hide: function (deleteElement) {
			$(this).slideUp(deleteElement);
		}
	});
	<?php endif; ?>

	<?php if(!$raw_mat_id && $user_permissions['import']['add_access'] === '1'): ?>
	ajaxForm("#addImportForm", 'actions/add.php', true);
	<?php endif; ?>

	<?php if($raw_mat_id && $user_permissions['import']['edit_access'] === '1'): ?>
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

		$("#editImportForm select").select2({minimumResultsForSearch: Infinity});
	});

	ajaxForm("#editImportForm", 'actions/edit.php', true);
	<?php endif; ?>

</script>

</body>
</html>