<?php

include 'core/index.php';

$page_title = lot_kir("Sotilgan tovarlar");

include 'inc/head.php';

$header['title'] = lot_kir("Sotish");
$header['breadcrumb'] = [0 => ['val' => lot_kir("Asosiy sahifa"), 'link' => '/'], $header['title']];
$header['add_btn'] = '<div class="app-navbar-item ms-1 ms-md-3"><a data-bs-toggle="modal" data-bs-target="#addOrderModal" class="btn btn-sm btn-primary"><i class="fas fa-plus pe-0 d-sm-none d-inline-block"></i><span class="d-sm-inline-block d-none">'.lot_kir("Sotish").'</span></a></div>';

$products_all = $Query->getN("products", [
	'fields' => 'id, title, info, photo, selling_price, active',
	'order' => ['title'],
]);

foreach ($products_all as $key => $value) {
	if($value['active'] === '0') continue;
	$products[] = $value;
}

$clients = $Query->getN("clients c", [
	'fields' => 'c.id, c.user_id, u.fio, u.phone, c.active',
	'order' => ['u.fio'],
	'join' => [
		['table' => 'users u', 'on' => 'u.id = c.user_id'],
	],
]);


if(isset($_GET['filterbyyears'])){
	$filterbyyears = intval($_GET['filterbyyears']);
}else{
	$filterbyyears = date("Y", strtotime($now));
}

$orders = $Query->getN("orders o", [
	'fields' => 'o.id, o.client_id, u.fio, o.order_num, o.product_id, o.qty, o.price, o.status, o.comment, p.title, p.photo, o.created_at as sold_time',
	'order' => ['o.created_at desc'],
	'join' => [
		['table' => 'products p', 'on' => 'p.id = o.product_id'],
		['table' => 'clients c', 'on' => 'c.id = o.client_id'],
		['table' => 'users u', 'on' => 'u.id = c.user_id'],
	],
	'where' => [
		['column' => 'YEAR(o.created_at)', 'value' => $filterbyyears]
	]
]);


// ------------------------------------------------------------------------------------- //

$products_with_tannarx = $Query->getN("products", [
	'fields' => 'id, title, compound, season, reaper_1_price, reaper_2_price, reaper_helper_price, tailor_price, iron_man_price, selling_price, additional_expenses, info, photo, active',
	'order' => ['title'],
]);

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

	$products_arr[$val['id']] = $price_raw_materials+$price_services;
}
unset($products_with_tannarx, $raw_materials_q);

// ------------------------------------------------------------------------------------- //


include 'inc/begin_body.php';

?>

<div class="app-content flex-column-fluid">
	<div class="app-container container-fluid">
		<div class="card card-flush">
			<?php if($_SESSION['role_value'] === 'admin'): ?>
				<div class="accordion" id="orders_clients_acc">
				<?php foreach ($clients as $v) {

					if(!in_array($v['id'], array_unique(array_column($orders, 'client_id')))) continue;

					echo '<div class="accordion-item">
						<h2 class="accordion-header" id="orders_clients_header_'.$v['id'].'">
							<button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-client_id="'.$v['id'].'" data-bs-target="#orders_clients_body_'.$v['id'].'" aria-expanded="true" aria-controls="orders_clients_body_'.$v['id'].'">
								'. $v['fio'].'
							</button>
						</h2>
						<div id="orders_clients_body_'.$v['id'].'" class="accordion-collapse collapse" aria-labelledby="orders_clients_header_'.$v['id'].'" data-bs-parent="#orders_clients_acc">
							<div class="accordion-body p-0">
								<div class="table-responsive">
									<table class="table align-middle table-row-dashed main_tables" data-table="orders">
										<thead>
											<tr class="text-primary fw-bold text-uppercase">
												<th class="text-center ps-5">№</th>
												<th>'.lot_kir("Mahsulot").'</th>
												<th class="text-center pe-3">'.lot_kir("Vaqti").'</th>
												<th class="text-center pe-3">'.lot_kir("Soni").'</th>
												<th class="text-center pe-3">'.lot_kir("Narhi").'</th>
												<th>'.lot_kir("Izoh").'</th>';
												if($user_permissions[SCRIPTNAME]['edit_access'] === '1' || $user_permissions[SCRIPTNAME]['delete_access'] === '1'):
													echo '<th class="text-end pe-5">'.lot_kir("Amallar").'</th>';
												else:
													echo '<th class="text-end pe-5"></th>';
												endif;
											echo '</tr>
										</thead>
									</table>
								</div>
							</div>
						</div>
					</div>';
				} ?>
				</div>
			<?php else:
				if($_SESSION['role_value'] === 'customer'):
					$client_id = $Query->getN("clients", ['fields' => 'id','where' => [['column' => 'user_id', 'value' => $_SESSION['id']],],])[0]['id'];

					echo '<div class="table-responsive">
						<table class="table align-middle table-row-dashed" data-table="orders" datatable="true">
							<thead>
								<tr class="text-primary fw-bold text-uppercase">
									<th class="text-center ps-5">№</th>
									<th>'.lot_kir("Mahsulot").'</th>
									<th class="text-center">'.lot_kir("Vaqti").'</th>
									<th class="text-center">'.lot_kir("Soni").'</th>
									<th class="text-center">'.lot_kir("Narhi").'</th>
									<th>'.lot_kir("Izoh").'</th>';
									if($user_permissions[SCRIPTNAME]['edit_access'] === '1' || $user_permissions[SCRIPTNAME]['delete_access'] === '1'):
										echo '<th class="text-end pe-5">'.lot_kir("Amallar").'</th>';
									endif;
								echo '</tr>
							</thead>
							<tbody>';
								foreach ($orders as $key => $val) {
									if($val['client_id'] == $client_id){
										echo '<tr data-id="'.$val['id'].'">
											<td align="center">' . ++$orders_num . '</td>
											<td>
												<div class="d-flex align-items-center">
													<a class="symbol symbol-50px d-md-block d-none me-5">
														<span class="symbol-label" style="background-image:url(/assets/media/products/'.$val['photo'].');"></span>
													</a>
													<a class="text-gray-800 fs-5 fw-bold">'.$val['title'].'</a>
												</div>
											</td>
											<td class="text-center pe-0" data-order="'.$val['sold_time'].'">'.dwt($val['sold_time']).'</td>
											<td class="text-center pe-0" data-order="'.$val['qty'].'">'.nf($val['qty']).'</td>
											<td class="text-center pe-0 text-nowrap" data-order="'.$val['price'].'">'.nf($val['price']).'</td>
											<td>'.$val['comment'].'</td>
											' . actionsFunction('editOrderModal', [
												'data-id' => $val['id'],
												'data-client_id' => $val['client_id'],
												'data-tannarx_edit' => $products_arr[$val['product_id']],
												'data-product_id' => $val['product_id'],
												'data-qty' => $val['qty'],
												'data-price' => $val['price'],
												'data-comment' => $val['comment'],
												'data-table' => 'orders',
											], $user_permissions['orders']).'
										</tr>';
									}
								}
							echo '</tbody>
						</table>
					</div>';
				endif;
			endif;
			 ?>
		</div>
		<?php if($_SESSION['role_value'] === 'admin'):
			$orders_new = [];
			foreach ($orders as $key => $value) {
				$orders_new[$value['product_id']] = [
					'photo' => $value['photo'],
					'title' => $value['title'],
				];
			}
			foreach ($orders as $key => $value) {
				$orders_new[$value['product_id']]['qty'] += $value['qty'];
			}
			?>
			<div class="card card-flush mt-10">
				<div class="accordion" id="orders_all">
					<div class="accordion-item">
						<h2 class="accordion-header" id="orders_all_header">
							<button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#orders_all_body" aria-expanded="true" aria-controls="orders_all_body">
								<?php echo lot_kir("Jami sotilgan tovarlar") ?>
							</button>
						</h2>
						<div id="orders_all_body" class="accordion-collapse collapse" aria-labelledby="orders_all_header" data-bs-parent="#orders_all">
							<div class="accordion-body p-0">
								<div class="table-responsive">
									<table class="table align-middle table-row-dashed" datatable="all_orders_table">
										<thead>
											<tr class="text-primary fw-bold text-uppercase py-7">
												<th class="text-center ps-5">№</th>
												<th><?php echo lot_kir("Mahsulot") ?></th>
												<th class="text-center"><?php echo lot_kir("Sotilgan")?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											foreach ($orders_new as $key => $val) {
												echo '<tr>
													<td class="text-center pe-0">' . ++$orders_all_num . '</td>
													<td>
														<div class="d-flex align-items-center">
															<a class="symbol symbol-50px d-md-block d-none me-5">
																<span class="symbol-label" style="background-image:url(/assets/media/products/'.$val['photo'].');"></span>
															</a>
															<a class="text-gray-800 fs-5 fw-bold">'. lot_kir($val['title']) .'</a>
														</div>
													</td>
													<td class="text-center pe-0" data-order="'.$val['qty'].'">'.$val['qty'].'</td>
												</tr>';
											}
											?>
										</tbody>
										<tfoot>
											<th></th>
											<th class="fw-bold fs-5"><?php echo lot_kir("Jami:") ?></th>
											<th class="text-center fw-bold fs-5"></th>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="card card-flush mt-10">
				<?php
					if(isset($_GET['diller'])){

						$dillerOrders = $Query->getN("orders o", [
							'fields' => 'o.id, o.client_id, u.fio, o.order_num, o.product_id, o.qty, o.price, o.status, o.comment, p.title, p.photo, o.created_at as sold_time',
							'order' => ['o.created_at desc'],
							'join' => [
								['table' => 'products p', 'on' => 'p.id = o.product_id'],
								['table' => 'clients c', 'on' => 'c.id = o.client_id'],
								['table' => 'users u', 'on' => 'u.id = c.user_id'],
							],
							'where' => [
								['column' => 'o.client_id', 'value' => $_GET['diller']],
							],
						]);

						echo '<table class="table align-middle table-row-dashed" datatable="true">
							<thead>
								<tr class="text-primary fw-bold text-uppercase py-7">
									<th class="text-center ps-5">№</th>
									<th>'.lot_kir("Vaqti").'</th>
									<th>'.lot_kir("Mahsulot").'</th>
									<th class="text-center">'.lot_kir("Soni").'</th>
									<th class="text-end">'.lot_kir("Narxi").'</th>
									<th class="text-end pe-5">'.lot_kir("Summasi").'</th>
								</tr>
							</thead>
							<tbody>';
								foreach ($dillerOrders as $key => $val) {
									$summ += $val['qty'] * $val['price'];
									echo '<tr>
										<td class="text-center pe-0">' . ++$orders_all_num . '</td>
										<td class="text-left pe-0">' . dod($val['sold_time']) . '</td>
										<td>
											<div class="d-flex align-items-center">
												<a class="symbol symbol-50px d-md-block d-none me-5">
													<span class="symbol-label" style="background-image:url(/assets/media/products/'.$val['photo'].');"></span>
												</a>
												<a class="text-gray-800 fs-5 fw-bold">'. lot_kir($val['title']) .'</a>
											</div>
										</td>
										<td class="text-center pe-0" data-order="'.$val['qty'].'">'.$val['qty'].'</td>
										<td class="text-end pe-0" data-order="'.$val['price'].'">'.$val['price'].'</td>
										<td class="text-end pe-5" data-order="'.($val['qty'] * $val['price']).'">'.nf($val['qty'] * $val['price']).'</td>
									</tr>';
								}
							echo '
							<tfoot>
								<td></td>
								<td class="fw-bolder fs-5">Jami:</td>
								<td></td>
								<td></td>
								<td></td>
								<td class="text-end pe-5 fw-bolder">'.nf($summ).'</td>
							</tfoot>
							</tbody>
						</table>';
					}else{
						foreach ($clients as $key => $val) {
							echo '<div class="p-5">
								<a class="text-gray-800 fs-5 fw-bold" href="?diller='.$val['id'].'">'. lot_kir($val['fio']) .'</a>
							</div>';
						}
					}
				?>
			</div>
		<?php endif; ?>
	</div>
</div>
<?php include 'inc/end_body.php'; ?>

<?php if($user_permissions[SCRIPTNAME]['add_access'] === '1'): ?>
<!-- Add Order -->
<div class="modal fade" id="addOrderModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Sotish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="addOrderForm">
					<div class="form-floating mb-7">
						<select class="form-select form-select-solid" name="client_id" data-placeholder="<?php echo lot_kir("Dillerni tanlang")?>" data-hide-search="true" data-control="select2" required>
						<option></option>
						<?php foreach ($clients as $key => $val) {
							echo '<option value="'.$val['id'].'">'.lot_kir($val['fio']).'</option>';
						} ?>
						</select>
						<label><?php echo lot_kir("Diller") ?></label>
					</div>
					<div id="product_add_repeator">
						<div class="form-group">
							<div data-repeater-list="product">
								<div data-repeater-item>
									<div class="form-group row">
										<div class="col-md-5 mb-7">
											<select class="form-select form-select-solid form-select-sm select_content" name="product_id" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Tovarni tanlang") ?>" required>
												<option></option>
												<?php foreach ($products as $key => $val) {
													echo '<option value="'.$val['id'].'" data-price="'.$val['selling_price'].'" data-tannarx="'.$products_arr[$val['id']].'">'.lot_kir($val['title']).'</option>';
												} ?>
											</select>
										</div>
										<div class="col-md-5 mb-7">
											<div class="form-input row">
												<input type="text" class="form-control form-control-sm form-control-solid mask_number w-50" autocomplete="off" name="qty" placeholder="<?php echo lot_kir("Miqdori")?>" required />
												<div class="ps-3 w-50"><input type="text" class="form-control form-control-solid form-control-sm mask_number price_input" autocomplete="off" name="price" placeholder="<?php echo lot_kir("Narhi") ?>" required /></div>
												<input type="hidden" class="tannarx_input" name="tannarx" />
											</div>
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
						<textarea class="form-control form-control-solid" name="comment" placeholder="Izoh" style="height: 70px"></textarea>
						<label><?php echo lot_kir("Izoh") ?></label>
					</div>
					<div class="text-center">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
							<input type="hidden" name="table" value="orders">
							<span class="indicator-label"><?php echo lot_kir("Saqlash") ?></span>
							<span class="indicator-progress">
								<?php echo lot_kir("Amaliyot bajarilmoqda"); ?>...
								<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
							</span>
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1'): ?>
<!-- Edit Order -->
<div class="modal fade" id="editOrderModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
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
				<form id="editOrderForm">
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid mask_number" autocomplete="off" name="qty" placeholder="<?php echo lot_kir("Soni") ?>" required />
						<label><?php echo lot_kir("Soni") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="hidden" name="tannarx_edit" />
						<input type="text" class="form-control form-control-solid mask_number" autocomplete="off" name="price" placeholder="<?php echo lot_kir("Narhi") ?>" required />
						<label><?php echo lot_kir("Narhi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<textarea class="form-control form-control-solid" name="comment" placeholder="Izoh" style="height: 70px"></textarea>
						<label><?php echo lot_kir("Izoh") ?></label>
					</div>
					<div class="text-center">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
							<input type="hidden" name="table" value="orders">
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
				$(this).parent().next().find('input')[0].focus();
			}, 10);
		});
	<?php endif; ?>

	<?php if($user_permissions[SCRIPTNAME]['view_access'] === '1'): ?>
		$('[datatable="all_orders_table"]').DataTable({
			dom: "<'card-header align-items-center border-bottom-0'<'card-title'<'d-flex align-items-center flex-column flex-sm-row'<'d-none d-sm-block'l>p>><'yearFilter'>><'card-body p-0'<'table-responsive't>>",
			buttons: [{extend:'print', text:'<i class="fas fa-print text-primary"></i>'}],
			lengthMenu: [10, 20, 30, 50, 100],
			pagingType: "simple_numbers",
			aaSorting: [],
			footerCallback: function (row, data, start, end, display) {
				let api = this.api();

				// Remove the formatting to get integer data for summation
				let intVal = function (i) {
						return typeof i === 'string'
								? i.replace(/[\$,\s]/g, '') * 1
								: typeof i === 'number'
								? i
								: 0;
				};
				total = api
						.column(2)
						.data()
						.reduce((a, b) => intVal(a) + intVal(b), 0);
				api.column(2).footer().innerHTML = total.toFixed(1).replace(/\d(?=(\d{3})+\.)/g, '$& ');
			}
		});

		$('.yearFilter').html(`<select data-control="select2" onchange="if(this.value) window.location.href = this.value" class="ms-3 form-select form-select-solid form-select-lg" data-hide-search="true">
			<option value="orders?filterbyyears=<?php echo date("Y", strtotime("-1 year", strtotime($now))) ?>" <?php if(date("Y", strtotime("-1 year", strtotime($now))) == $filterbyyears) echo ' selected'; ?>><?php echo date("Y", strtotime("-1 year", strtotime($now))) ?></option>
			<option value="orders?filterbyyears=<?php echo date("Y", strtotime($now)) ?>" <?php if(date("Y", strtotime($now)) == $filterbyyears) echo ' selected'; ?>><?php echo date("Y", strtotime($now)) ?></option>
		</select>`);
		$('.role_filter select').select2({minimumResultsForSearch: Infinity });

		$(".accordion-button").on("click", function(){

			let table_this = $(this).parent().parent().find(".main_tables");

			if($(this).hasClass('inserted') === false){
				$(table_this).DataTable({
					dom: "<'card-header align-items-center border-bottom-0'<'card-title'<'d-flex align-items-center flex-column flex-sm-row'<'d-none d-sm-block'l>p>>B><'card-body p-0'<'table-responsive't>>",
					buttons: [{extend:'print', text:'<i class="fas fa-print text-primary"></i>'}],
					lengthMenu: [10, 20, 30, 50, 100],
					pagingType: "simple_numbers",
					aaSorting: [],
					ajax: {
						url: 'core/ajax/orders_by_client.php',
						type: 'POST',
						data: {
							client_id: $(this).data('client_id')
						}
					},
					columns: [
						{ width: "auto", className: "text-center", data: 'num' },
						{ width: "auto", data: 'product' },
						{ width: "auto", className: "text-center", data: 'sold_time' },
						{ width: "auto", className: "text-center", data: 'qty' },
						{ width: "auto", className: "text-center text-nowrap", data: 'cost' },
						{ width: "auto", data: 'comment' },
						{ width: "auto", className: "text-end", data: 'actions' },
					],
				});
			}

			if($(this).hasClass('inserted') === false){
				$(this).addClass('inserted');
			}

		});
	<?php endif; ?>

	<?php if($user_permissions[SCRIPTNAME]['add_access'] === '1'): ?>

		$("body").on("change", "#product_add_repeator select", function(){

			let price_input = $(this).parent().parent();
			$(price_input).find('.price_input').val($(this).find(':selected').data('price'));
			$(price_input).find('.tannarx_input').val($(this).find(':selected').data('tannarx'));

		});

		let repeater = $('#product_add_repeator').repeater({
			show: function () {
				$(this).slideDown();
				$('#product_add_repeator select').select2({minimumResultsForSearch: Infinity});
				Inputmask({
					numericInput: true,
					alias: "numeric",
					groupSeparator: " ",
					digits: 0,
					rightAlign: false,
				}).mask('#product_add_repeator .mask_number');

			},
			hide: function (deleteElement) {
				$(this).slideUp(deleteElement);
			}
		});

		ajaxForm("#addOrderForm", 'actions/add.php', true);
	<?php endif; ?>

	<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1'): ?>
		$('body').on("click", ".editBtn", function(){

			let id = $(this).data('id');
			let client_id = $(this).data('client_id');
			let product_id = $(this).data('product_id');
			let tannarx_edit = $(this).data('tannarx_edit');
			let qty = $(this).data('qty');
			let price = $(this).data('price');
			let comment = $(this).data('comment');
			let active = $(this).data('active');

			$(".modal#editOrderModal .modal-header h2").html('<?php echo lot_kir("O'zgartirish") ?>');
			$("#editOrderForm select[name=client_id] option[value=" + client_id + "]").prop('selected', true);
			$("#editOrderForm select[name=product_id] option[value=" + product_id + "]").prop('selected', true);
			$("#editOrderForm [name='qty']").val(qty);
			$("#editOrderForm [name='price']").val(price);
			$("#editOrderForm [name='comment']").val(comment);
			$("#editOrderForm [name='tannarx_edit']").val(tannarx_edit);
			$("#editOrderForm [name='id']").val(id);

			$("#editOrderForm select").select2();

			if(active == '1'){ $("#editOrderForm [name='active']").prop('checked', true); }else{ $("#editOrderForm [name='active']").prop('checked', false);}

		});

		ajaxForm("#editOrderForm", 'actions/edit.php', true);
	<?php endif; ?>

</script>

</body>
</html>