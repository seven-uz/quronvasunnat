<?php

include 'core/index.php';

$page_title = lot_kir("To'lovlar");

include 'inc/head.php';

$header['title'] = lot_kir("To'lovlar");
$header['breadcrumb'] = [0 => ['val' => lot_kir("Asosiy sahifa"), 'link' => '/'], $header['title']];

include 'inc/begin_body.php';

$payments = $Query->getN("payments p", [
	'fields' => 'p.id, p.client_id, p.amount, p.payment_time, p.comment, u.fio',
	'join' => [
		['table' => 'clients c', 'on' => 'c.id = p.client_id'],
		['table' => 'users u', 'on' => 'c.user_id = u.id'],
	],
	'order' => ['p.payment_time desc','p.id desc'],
]);

$clients = $Query->getN("clients c", [
	'fields' => 'c.id, c.user_id, u.fio, u.phone, c.active',
	'order' => ['u.fio'],
	'join' => [
		['table' => 'users u', 'on' => 'u.id = c.user_id'],
	],
]);

if($_SESSION['role_value'] === 'customer'){
	$client_id = $Query->getN("clients", ['fields' => 'id','where' => [['column' => 'user_id', 'value' => $_SESSION['id']],],])[0]['id'];
}

?>

<div class="app-content flex-column-fluid">
	<div class="app-container container-fluid">
		<div class="card card-flush">
			<table class="table align-middle table-row-dashed" datatable="custom">
				<thead>
					<tr class="text-primary fw-bold text-uppercase">
						<th class="text-center"><?php echo lot_kir("To'lov sanasi") ?></th>
						<th class="text-end"><?php echo lot_kir("Summasi") ?></th>
						<?php if($_SESSION['role_value'] !== 'customer'){?><th><?php echo lot_kir("To'lovchi") ?></th><?php } ?>
						<th><?php echo lot_kir("Izoh") ?></th>
						<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1' || $user_permissions[SCRIPTNAME]['delete_access'] === '1'): ?>
							<th class="text-end pe-5"><?php echo lot_kir("Amallar") ?></th>
						<?php endif; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($payments as $key => $val) {
						if($_SESSION['role_value'] === 'customer' && $val['client_id'] != $client_id) continue;
						echo '<tr>
							<td align="center" data-order="'.$val['payment_time'].'">'.dwt($val['payment_time']).'</td>
							<td align="right" data-amount="'.$val['amount'].'">'.nf($val['amount']).'</td>';
							if($_SESSION['role_value'] !== 'customer') echo '<td>'. lot_kir($val['fio']) .'</td>';
							echo '<td>'. $val['comment'] .'</td>'.
							actionsFunction('editPaymentModal', [
								'data-id' => $val['id'],
								'data-client_id' => $val['client_id'],
								'data-amount' => $val['amount'],
								'data-name' => $val['fio'],
								'data-time' => dateSimple($val['payment_time']),
								'data-comment' => $val['comment'],
								'data-table' => 'payments',
							], $user_permissions['payments']).'
						</tr>';
					} ?>
				</tbody>
				<tfoot>
					<th class="text-center fw-bold fs-5">Jami: </th>
					<th class="text-end fw-bold fs-5"></th>
					<th></th>
					<th></th>
					<th></th>
				</tfoot>
			</table>
		</div>
	</div>
</div>
<?php include 'inc/end_body.php'; ?>

<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1'): ?>
<!-- Edit payments -->
<div class="modal fade" id="editPaymentModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("To'lovlarni o'zgartirish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="editPaymentForm">
					<div class="form-floating mb-7">
						<select class="form-select form-select-solid" name="client_id" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Dillerni tanlang") ?>" required>
							<option></option>
							<?php foreach ($clients as $key => $val) {
								echo '<option value="'.$val['id'].'">'.lot_kir($val['fio']).'</option>';
							} ?>
						</select>
						<label><?php echo lot_kir("Diller") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid mask_number" name="amount" placeholder="<?php echo lot_kir("To'lov summasi") ?>" />
						<label><?php echo lot_kir("To'lov summasi")?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid date_format" name="time" placeholder="<?php echo lot_kir("To'lov vaqti") ?>" />
						<label><?php echo lot_kir("To'lov vaqti")?></label>
					</div>
					<div class="form-floating mb-7">
						<textarea class="form-control form-control-solid" name="comment" placeholder="Izoh" style="height: 100px"></textarea>
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
							<input type="hidden" name="id">
							<input type="hidden" name="table" value="payments">
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

	let pageTable = $('.table[datatable="custom"]').DataTable({
		language: {
			search: '',
			searchPlaceholder: "<?php echo lot_kir("Qidirish...");?>",
      emptyTable: "<?php echo lot_kir("Ma'lumot topilmadi!");?>"
		},
		lengthMenu: [ 10, 25, 50, 100, 500 ],
		buttons: [{extend:'print', text:'<i class="fas fa-print text-primary"></i>'}],
		"aaSorting": [],
		dom: "<'card-header border-bottom-0 align-items-center'<'card-title'<'d-flex align-items-center flex-column flex-sm-row'lp>><'d-flex align-items-center'<'me-5 filterClient'><'me-5'f>B>><'card-body pt-0 px-0 pb-0'<'table-responsive't>>",
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
					.column(1)
					.data()
					.reduce((a, b) => intVal(a) + intVal(b), 0);
			pageTotal = api
					.column(1, { page: 'current' })
					.data()
					.reduce((a, b) => intVal(a) + intVal(b), 0);

			// Update footer
			api.column(1).footer().innerHTML = pageTotal.toFixed(1).replace(/\d(?=(\d{3})+\.)/g, '$& ');
    }
	});

	<?php if($_SESSION['role_value'] === 'admin'): ?>
	$('.filterClient').append(`
	<select class="form-select form-select-solid filterClientField" data-control="select2" data-hide-search="true">
		<option value=""><?php echo lot_kir("Barcha diller");?></option>
		<?php foreach ($clients as $val) {
			echo '<option value="'.lot_kir($val['fio']).'">'.lot_kir($val['fio']).'</option>';
		} ?>
	</select>`);

	$('.filterClientField').on('change', function(){
		pageTable.column(2).search(this.value).draw()
	})
	<?php endif; ?>

	<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1'): ?>
	$('body').on("click", ".editBtn", function(){

		let id = $(this).data('id');
		let client_id = $(this).data('client_id');
		let amount = $(this).data('amount');
		let time = $(this).data('time');
		let comment = $(this).data('comment');

		$(".modal#editPaymentModal .modal-header h2").html('<?php echo lot_kir("O'zgartirish") ?>: '+amount);
		$("#editPaymentForm [name='amount']").val(amount);
		$("#editPaymentForm [name='client_id'] option[value='"+client_id+"']").prop('selected', true);
		$("#editPaymentForm [name='time']").val(time);
		$("#editPaymentForm [name='comment']").val(comment);
		$("#editPaymentForm [name='id']").val(id);

		$('#editPaymentForm select').select2({minimumResultsForSearch: Infinity });
	});

	ajaxForm("#editPaymentForm", 'actions/edit.php', true);
	<?php endif; ?>

</script>

</body>
</html>