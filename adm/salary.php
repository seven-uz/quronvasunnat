<?php

include 'core/index.php';

$page_title = lot_kir("Oylik");

include 'inc/head.php';

$first_day_this_month = date("Y-m-01", strtotime($now));

for ($i=0; $i < 7; $i++) {
	$options .= '<option value="'.date("Y-m", strtotime("-$i month", strtotime($first_day_this_month))).'">'.mn(date("Y-m", strtotime("-$i month", strtotime($first_day_this_month))), 'shorter').'</option>';
}

$header['title'] = lot_kir("Oylik");
$header['breadcrumb'] = [0 => ['val' => lot_kir("Asosiy sahifa"), 'link' => '/'], $header['title']];
$header['add_btn'] = '<div class="app-navbar-item ms-1 ms-md-3"><a data-bs-toggle="modal" data-bs-target="#addSalaryModal" class="btn btn-sm btn-primary"><i class="fas fa-plus pe-0 d-sm-none d-inline-block"></i><span class="d-sm-inline-block d-none">'.lot_kir("Oylik berish").'</span></a></div>';
// $header['add_btn'] .= '<div class="app-navbar-item ms-1 ms-md-3"><select class="form-select form-select-sm form-select-solid view_other_month_salary" data-hide-search="true" data-control="select2" data-placeholder="'.lot_kir("Boshqa oyni ko'rish").'"><option></option>'.$options.'</select></div>';

if(isset($_GET['month'])) {
	$month = $_GET['month'];
	$month_month = $_GET['month'];
	$current_date = intval(date("t", strtotime($_GET['month'])));
}else{
	$month_month = $now;
	$month = $month_month;
	$current_date = intval(date("d", strtotime($now)));
}

$users_groups = $Query->getN("users_groups", [
	'fields' => 'user_group_id, title, other_workhouse',
	'where' => [
		['column' => 'active', 'value' => "'1'"],
	],
	'fieldAsKey' => 'user_group_id',
	'order' => ['title'],
]);

$users = $Query->getN("users u", [
	'fields' => 'u.id, u.fio, u.photo, r.title as role_title, u.group_id, r.value as role_value, r.worker_type',
	'join' => [
		['table' => 'roles r', 'on' => 'r.id = u.role'],
	],
	'order' => ['fio'],
	'group' => 'u.id',
	'idAsKey' => true,
	'where' => [
		['column' => 'u.active', 'value' => "'1'"],
		['column' => 'r.is_worker', 'value' => "'1'"],
	],
]);

$production = $Query->getN("production p", [
	'fields' => 'p.id, p.user_id, p.group_id, p.product_id, p.time, SUBSTR(p.time, 1, 10) as date, p.qty,
	pr.tailor_price, pr.reaper_1_price, pr.reaper_2_price, pr.reaper_helper_price, pr.iron_man_price',
	'join' => [
		['table' => 'products pr', 'on' => 'pr.id = p.product_id'],
	],
	'where' => [
		['column' => 'YEAR(p.time)', 'operand' => '<=', 'value' => "'".date("Y", strtotime($month_month))."'"],
		['column' => 'MONTH(p.time)', 'operand' => '<=', 'value' => "'".date("m", strtotime($month_month))."'"],
	],
]);

$works_ironman = $Query->getN("works w", [
	'fields' => 'w.id, w.user_id, w.worker_type, SUBSTR(w.date, 1, 10) as date, w.product_id, w.work_qty, w.worked_time,
	p.iron_man_price',
	'join' => [
		['table' => 'products p', 'on' => 'p.id = w.product_id'],
	],
	'where' => [
		['column' => 'YEAR(w.date)', 'operand' => '<=', 'value' => "'".date("Y", strtotime($month_month))."'"],
		['column' => 'MONTH(w.date)', 'operand' => '<=', 'value' => "'".date("m", strtotime($month_month))."'"],
		['column' => 'worker_type', 'value' => "'ironman'"],
	],
]);

$works_packer = $Query->getN("works w", [
	'fields' => 'w.id, w.user_id, w.worker_type, SUBSTR(w.date, 1, 10) as date, w.product_id, w.work_qty, w.worked_time',
	'where' => [
		['column' => 'YEAR(w.date)', 'operand' => '<=', 'value' => "'".date("Y", strtotime($month_month))."'"],
		['column' => 'MONTH(w.date)', 'operand' => '<=', 'value' => "'".date("m", strtotime($month_month))."'"],
		['column' => 'worker_type', 'value' => "'packer'"],
	],
]);

$paid_q = $Query->getN("expenses", [
	'fields' => 'to_id, to_group_id, SUM(cost) as cost',
	'where' => [
		['column' => '(to_id', 'operand' => '<>', 'value' => "'' OR to_group_id <> '')"],
		['column' => 'YEAR(time)', 'operand' => '<=', 'value' => "'".date("Y", strtotime($month_month))."'"],
		['column' => 'MONTH(time)', 'operand' => '<=', 'value' => "'".date("m", strtotime($month_month))."'"],
	],
	'group' => 'to_id, to_group_id',
]);

$attendance = $Query->getN("attendance a", [
	'fields' => 'a.id, a.date, a.worker_id, a.not_come, a.comment, u.fio, r.value',
	'join' => [
		['table' => 'users u', 'on' => 'u.id = a.worker_id'],
		['table' => 'roles r', 'on' => 'r.id = u.role'],
	],
	'where' => [
		['column' => 'YEAR(a.date)', 'operand' => '<=', 'value' => "'".date("Y", strtotime($month_month))."'"],
		['column' => 'MONTH(a.date)', 'operand' => '<=', 'value' => "'".date("m", strtotime($month_month))."'"],
	],
]);

$earned = $paid = $workers = $attendance_arr = [];

if(!empty($attendance)) {
	$workers = array_unique(arrayColumn($attendance, 'worker_id', 'fio'));

	foreach ($attendance as $key => $value) {
		if($value['value'] === 'master'){
			$attendance_arr[$value['date']]['master'] = $value['not_come'];
		}else{
			$attendance_arr[$value['date']][$value['worker_id']] = $value['not_come'];
		}
	}
	unset($attendance);
}

foreach ($production as $key => $value) {
	if(!$attendance_arr[$value['date']][$value['user_id']]) {
		$earned['tailor'][$value['user_id']][$value['date']] += $value['qty'] * $value['tailor_price'];
	}

	if(!$attendance_arr[$value['date']]['master']) {
		if($users_groups[$value['group_id']]['other_workhouse'] != '1'){
			$master[$value['date']][$value['user_id']] += $value['qty'] * $value['tailor_price'];
		}
	}
	@$earned['master'][$value['date']] = max(array_unique($master[$value['date']]));
}

foreach ($users as $k => $v) {
	if($v['role_value'] !== 'reaper') continue;
	foreach ($production as $value) {
		if(!$attendance_arr[$value['date']][$v['id']]) {
			if($v['worker_type'] === 'main'){
				$earned['reaper'][$v['id']][$value['date']] += $value['qty'] * $value['reaper_1_price'];
			}elseif($v['worker_type'] === 'helper'){
				$earned['reaper'][$v['id']][$value['date']] += $value['qty'] * $value['reaper_helper_price'];
			}
		}
	}
}

ksort($attendance_arr);

$attendance_this_month = [];
foreach ($attendance_arr as $key => $value) {
	if(substr($month_month, 0, 7) == substr($key, 0, 7)){
		foreach ($value as $k => $v) {
			$attendance_this_month[] = $k;
		}
	}
}
$attendance_this_month = array_unique($attendance_this_month);

foreach ($works_ironman as $key => $val) {
	$earned['ironman'][$val['user_id']][$val['date']] += $val['work_qty'] * $val['iron_man_price'];
}

foreach ($works_packer as $key => $val) {
	$earned['packer'][$val['user_id']][$val['date']] += $val['worked_time'] * 12000;
}

foreach ($paid_q as $key => $val) {
	$paid[$val['to_id']] = $val['cost'];
}

$first_produced_porduct_time = $Query->getN("production", [
	'fields' => 'time',
	'order' => ['time'],
	'limit' => 1
])[0]['time'];
if($first_produced_porduct_time == null) $first_produced_porduct_time = date("Y-m", strtotime($now));
$first_produced_porduct = months_count($first_produced_porduct_time, date("Y-m", strtotime($now)));


include 'inc/begin_body.php';


?>

<div class="app-content flex-column-fluid">
	<div class="app-container container-fluid">
		<div class="d-flex justify-content-between align-items-center mb-5 flex-column flex-sm-row">
			<h3 class="fw-bold"><?php echo lot_kir("Ishchilar oyligi") ?></h3>
			<div class="app-navbar-item ms-md-3 mb-sm-0 mb-3">
				<select class="form-select" onchange="if(this.value) window.location.href = this.value" data-control="select2" data-hide-search="true">
					<?php
					for ($i = 0; $i <= $first_produced_porduct; $i++) {
						echo '<option value="salary?month='. date("Y-m-d", strtotime("-$i months", strtotime($first_day_this_month))) .'"'; if(date("Y-m", strtotime("-$i months", strtotime($first_day_this_month))) == date("Y-m", strtotime($month_month))) echo ' selected'; echo '>'. mn(date("Y-m-d", strtotime("-$i months", strtotime($first_day_this_month)))) .'</option>';
					}
					?>
				</select>
			</div>
		</div>
		<!-- Main table -->
		<div class="card card-flush">
			<div class="card-body main_table_content">
				<div class="table-responsive">
					<table class="table align-middle table-row-dashed table-bordered" data-table="users" datatable="custom">
						<thead>
							<tr class="text-primary fw-bold text-uppercase align-middle">
								<th class="text-center pe-3">№</th>
								<th><?php echo lot_kir("Ishchi") ?></th>
								<th class="text-center"><?php echo lot_kir("Qoldiq") ?></th>
								<?php
								for ($i=$current_date; $i >= 1; $i--) {
									echo '<th class="text-center pe-3">'.($i).'</th>';
								} ?>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($users as $value) {
								echo '<tr>
									<td class="text-center pe-3">'.++$num.'</td>
									<td class="fw-bold">'. lot_kir($value['fio']) . '</td>';

									if($value['role_value'] === 'master'){
										$salary_left = ($earned['master'] != null ? array_sum($earned['master']) : 0) - $paid[$value['id']];
									}else{
										$salary_left = ($earned[$value['role_value']][$value['id']] != null ? array_sum($earned[$value['role_value']][$value['id']]) : 0) - $paid[$value['id']];
									}
									echo '<td class="text-center text-nowrap fw-bold'; if($salary_left > 0) echo ' text-danger'; elseif($salary_left < 0) echo ' text-warning'; echo '" data-sum="'.$salary_left.'">'. nf($salary_left) .'</td>';

									for ($i=$current_date; $i >= 1; $i--) {
										if(strlen($i) == 1) $i = '0'.$i;

										if($value['role_value'] === 'master'){
											$earned_for_day = ($earned['master'][substr($month_month, 0, 7).'-'.$i]) ? $earned['master'][substr($month_month, 0, 7).'-'.$i] : 0;
										}elseif($value['role_value'] === 'ironman'){
											$earned_for_day = $earned['ironman'][$value['id']][substr($month_month, 0, 7).'-'.$i] ? $earned['ironman'][$value['id']][substr($month_month, 0, 7).'-'.$i] : 0;
										}elseif($value['role_value'] === 'packer'){
											$earned_for_day = $earned['packer'][$value['id']][substr($month_month, 0, 7).'-'.$i] ? $earned['packer'][$value['id']][substr($month_month, 0, 7).'-'.$i] : 0;
										}elseif($value['role_value'] === 'tailor'){
											$earned_for_day = $earned['tailor'][$value['id']][substr($month_month, 0, 7).'-'.$i];
										}elseif($value['role_value'] === 'reaper'){
											$earned_for_day = $earned['reaper'][$value['id']][substr($month_month, 0, 7).'-'.$i];
										}
										echo '<td class="text-center text-nowrap" data-order="'.$earned_for_day.'">'. nf($earned_for_day) .'</td>';
									}

								echo '</tr>';
							}
							?>
						</tbody>
						<tfoot>
							<tr>
								<td></td>
								<td><?php echo lot_kir("Jami") ?></td>
								<td></td>
								<td colspan="<?php echo $current_date?>"></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>

		<!--Attendace-->
		<div class="accordion mt-10" id="attendance_accordeon">
			<div class="accordion-item">
				<h2 class="accordion-header" id="attendance_content_header">
					<button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#attendance_content" aria-expanded="true" aria-controls="attendance_content">
						<?php echo lot_kir("Yo'qlama") ?>
					</button>
				</h2>
				<div id="attendance_content" class="accordion-collapse collapse" aria-labelledby="attendance_content_header" data-bs-parent="#attendance_accordeon">
					<div class="card card-flush">
						<div class="card-body p-4">
							<table class="table align-middle table-row-dashed table-bordered mb-0 rounded-3">
									<thead>
										<tr class="text-primary fw-bold text-uppercase align-middle">
											<th><?php echo lot_kir("Ishchi") ?></th>
											<?php foreach (array_keys($attendance_arr) as $i) {
												if(substr($month_month, 0, 7) == substr($i, 0, 7)){
													echo '<th class="text-center">'. substr($i, 8, 2) .'</th>';
												}
											} ?>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($workers as $k => $v) {
											if(!in_array($k, $attendance_this_month)) continue;
											echo '<tr>
												<td>'.$v.'</td>';
												foreach ($attendance_arr as $day_key => $user) {
													if(substr($month_month, 0, 7) == substr($day_key, 0, 7)){
														echo '<td class="text-center text-nowrap fw-bold text-danger">'; if($attendance_arr[$day_key][$k]) echo '-'; else ''; echo '</td>';
													}
												}
											echo '</tr>';
										}
										?>
									</tbody>
								</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include 'inc/end_body.php'; ?>


<?php if($user_permissions[SCRIPTNAME]['add_access'] === '1'): ?>
<!-- Add Salary -->
<div class="modal fade" id="addSalaryModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Oylik berish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="addSalaryForm">
					<input type="hidden" name="type_id" value="<?php echo $Query->getN("expenses_types", ['fields' => 'id','where' => [['column' => 'title', 'value' => "'Oylik'"],],'order' => ['title']])[0]['id']; ?>">
					<div class="form-floating mb-7">
						<select class="form-select form-select-solid" name="to_id" data-control="select2" data-placeholder="<?php echo lot_kir("Ishchi (Potok)") ?>" data-dropdown-parent="#addSalaryModal" data-allow-clear="true" required>
							<option></option>
							<?php
							foreach ($users as $value) {
								echo '<option value="'.$value['id'].'">'.lot_kir($value['fio']).'</option>';
							}
							?>
						</select>
						<label class="required"><?php echo lot_kir("Ishchi (Potok)") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid mask_number" name="cost" placeholder="<?php echo lot_kir("Summa") ?>" required />
						<label class="required"><?php echo lot_kir("Summa") ?></label>
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
<?php endif; ?>

<?php include 'inc/javascript.php'; ?>

<script>

	<?php if($user_permissions[SCRIPTNAME]['add_access'] === '1'): ?>
	ajaxForm("#addSalaryForm", 'actions/add.php', true);
	<?php endif; ?>

	<?php if($user_permissions[SCRIPTNAME]['view_access'] === '1'): ?>
	$(".view_other_month_salary").on("change", function(){

		let year = $(this).val().substring(0, 4);
		let month = $(this).val().substring(7, 5);

		let selected_month = new Date(year + '-' + month + ' ' + '1'), y = selected_month.getFullYear(), m = selected_month.getMonth();
		let selected_month_last_day = new Date(y, m + 1, 0);
		let selected_month_last_day_int = selected_month_last_day.getDate();

		let arr = '';

		let table_header = [
			{ data: 'title' },
			{ data: 'left' },
		];

		// for (let index = 1; index <= selected_month_last_day_int; index++) {
		// 	arr += `<th class="text-center pe-3">${index}</th>`;

		// 	table_header.push({ data: 'day_'+index });

		// }
		for (let index = 1; index <= selected_month_last_day_int; index++) {
			arr += `<th class="text-center pe-3">${index}</th>`;
		}


		$.ajax({
			url: 'core/ajax/salary.php',
			type: 'POST',
			data: {
				date: $(this).val()
			},
			success: function (data) {
				let json_result = JSON.parse(data);

				let tbody = '<tbody>';

				Object.entries(json_result['data']).forEach((val, i) => {
					tbody += '<tr>';
					Object.entries(val[1]).forEach(element => {
						if(element[0] == 'title'){
							tbody += '<td class="text-nowrap fw-bold">'+element[1]+'</td>';
						}else return
					});
					Object.entries(val[1]).forEach(element => {
						if(element[0] != 'left' && element[0] != 'title'){
							tbody += '<td class="text-nowrap text-center" data-order="'+$.trim(element[1])+'">'+element[1]+'</td>';
						}else return
					});
					Object.entries(val[1]).forEach(element => {
						if(element[0] == 'left'){
							tbody += '<td class="text-nowrap text-center" data-order="'+$.trim(element[1])+'">'+element[1]+'</td>';
						} else return;
					});
					tbody += '</tr>';
					return;
				});
				tbody += '</tbody>';

				$(".main_table_content").html(`
					<div class="table-responsive">
						<table class="table align-middle table-row-dashed table-bordered safasfsa" datatable="`+year+month+`_table">
							<thead>
								<tr class="text-primary fw-bold text-uppercase align-middle">
									<th><?php echo lot_kir("Ishchi (Potok)") ?></th>
									`+arr+`
									<th class="text-center"><?php echo lot_kir("Qoldiq") ?></th>
								</tr>
							</thead>`+tbody+`
						</table>
					</div>
				`);
			},
		});

		setTimeout(() => {
			$('.main_table_content .safasfsa').DataTable({
				language: {
					search: '',
					searchPlaceholder: "<?php echo lot_kir("Qidirish...");?>",
					emptyTable: "<?php echo lot_kir("Ma'lumot topilmadi!");?>"
				},
				"aaSorting": [],
				dom: "t",
				paging:        false,
				scrollX:        true,
				scrollCollapse: true,
				fixedColumns:   {
					left: 1
				},
			});
		}, 100);
	});

	customdatatable = $('.table[datatable="custom"]').DataTable({
		language: {
			search: '',
			searchPlaceholder: "<?php echo lot_kir("Qidirish...");?>",
      emptyTable: "<?php echo lot_kir("Ma'lumot topilmadi!");?>"
		},
		"aaSorting": [],
		dom: "t",
		paging:        false,
		scrollX:        true,
		scrollCollapse: true,
		fixedColumns:   {
			left: 3
		},
		footerCallback: function (tfoot, data, start, end, display) {
      var api = this.api();
     	api.columns(2, {page: 'current'}).every(function() {
				var sum = this.nodes().reduce(function(a, b) {
					var x = parseFloat(a) || 0;
					var y = parseFloat($(b).attr('data-sum')) || 0;
					return x + y;
				}, 0);
				$(this.footer()).html(`<span class="fw-bolder text-nowrap">`+sum.toLocaleString().replaceAll(',', ' ')+`</span>`);
			});
    }
	});
	<?php endif; ?>

</script>

</body>
</html>