<?php

include 'core/index.php';

$page_title = lot_kir("Dillerlar");

include 'inc/head.php';

if(isset($_GET['id'])) $id = intval($_GET['id']);

$header['title'] = lot_kir("Dillerlar");
$header['add_btn'] = '<div class="app-navbar-item ms-1 ms-md-3"><a data-bs-toggle="modal" data-bs-target="#addClientModal" class="btn btn-sm btn-primary"><i class="fas fa-plus pe-0 d-sm-none d-inline-block"></i><span class="d-sm-inline-block d-none">'.lot_kir("Diller qo'shish").'</span></a></div>';

if($id){

	$sold_amount = $Query->getN("orders", [
		'fields' => 'SUM(qty * price) as amount',
		'where' => [
			['column' => 'client_id', 'value' => $id],
		],
		'fetch' => 'one',
	])['amount'];

	$not_archived_payments_amount = $Query->getN("payments", [
		'fields' => 'SUM(amount) as amount',
		'where' => [
			['column' => 'client_id', 'value' => $id],
			['column' => 'archive', 'value' => "'0'"],
		],
		'fetch' => 'one',
	])['amount'];

	$sold_by_client = $Query->getN("soldout_by_diller", [
		'fields' => 'SUM(qty * cost) as sum',
		'where' => [
			['column' => 'client_id', 'value' => $id],
		],
		'fetch' => 'one',
	])['sum'];

	$orders_all = $Query->getN("orders o", [
		'fields' => 'o.created_at, o.archive, CONCAT(SUBSTR(o.created_at, 9, 2),".",SUBSTR(o.created_at, 6, 2)) as created_at_short, o.id, o.product_id, o.qty, o.price, p.title, (o.qty * o.price) as summ, 0 as leftover',
		'join' => [
			['table' => 'products p', 'on' => 'p.id = o.product_id'],
		],
		'where' => [
			['column' => 'o.client_id', 'value' => $id],
		],
		'order' => ['o.created_at', 'p.id'],
	]);

	$orders = [];

	foreach ($orders_all as $key => $value) {
		if($value['archive'] === '1') continue;
		$orders[] = $value;
	}

	$payments_all = $Query->getN("payments p", [
		'fields' => 'SUM(amount) as amount',
		'where' => [
			['column' => 'p.client_id', 'value' => $id],
		],
		'fetch' => 'one',
	])['amount'];

	$residual = $Query->getN("residual r", [
		'fields' => 'r.product_id, r.qty, r.send_time, p.title, p.selling_price as price',
		'where' => [
			['column' => 'r.client_id', 'value' => $id],
			['column' => 'archive', 'value' => "'0'"],
		],
		'join' => [
			['table' => 'products p', 'on' => 'p.id = r.product_id'],
		],
		'fieldAsKey' => 'product_id',
	]);

	$payments_arr = $Query->getN("payments", [
		'fields' => 'id, payment_time, amount, comment',
		'where' => [
			['column' => 'client_id', 'value' => $id],
			['column' => 'archive', 'value' => "'0'"],
		],
		'order' => ['payment_time desc'],
	]);

	$products = arrayColumn($orders, 'product_id', 'title');

	$orders_arr = [];
	foreach (array_unique(array_column($orders, 'title')) as $key => $value) {
		$orders_arr[$value] = [
			"dates" => []
		];
	}
	foreach ($orders as $key => $val) {
		$orders_arr[$val['title']]['dates'][$val['created_at_short']] += $val['qty'];
		$orders_arr[$val['title']]['amount'] += $val['qty'];
		$orders_arr[$val['title']]['prices'][] = $val['price'];
		$orders_arr[$val['title']]['product_id'] = $val['product_id'];
	}
	foreach ($residual as $key => $val) {
		$orders_arr[$val['title']]['product_id'] = $val['product_id'];
		$orders_arr[$val['title']]['residual']['dates'][date("d.m", strtotime($val['send_time']))] += $val['qty'];
		$orders_arr[$val['title']]['prices'][] = $val['price'];
	}
	ksort($orders_arr);

	$clients = $Query->getN("clients c", [
		'fields' => 'c.id, c.user_id, u.fio, u.phone, c.active',
		'order' => ['u.fio'],
		'join' => [
			['table' => 'users u', 'on' => 'u.id = c.user_id'],
		],
		'where' => [
			['column' => 'c.id', 'value' => "$id"],
		],
	]);

	$payments_by_month = $Query->getN("payments p", [
		'fields' => 'SUM(amount) as amount, SUBSTR(payment_time, 1, 7) as month',
		'where' => [
			['column' => 'p.client_id', 'value' => $id],
		],
		'group' => 'month',
		'order' => ['month desc'],
	]);

	$header['breadcrumb'] = [0 => ['val' => lot_kir("Asosiy sahifa"), 'link' => '/'], 1 => ['val' => $header['title'], 'link' => 'clients'], lot_kir($clients[0]['fio'])];

	$orders_all_summ = array_sum(array_column($orders_all, 'summ'));
	$orders_not_archived_summ = array_sum(array_column($orders, 'summ'));

	$debt = $orders_all_summ - $payments_all;

}else{
	$clients = $Query->getN("clients c", [
		'fields' => 'c.id, c.user_id, u.fio, u.phone, c.active',
		'order' => ['u.fio'],
		'join' => [
			['table' => 'users u', 'on' => 'u.id = c.user_id'],
		],
	]);
	$header['breadcrumb'] = [0 => ['val' => lot_kir("Asosiy sahifa"), 'link' => '/'], $header['title']];
}

if($id) $header['add_btn'] .= '<div class="app-navbar-item ms-1 ms-md-3"><a data-bs-toggle="modal" data-bs-target="#addResidualModal" class="btn btn-sm btn-primary w-100"><i class="fas fa-file-import pe-0 d-md-none d-inline-block"></i><span class="d-md-inline-block d-none">'.lot_kir("Qoldiq jo‘natish").'</span></a></div>';

include 'inc/begin_body.php';

?>

<div class="app-content flex-column-fluid">
	<div class="app-container container-fluid">
		<?php if($id):
			$getted_money_amount = $payments_all - $sold_by_client;
			?>
			<div class="card card-flush">
				<div class="card-header">
					<h3 class="card-title">
						<?php echo lot_kir($clients[0]['fio']); ?>&nbsp;&nbsp;-&nbsp;&nbsp;<span class="text-<?php if($debt > 0) echo 'danger'; elseif($debt < 0) echo 'warning'; else echo 'success';?> fw-bolder fs-5"><?php echo lot_kir("Qarzdorlik: ").' '. nf($debt, 0) ?></span>
					</h3>
					<div class="card-toolbar">
						<div class="table_buttons_sold ms-3"></div>
					</div>
				</div>
				<div class="card-body p-0">
					<div class="table-responsive">
						<?php
						$residual_dates = [];
						foreach (array_column($orders_arr, 'residual') as $key => $value) {
							foreach ($value as $ka => $val) {
								foreach ($val as $k => $v) {
									$residual_dates[] = $k;
								}
							}
						}
						?>
						<table class="table align-middle table-row-dashed fs-6" datatable="sold">
							<thead>
								<tr class="text-start fw-bold fs-7 text-uppercase gs-0">
									<th class="ps-5"><?php echo lot_kir("Model") ?></th>
									<th class="text-end pe-0"><?php echo lot_kir("Narhi") ?></th>
									<?php foreach (array_unique($residual_dates) as $key => $value) {
										echo '<th class="text-end pe-0">'.lot_kir("Ost.") . " ($value)</th>";
									} ?>
									<?php foreach (array_unique(array_column($orders, 'created_at_short')) as $v) {
										echo '<th class="text-end pe-0">'.$v.'</th>';
									} ?>
									<th class="text-end pe-5"><?php echo lot_kir("Jami") ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($orders_arr as $key => $val) {
									$price_arr = '';
									foreach (array_unique($val['prices']) as $key_p => $price) {
										if(count(array_unique($val['prices'])) > 1) {
											if($key_p == count($val['prices']) - 1) $price_arr .= '<span class="text-success fw-bold">'.nf($price).'</span> | ';
											else $price_arr .= '<span class="text-danger fw-bold">'.nf($price).'</span> | ';
										}
										else $price_arr .= '<span class="text-success fw-bold">'.nf($price).'</span> | ';
									}

									$residual_date_amount = 0;

									echo '<tr>
										<td class="ps-5">'. lot_kir($key) .'</td>';
										echo '<td class="text-end pe-0">' . rtrim($price_arr, ' | ') . '</td>';
										foreach (array_unique($residual_dates) as $r_date) {
											echo '<td class="text-end pe-0">' . nf($val['residual']['dates'][$r_date]) . '</td>';
											$residual_date_amount += $val['residual']['dates'][$r_date];
										}
										$orders_arr[$key]['residual_date_amount'] = $residual_date_amount;
										foreach (array_unique(array_column($orders, 'created_at_short')) as $v) {
											echo '<td class="text-end pe-0">'.nf($orders_arr[$key]['dates'][$v]).'</td>';
										}
										echo '<td class="text-end pe-5">'.nf($orders_arr[$key]['amount'] + $orders_arr[$key]['residual_date_amount']).'</td>
									</tr>';

									$amount += ($orders_arr[$key]['amount'] + $orders_arr[$key]['residual_date_amount']) * end($val['prices']);
								}
							echo '</tbody>'; ?>
							<?php
							if(count($orders_arr) > 0){
								echo '<tfoot>
									<tr>
										<td class="text-end fs-5 pe-5 fw-bold" colspan="'.(count(array_unique(array_column($orders, 'created_at_short'))) + count(array_unique($residual_dates)) + 3).'">Jami: '.nf($amount). '<span class="text-gray-500">' . '</span></td>
									</tr>
								</tfoot>';
							}
							?>
						</table>
					</div>
				</div>
			</div>

			<div class="card card-flush mt-10">
				<div class="card-header">
					<h3 class="card-title align-items-start flex-column">
						<span class="card-label fw-bold text-dark"><?php echo lot_kir("To'lovlar") ?></span>
					</h3>
					<div class="card-toolbar">
						<?php
							echo '<div class="fw-bolder fs-5">
								<span class="text-'; if($getted_money_amount < 0) echo 'warning'; echo '">'.
									lot_kir("Jami olingan pul: ").' '. nf($getted_money_amount, 0).'
								</span>
							</div>';
						?>
						<div class="table_buttons_payments ms-3"></div>
					</div>
				</div>
				<?php if(count($payments_arr) > 0): ?>
				<div class="card-body p-0">
					<div class="table-responsive">
						<table class="table align-middle table-row-dashed fs-6" data-table="payments" datatable="payments">
							<thead>
								<tr class="text-start fw-bold fs-7 text-uppercase gs-0">
									<th class="ps-5"><?php echo lot_kir("Vaqti") ?></th>
									<th class="text-end pe-0"><?php echo lot_kir("Summa") ?></th>
									<th class="text-end pe-0"><?php echo lot_kir("Izoh") ?></th>
									<?php if($user_permissions['payments']['edit_access'] === '1' || $user_permissions['payments']['delete_access'] === '1'){ echo '<th class="text-end pe-5">'. lot_kir("Amallar") .'</th>'; } ?>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($payments_arr as $key => $val){
									echo '<tr data-id="'.$val['id'].'">
										<td class="ps-5">'. dwt($val['payment_time']) .'</td>';
										echo '<td class="text-end pe-0">'.nf($val['amount']).'</td>';
										echo '<td class="text-end pe-0">'. $val['comment'] .'</td>';
										if($user_permissions['payments']['delete_access'] === '1'){
										echo '<td class="text-end pe-5">
										<button class="btn btn-icon btn-light-danger w-30px h-30px deleteBtn" data-bs-toggle="modal" data-id="'.$val['id'].'" data-table="payments">
											<i class="ki-duotone ki-trash fs-3">
												<span class="path1"></span>
												<span class="path2"></span>
												<span class="path3"></span>
												<span class="path4"></span>
												<span class="path5"></span>
											</i>
										</button></td>';
										}
									echo '</tr>';
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<?php endif; ?>
			</div>

			<?php if(count($payments_by_month) > 0): ?>
			<div class="card card-flush mt-10">
				<div class="card-header">
					<h3 class="card-title align-items-start flex-column">
						<span class="card-label fw-bold text-dark"><?php echo lot_kir("To'lovlar arxivi") ?></span>
					</h3>
				</div>
				<div class="card-body p-0">
					<div class="table-responsive">
						<table class="table align-middle table-row-dashed fs-6" <?php echo count($payments_by_month) > 10 ? 'datatable="true"' : ''; ?>>
							<thead>
								<tr class="text-start fw-bold fs-7 text-uppercase gs-0">
									<th class="ps-5"><?php echo lot_kir("Oy") ?></th>
									<th class="text-end pe-5"><?php echo lot_kir("Topshirilgan summa") ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($payments_by_month as $key => $val) {
									echo '<tr>
										<td class="ps-5">'. mn($val['month']) .'</td>
										<td class="text-end pe-5">'.nf($val['amount']).'</td>
									</tr>';
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<?php endif; ?>
		<?php else: ?>
			<div class="card card-flush">
				<table class="table align-middle table-row-dashed" data-table="clients" datatable="true">
					<thead>
						<tr class="text-primary fw-bold text-uppercase">
							<th class="text-center ps-5">№</th>
							<th><?php echo lot_kir("Nomi") ?></th>
							<th class="text-center"><?php echo lot_kir("Telefon raqami") ?></th>
							<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1' || $user_permissions[SCRIPTNAME]['delete_access'] === '1'): ?>
								<th class="text-end pe-5"><?php echo lot_kir("Amallar") ?></th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($clients as $key => $val) {
							echo '<tr>
								<td class="text-center pe-0 ps-5">'.++$clients_num.'</td>
								<td>
									<div class="d-flex align-items-center">
										<a href="?id='.$val['id'].'" class="text-gray-800 text-hover-primary fs-5 fw-bold">'. lot_kir($val['fio']) .'</a>
									</div>
								</td>
								<td class="text-center pe-0" data-order="'.$val['phone'].'"><a href="tel:+998'.$val['phone'].'">'.phone_number9($val['phone']).'</a></td>';
								if($user_permissions[SCRIPTNAME]['edit_access'] === '1'){
									echo '<td class="text-end pe-5"><a class="btn btn-icon btn-light-warning w-30px h-30px" href="users?id='.$val['user_id'].'"><i class="ki-duotone ki-notepad-edit fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i></a></td>';
								}
							echo '</tr>';
						} ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>
	</div>
</div>
<?php include 'inc/end_body.php'; ?>

<?php if($user_permissions[SCRIPTNAME]['add_access'] === '1'): ?>
<!-- Add Client -->
<div class="modal fade" id="addClientModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Diller qo'shish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="addClientForm">
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid" name="fio" placeholder="<?php echo lot_kir("Ismi va (yoki) Familiyasi") ?>" required />
						<label class="required"><?php echo lot_kir("Ismi va (yoki) Familiyasi") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid" name="login" placeholder="<?php echo lot_kir("Login") ?>" />
						<label><?php echo lot_kir("Login") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="phone" class="form-control form-control-solid mask_phone" name="phone" placeholder="<?php echo lot_kir("Telefon") ?>" />
						<label><?php echo lot_kir("Telefon") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="email" class="form-control form-control-solid mask_email" name="email" placeholder="<?php echo lot_kir("Pochta (E-mail)") ?>" />
						<label><?php echo lot_kir("Pochta (E-mail)") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="password" class="form-control form-control-solid" name="password" placeholder="<?php echo lot_kir("Parol (Kamida 4 ta simvol)") ?>" />
						<label><?php echo lot_kir("Parol (Kamida 4 ta simvol)") ?></label>
					</div>
					<div class="text-center pt-5">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
							<input type="hidden" name="table[]" value="clients">
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

<?php if($id && $user_permissions['residual']['add_access'] === '1'): ?>
<!-- Add Residual -->
<div class="modal fade" id="addResidualModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Mavjud qoldiq jo'natish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="addResidualForm">
					<div id="residual_repeator">
						<div class="form-group">
							<div data-repeater-list="product">
								<div data-repeater-item>
									<div class="row mb-5">
										<div class="col-5">
											<select class="form-select form-select-solid form-select-sm select_content w-100" name="product_id" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Tovarni tanlang") ?>" required>
												<option></option>
												<?php foreach ($orders_arr as $key => $val) {
													echo '<option value="'.$val['product_id'].'" data-residual_goods_qty="'.($val['amount'] + $val['residual_date_amount']).'" data-cost="'.end($val['prices']).'">'.lot_kir($key).'</option>';
												} ?>
											</select>
										</div>
										<div class="col-7 text-end">
											<div class="d-flex">
												<input type="text" class="form-control form-control-sm form-control-solid mask_number cost" name="cost" placeholder="<?php echo lot_kir("Narhi")?>" required />
												<input type="text" class="form-control form-control-sm form-control-solid mask_number ms-3" name="qty" />
												<input type="hidden" class="residual_goods_qty" name="residual_goods_qty" />
												<a data-repeater-delete class="btn btn-sm btn-light-danger ms-3 px-2">
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
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid time_format_full" name="time" value="<?php echo date("d.m.Y H:i:s", strtotime($now))?>" placeholder="<?php echo lot_kir("Jo'natilgan vaqti") ?>" readonly required />
						<label><?php echo lot_kir("Jo'natilgan vaqti") ?></label>
					</div>
					<div class="form-floating mb-7">
						<textarea class="form-control form-control-solid" name="comment" placeholder="Izoh" style="height: 100px"></textarea>
						<label><?php echo lot_kir("Izoh") ?></label>
					</div>
					<div class="text-center pt-5">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
							<input type="hidden" name="client" value="<?php echo $id ?>">
							<input type="hidden" name="table" value="residual">
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

	<?php if($user_permissions[SCRIPTNAME]['add_access'] === '1'): ?>
		ajaxForm("#addClientForm", 'actions/add.php', true);
	<?php endif; ?>

	<?php if($id && $user_permissions['residual']['add_access'] === '1'): ?>

		var sold_table = $('.table[datatable="sold"]').DataTable({
			language: {emptyTable: "<?php echo lot_kir("Ma'lumot topilmadi!");?>"},
			paging:false,
			"aaSorting": [],
			info:false,
			buttons: [{extend:'print', text:'<i class="fas fa-print text-primary"></i>'}]}),
		payments_table = $('.table[datatable="payments"]').DataTable({
			language: {emptyTable: "<?php echo lot_kir("Ma'lumot topilmadi!");?>"},
			paging:false,
			"aaSorting": [],
			info:false,
			buttons: [{extend:'print', text:'<i class="fas fa-print text-primary"></i>'}]});

		sold_table.buttons().container().appendTo( $('.table_buttons_sold', sold_table.table('B').container() ) );
		payments_table.buttons().container().appendTo( $('.table_buttons_payments', payments_table.table('B').container() ) );


		ajaxForm("#addResidualForm", 'actions/add.php', true);

		$('#addResidualForm').on("change", '.select_content', function(){
			let cost = $(".select_content option[value="+$(this).val()+"]").data('cost');
			let residual_goods_qty = $(".select_content option[value="+$(this).val()+"]").data('residual_goods_qty');
			$(this).parent().parent().find('.cost').val(cost).next()
			$(this).parent().parent().find('.residual_goods_qty').val(residual_goods_qty).next()
			setTimeout(() => {
				$(this).parent().parent().find('.cost').next().focus();
			}, 100);
		});

		let repeater = $('#residual_repeator').repeater({
			show: function () {
				$(this).slideDown();
				$('#residual_repeator select').select2({minimumResultsForSearch: Infinity});
				Inputmask({
					numericInput: true,
					alias: "numeric",
					groupSeparator: " ",
					digits: 0,
					rightAlign: false,
				}).mask('#residual_repeator .mask_number');

			},
			hide: function (deleteElement) {
				$(this).slideUp(deleteElement);
			}
		});
	<?php endif; ?>

</script>

</body>
</html>