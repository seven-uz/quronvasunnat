<?php

include 'core/index.php';

$page_title = lot_kir("Ishlab chiqarish");

include 'inc/head.php';

$header['title'] = lot_kir("Ishlab chiqarish");
$header['breadcrumb'] = [0 => ['val' => lot_kir("Asosiy sahifa"), 'link' => '/'], $header['title']];

$production = $Query->getN("production pr", [
	'fields' => 'pr.id, pr.product_id, pr.group_id,	pr.user_id, pr.time, pr.qty,	pr.status,	pr.comment, p.title, u.fio',
	'join' => [
		['table' => 'products p', 'on' => 'pr.product_id = p.id'],
		['table' => 'users u', 'on' => 'pr.user_id = u.id'],
	],
	'order' => ['pr.time desc', 'pr.id desc'],
	'group' => 'pr.id, pr.group_id'
]);

if($user_permissions[SCRIPTNAME]['recent_history_access'] === '1'){
	$production_group = $Query->getN("production pr", [
		'fields' => 'pr.time, p.title, pr.product_id, pr.group_id, g.title as group_title, SUM(pr.qty) as qty',
		'join' => [
			['table' => 'products p', 'on' => 'pr.product_id = p.id'],
			['table' => 'users_groups g', 'on' => 'pr.group_id = g.user_group_id'],
		],
		'where' => [
			['column' => 'DATE(pr.time)', 'operand' => '>', 'value' => "'".date("Y-m-d H:i:s", strtotime($now.' -10 days'))."'"],
		],
		'order' => ['pr.time desc'],
		'group' => 'pr.product_id, p.title, pr.time, pr.group_id',
		'fieldAsKey' => ['time', 'id'],
	]);

	$works = $Query->getN("works w", [
		'fields' => 'w.date, w.worker_type, w.id, w.product_id, w.user_id, w.work_qty,	w.worked_time, w.comment, p.title, u.fio',
		'join' => [
			['table' => 'products p', 'on' => 'w.product_id = p.id'],
			['table' => 'users u', 'on' => 'w.user_id = u.id'],
		],
		'where' => [
			['column' => 'DATE(w.date)', 'operand' => '>', 'value' => "'".date("Y-m-d H:i:s", strtotime($now.' -10 days'))."'"],
		],
		'order' => ['w.date desc', 'w.id desc'],
		'ignore' => ['where' => " AND p.deleted <> '1'"]
		// 'where' => [
		// 	['column' => 'w.status', 'value' => "'ironman'"],
		// ],
		// 'fieldAsKey' => ['time', 'id'],
	]);
}else{
	$production_group = $Query->getN("production pr", [
		'fields' => 'pr.time, p.title, pr.product_id, pr.group_id, g.title as group_title, SUM(pr.qty) as qty',
		'join' => [
			['table' => 'products p', 'on' => 'pr.product_id = p.id'],
			['table' => 'users_groups g', 'on' => 'pr.group_id = g.user_group_id'],
		],
		'order' => ['pr.time desc'],
		'group' => 'pr.product_id, p.title, pr.time, pr.group_id',
		'fieldAsKey' => ['time', 'id'],
	]);

	$works = $Query->getN("works w", [
		'fields' => 'w.date, w.worker_type, w.id, w.product_id, w.user_id, w.work_qty,	w.worked_time, w.comment, p.title, u.fio',
		'join' => [
			['table' => 'products p', 'on' => 'w.product_id = p.id'],
			['table' => 'users u', 'on' => 'w.user_id = u.id'],
		],
		'order' => ['w.date desc', 'w.id desc'],
		'ignore' => ['where' => " AND p.deleted <> '1'"]
		// 'where' => [
		// 	['column' => 'w.status', 'value' => "'ironman'"],
		// ],
		// 'fieldAsKey' => ['time', 'id'],
	]);
}


// $production_packer = $Query->getN("production pr", [
// 	'fields' => 'pr.time, pr.id, pr.product_id, pr.user_id, pr.qty,	pr.status, pr.comment, u.fio',
// 	'join' => [
// 		['table' => 'users u', 'on' => 'pr.user_id = u.id'],
// 	],
// 	'order' => ['pr.time desc', 'pr.id desc'],
// 	'where' => [
// 		['column' => 'pr.status', 'value' => "'packer'"],
// 	],
// 	'fieldAsKey' => ['time', 'id'],
// ]);

// $production_iron_packer = array_merge($production_group, $production_ironman, $production_packer);
// krsort($production_iron_packer);

$products = $Query->getN("products p", [
	'fields' => 'p.id, p.title',
	'where' => [
		['column' => 'p.active', 'value' => "'1'"],
	],
	'order' => ['p.title'],
]);

$users_groups = $Query->getN("users_groups", [
	'fields' => 'user_group_id, title, active, type, comment',
	'order' => ['type', 'title'],
	'where' => [
		['column' => 'active', 'value' => "'1'"],
	],
]);

$ironman_packer = $Query->getN("users u", [
	'fields' => 'u.id, u.fio, r.value as role_values',
	'join' => [
		['table' => 'roles r', 'on' => 'r.id = u.role'],
	],
	'order' => ['u.fio'],
	'where' => [
		['column' => 'r.is_worker', 'value' => "'1'"],
		['column' => '(r.value', 'value' => "'packer' OR r.value = 'ironman')"],
	],
]);

include 'inc/begin_body.php';

?>

<div class="app-content flex-column-fluid">
	<div class="app-container container-fluid">
		<div class="d-flex flex-column flex-sm-row align-items-baseline align-items-sm-center mb-5 justify-content-between">
			<h4><?php echo lot_kir("Tikilgan ishlar") ?></h4>
			<div class="app-navbar-item mb-sm-0 mb-3"><a data-bs-toggle="modal" data-bs-target="#addProductionModal" class="btn btn-sm btn-primary w-100"><?php echo lot_kir("Ish qo'shish") ?></a></div>
		</div>
		<div class="card">
			<table class="table align-middle table-row-dashed fs-6 gy-3" id="subtable" datatable="true">
				<thead>
					<tr class="text-primary fw-bold text-uppercase">
						<th class="text-center ps-5">№</th>
						<th class="text-center"><?php echo lot_kir("Vaqti") ?></th>
						<th><?php echo lot_kir("Potok") ?></th>
						<th><?php echo lot_kir("Tovar") ?></th>
						<th class="text-center"><?php echo lot_kir("Soni") ?></th>
						<th class="text-end pe-5"><?php echo lot_kir("Amallar") ?></th>
					</tr>
				</thead>
				<tbody class="fw-bold text-gray-600">
					<?php
					foreach ($production_group as $value) {
						echo '<tr>
							<td class="text-center pe-0 ps-5">'.++$numm.'</td>
							<td class="text-center text-nowrap pe-0">'.dwt($value['time']).'</td>
							<td>'. lot_kir($value['group_title']) .'</td>
							<td>'. lot_kir($value['title']) .'</td>
							<td class="text-center pe-0">'.$value['qty'].'</td>
							<td class="text-end pe-5">';
								if(!$value['status']) echo '<button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary toggle h-35px w-35px mb-sm-0 mb-3" datatable-subtable="expand_row" data-time="'.$value['time'].'" data-group_id="'.$value['group_id'].'">
									<i class="ki-duotone ki-plus fs-2 m-0 toggle-off"></i>
									<i class="ki-duotone ki-minus fs-2 m-0 toggle-on"></i>
								</button>';
								if($user_permissions[SCRIPTNAME]['delete_access'] === '1'):
								echo '<a data-time="'.$value['time'].'" data-column="group_id" data-id="'.$value['group_id'].'" class="btn btn-icon btn-light-danger w-35px h-35px ms-3 delBtn" data-table="production">
									<i class="ki-duotone ki-trash fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
										<span class="path4"></span>
									</i>
								</a>';
								endif;
							echo '</td>
						</tr>';
					} ?>
				</tbody>
			</table>
		</div>
		<span class="separator separator-dashed mt-10"></span>
		<div class="d-flex flex-column flex-sm-row align-items-baseline align-items-sm-center mb-5 justify-content-between mt-8">
			<h4><?php echo lot_kir("Dazmollangan va qadoqlangan ishlar") ?></h4>
			<div class="d-flex">
				<div class="app-navbar-item"><a data-bs-toggle="modal" data-bs-target="#addIronmanModal" class="btn btn-sm btn-primary w-100"><?php echo lot_kir("Dazmolchi") ?></a></div>
			<div class="app-navbar-item ms-3"><a data-bs-toggle="modal" data-bs-target="#addPackerModal" class="btn btn-sm btn-primary w-100"><?php echo lot_kir("Qadoqlovchi") ?></a></div>
			</div>
		</div>
		<div class="card">
			<table class="table align-middle table-row-dashed fs-6 gy-3" id="subtable" datatable="true">
				<thead>
					<tr class="text-primary fw-bold text-uppercase">
						<th class="text-center mw-50px ps-5">№</th>
						<th class="text-center mw-50px"><?php echo lot_kir("Kuni") ?></th>
						<th><?php echo lot_kir("Ishchi") ?></th>
						<th><?php echo lot_kir("Tovar") ?></th>
						<th><?php echo lot_kir("Qilgan ishi") ?></th>
						<?php if($user_permissions[SCRIPTNAME]['delete_access'] === '1'): ?>
						<th class="text-end pe-5"><?php echo lot_kir("Amallar") ?></th>
						<?php endif; ?>
					</tr>
				</thead>
				<tbody class="fw-bold text-gray-600">
					<?php
					foreach ($works as $value) {

						if($value['worker_type'] === 'ironman') $worker_type = $value['work_qty'].' '.lot_kir("ta dazmollagan");
						elseif($value['worker_type'] === 'packer') $worker_type = intval(substr($value['worked_time'], 0, -6)) .' '.lot_kir("soat ishlagan");

						echo '<tr>
							<td class="text-center mw-50px pe-0 ps-5">'.++$numm2.'</td>
							<td class="text-center text-nowrap pe-0">'.dod($value['date']).'</td>
							<td>'. lot_kir($value['fio']) .'</td>
							<td>'; echo $value['title'] ? lot_kir($value['title']) : '-'; echo '</td>
							<td>'.$worker_type . '</td>
							<td class="text-end pe-5">';
								if($user_permissions[SCRIPTNAME]['delete_access'] === '1'):
								echo '<a data-time="'.$value['time'].'" data-column="user_id" data-id="'.$value['id'].'" class="btn btn-icon btn-light-danger w-35px h-35px ms-3 delBtn" data-table="production_works">
									<i class="ki-duotone ki-trash fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
										<span class="path4"></span>
									</i>
								</a>';
								endif;
							echo '</td>
						</tr>';
					} ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php include 'inc/end_body.php'; ?>

<?php if($user_permissions[SCRIPTNAME]['add_access'] === '1'): ?>
<!-- Add production -->
<div class="modal fade" id="addProductionModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Ishlab chiqarish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="addProductionForm">
					<div class="form-floating mb-7">
						<select class="form-select form-select-solid" name="product_id" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Tovarni tanlang") ?>" required>
						<option></option>
						<?php foreach ($products as $key => $val) {
							echo '<option value="'.$val['id'].'">'.lot_kir($val['title']).'</option>';
						} ?>
						</select>
						<label><?php echo lot_kir("Tovar") ?></label>
					</div>
					<div id="production_repeator">
						<div class="form-group">
							<div data-repeater-list="producer">
								<div data-repeater-item>
									<div class="form-group row">
										<div class="col-md-5 mb-7">
											<select class="form-select form-select-solid form-select-sm" name="stream" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Ishchi guruhini tanlang") ?>" required>
												<option></option>
												<?php foreach ($users_groups as $v) {
													if($v['type'] !== 'tailor') continue;
													echo '<option value="'.$v['user_group_id'].'">'. lot_kir($v['title']) .'</option>';
												} ?>
											</select>
										</div>
										<div class="col-md-5 mb-7">
											<input type="text" class="form-control form-control-sm form-control-solid mask_number" name="qty" placeholder="<?php echo lot_kir("Soni") ?>" required />
										</div>
										<div class="col-md-2 mb-7">
											<a data-repeater-delete class="btn btn-sm btn-light-danger">
												<i class="ki-duotone ki-trash fs-5 pe-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
											</a>
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
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid time_format_full" name="time" value="<?php echo date("d.m.Y H:i:s", strtotime($now))?>" placeholder="<?php echo lot_kir("Ishlab chiqarilgan vaqti") ?>" readonly required />
						<label><?php echo lot_kir("Ishlab chiqarilgan vaqti") ?></label>
					</div>
					<div class="form-floating mb-7">
						<textarea class="form-control form-control-solid" name="comment" placeholder="Izoh" style="height: 100px"></textarea>
						<label><?php echo lot_kir("Izoh") ?></label>
					</div>
					<div class="text-center pt-5">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
							<input type="hidden" name="table" value="production">
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

<!-- Add ironman works -->
<div class="modal fade" id="addIronmanModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Dazmolchi ishini qo'shish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="addIronmanForm">
					<div class="form-floating mb-7">
						<select class="form-select form-select-solid" name="product_id" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Tovarni tanlang") ?>" required>
						<option></option>
						<?php foreach ($products as $key => $val) {
							echo '<option value="'.$val['id'].'">'.lot_kir($val['title']).'</option>';
						} ?>
						</select>
						<label><?php echo lot_kir("Tovar") ?></label>
					</div>
					<div id="ironman_works_repeator">
						<div class="form-group">
							<div data-repeater-list="ironman">
								<div data-repeater-item>
									<div class="form-group row">
										<div class="col-md-5 mb-7">
											<select class="form-select form-select-solid form-select-sm" name="user_id" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Dazmolchini tanlang") ?>" required>
												<option></option>
												<?php foreach ($ironman_packer as $v) {
													if($v['role_values'] !== 'ironman') continue;
													echo '<option value="'.$v['id'].'">'. lot_kir($v['fio']) .'</option>';
												} ?>
											</select>
										</div>
										<div class="col-md-5 mb-7">
											<input type="text" class="form-control form-control-sm form-control-solid mask_number" name="qty" placeholder="<?php echo lot_kir("Soni") ?>" required />
										</div>
										<div class="col-md-2 mb-7">
											<a data-repeater-delete class="btn btn-sm btn-light-danger">
												<i class="ki-duotone ki-trash fs-5 pe-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
											</a>
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
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid date_format" name="date" value="<?php echo date("d.m.Y", strtotime($now))?>" readonly required />
						<label><?php echo lot_kir("Ish bajarilgan vaqti") ?></label>
					</div>
					<div class="form-floating mb-7">
						<textarea class="form-control form-control-solid" name="comment" placeholder="Izoh" style="height: 100px"></textarea>
						<label><?php echo lot_kir("Izoh") ?></label>
					</div>
					<div class="text-center pt-5">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
							<input type="hidden" name="table" value="production">
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

<!-- Add packer works -->
<div class="modal fade" id="addPackerModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Qadoqlovchi ishlagan vaqti") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="addPackerForm">
					<div id="packer_works_repeator">
						<div class="form-group">
							<div data-repeater-list="packer">
								<div data-repeater-item>
									<div class="form-group row">
										<div class="col-md-7 mb-7">
											<select class="form-select form-select-solid form-select-sm" name="user_id" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Qadoqlovchini tanlang") ?>" required>
												<option></option>
												<?php foreach ($ironman_packer as $v) {
													if($v['role_values'] !== 'packer') continue;
													echo '<option value="'.$v['id'].'">'. lot_kir($v['fio']) .'</option>';
												} ?>
											</select>
										</div>
										<div class="col-md-3 mb-7">
											<input type="text" class="form-control form-control-sm form-control-solid mask_number" name="qty" value="12" placeholder="<?php echo lot_kir("Soat") ?>" required />
										</div>
										<div class="col-md-2 mb-7">
											<a data-repeater-delete class="btn btn-sm btn-light-danger">
												<i class="ki-duotone ki-trash fs-5 pe-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
											</a>
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
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid date_format" name="date" value="<?php echo date("d.m.Y", strtotime($now))?>" readonly required />
						<label><?php echo lot_kir("Ishlagan kuni") ?></label>
					</div>
					<div class="form-floating mb-7">
						<textarea class="form-control form-control-solid" name="comment" placeholder="Izoh" style="height: 100px"></textarea>
						<label><?php echo lot_kir("Izoh") ?></label>
					</div>
					<div class="text-center pt-5">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
							<input type="hidden" name="table" value="production">
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

	const production_data = <?php echo json_encode($production)?>;

	$('body').on("click", '[datatable-subtable="expand_row"]', function(){

		let this_body = $(this).closest('tbody');
		let this_row = $(this).closest('tr');
		let data_id = $(this).data('group_id');
		let data_time = $(this).data('time');

		let new_content = '';

		production_data.forEach((el) => {
			if(el.group_id == data_id && data_time == el.time){
				new_content += `<tr datatable-subtable="subtable_row" data-closing_id="`+data_id+`">
					<td></td>
					<td></td>
					<td colspan="2">
						<div class="text-muted fs-7 fw-bold" datatable-subtable="template_cost">`+el.fio+`</div>
					</td>
					<td class="text-center pe-0">
						<div class="text-muted fs-7 fw-bold" datatable-subtable="template_qty">`+el.qty+`</div>
					</td>
					<td></td>
				</tr>`;
			}
		});

		if($(this).find('i').hasClass('ki-plus')){
			$(this).find('i').removeClass('ki-plus');
			$(this).find('i').addClass('ki-minus');
			$(this_row).after(new_content);
		}else{
			$(this).find('i').removeClass('ki-minus');
			$(this).find('i').addClass('ki-plus');
			$(this_body).find('tr[data-closing_id="'+data_id+'"]').remove();
		}
	});

	<?php if($user_permissions[SCRIPTNAME]['add_access'] === '1'): ?>

		ajaxForm("#addProductionForm", 'actions/add.php', true);
		ajaxForm("#addIronmanForm", 'actions/add.php', true);
		ajaxForm("#addPackerForm", 'actions/add.php', true);

		$('#production_repeator').repeater({
			show: function () {
				$(this).slideDown();
				$('#production_repeator select').select2({minimumResultsForSearch: Infinity});
				Inputmask({
					numericInput: true,
					alias: "numeric",
					groupSeparator: " ",
					digits: 0,
					rightAlign: false,
				}).mask('#production_repeator .mask_number');

			},
			hide: function (deleteElement) {
				$(this).slideUp(deleteElement);
			}
		});

		$('#ironman_works_repeator').repeater({
			show: function () {
				$(this).slideDown();
				$('#ironman_works_repeator select').select2({minimumResultsForSearch: Infinity});
				Inputmask({
					numericInput: true,
					alias: "numeric",
					groupSeparator: " ",
					digits: 0,
					rightAlign: false,
				}).mask('#ironman_works_repeator .mask_number');

			},
			hide: function (deleteElement) {
				$(this).slideUp(deleteElement);
			}
		});

		$('#packer_works_repeator').repeater({
			show: function () {
				$(this).slideDown();
				$('#packer_works_repeator select').select2({minimumResultsForSearch: Infinity});
				Inputmask({
					numericInput: true,
					alias: "numeric",
					groupSeparator: " ",
					digits: 0,
					rightAlign: false,
				}).mask('#packer_works_repeator .mask_number');

			},
			hide: function (deleteElement) {
				$(this).slideUp(deleteElement);
			}
		});

	<?php endif; ?>

	<?php if($user_permissions[SCRIPTNAME]['delete_access'] === '1'): ?>
		$('body').on("click", ".delBtn", function(){

			let id = $(this).data('id');
			let column = $(this).data('column');
			let time = $(this).data('time');
			let table = $(this).data('table');

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
						async: true,
						data: {table:table, column:column, id:id, time:time},
						dataType: "html",
						success: function (data) {
							if(data === 'success'){
								swal.fire({
									title: '<?php echo lot_kir("Amaliyot muvoffaqiyatli yakunlandi") ?>',
									icon: "success",
									customClass: {confirmButton: "btn btn-success m-btn m-btn--wide",}
								})
								.then(function () {
									setTimeout(() => {
										location.reload();
									}, 100);
								});
							}else{
								swal.fire({
									html: data,
									icon: "error",
									customClass: {confirmButton: "btn btn-success m-btn m-btn--wide",}
								});
							}
						},
						error: function (data) {
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