<?php

include 'core/index.php';

$page_title = lot_kir("Diller sotgan tovarlar");

include 'inc/head.php';

$header['title'] = lot_kir("Diller sotgan tovarlar");
$header['breadcrumb'] = [0 => ['val' => lot_kir("Asosiy sahifa"), 'link' => '/'], $header['title']];

if($_SESSION['role_value'] === 'customer'){
	$soldout_goods = $Query->getN("soldout_by_diller r", [
		'fields' => 'r.id, r.client_id, r.product_id, r.qty, r.created_at, r.user_id,
		p.title as title, u.fio',
		'join' => [
			['table' => 'users u', 'on' => 'u.id = r.user_id'],
			['table' => 'products p', 'on' => 'p.id = r.product_id'],
		],
		'order' => ['created_at desc', 'id desc'],
		'where' => [
			['column' => 'r.client_id', 'value' => $Query->getN("clients", ['fields' => 'id, user_id','where' => [['column' => 'user_id', 'value' => intval($_SESSION['id'])],],])[0]['id']]
		]
	]);
}else{
	$soldout_goods = $Query->getN("soldout_by_diller r", [
		'fields' => 'r.id, r.client_id, r.product_id, r.qty, r.created_at, r.user_id,
		p.title as title, u.fio',
		'join' => [
			['table' => 'users u', 'on' => 'u.id = r.user_id'],
			['table' => 'products p', 'on' => 'p.id = r.product_id'],
		],
		'order' => ['created_at desc', 'id desc'],
	]);
}

$soldout_goods_dillers = array_unique(array_column($soldout_goods, 'fio'));

$products = $Query->getN("products", [
	'fields' => 'id, title, compound, reaper_1_price, reaper_2_price, reaper_helper_price, tailor_price, iron_man_price, selling_price, additional_expenses, info, photo, active',
	'order' => ['title'],
]);

include 'inc/begin_body.php';
?>

<div class="app-content flex-column-fluid">
	<div class="app-container container-fluid">
		<div class="card card-flush">
			<table class="table align-middle table-row-dashed" data-table="soldout_goods">
				<thead>
					<tr class="text-primary fw-bold text-uppercase">
						<th class="text-center ps-5">№</th>
						<th class="text-center"><?php echo lot_kir("Vaqti") ?></th>
						<th><?php echo lot_kir("Kimga sotilgan") ?></th>
						<th><?php echo lot_kir("Model") ?></th>
						<th class="text-center"><?php echo lot_kir("Soni") ?></th>
						<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1' || $user_permissions[SCRIPTNAME]['delete_access'] === '1'): ?>
							<th class="text-end pe-5"><?php echo lot_kir("Amallar") ?></th>
						<?php endif; ?>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($soldout_goods as $row) {
						echo '<tr data-id="'.$row['id'].'">
							<td class="text-center pe-0 ps-5">' . ++$num . '</td>
							<td class="text-center pe-0">' . dwt($row['created_at']) . '</td>
							<td>' . lot_kir($row['fio']) .'</td>
							<td>' . lot_kir($row['title']) .'</td>
							<td class="text-center pe-0" data-order="'.$row['qty'].'">' . nf($row['qty']) . '</td>
							'.actionsFunction('editResidualModal', [
								'data-id' => $row['id'],
								'data-user_id' => $row['user_id'],
								'data-client_id' => $row['client_id'],
								'data-product_id' => $row['product_id'],
								'data-qty' => $row['qty'],
								'data-table' => 'soldout_goods',
							], $user_permissions['soldout_goods']).'
						</tr>';
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php include 'inc/end_body.php'; ?>

<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1'): ?>
<!-- Edit Residual -->
<div class="modal fade" id="editResidualModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
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
				<form id="editResidualForm">
					<div id="soldout_goods_repeator">
						<div class="form-group">
							<div class="form-floating mb-4">
								<select class="form-select form-select-solid form-select-sm select_content w-100" name="product_id" data-hide-search="false" data-control="select2" data-placeholder="<?php echo lot_kir("Tovarni tanlang") ?>" required>
									<option></option>
									<?php foreach ($products as $key => $val) {
										echo '<option value="'.$val['id'].'">'.lot_kir($val['title']).'</option>';
									} ?>
								</select>
								<label class="required"><?php echo lot_kir('Model')?></label>
							</div>
							<div class="form-floating">
								<input type="text" class="form-control form-control-sm form-control-solid mask_number" name="qty" placeholder="<?php echo lot_kir("Soni")?>" required />
								<label class="required"><?php echo lot_kir('Soni')?></label>
							</div>
						</div>
					</div>
					<div class="text-center pt-5">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
							<input type="hidden" name="table[]" value="soldout_goods">
							<input type="hidden" name="table[]" value="soldout_by_diller">
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

	let pageTable = $('.table[data-table="soldout_goods"]').DataTable({
		language: {
			search: '',
			searchPlaceholder: "<?php echo lot_kir("Qidirish...");?>",
      emptyTable: "<?php echo lot_kir("Ma'lumot topilmadi!");?>"
		},
		buttons: [{extend:'print', text:'<i class="fas fa-print text-primary"></i>'}],
		"aaSorting": [],
		dom: "<'card-header border-bottom-0 align-items-center'<'card-title'<'d-flex align-items-center flex-column flex-sm-row'<'d-none d-sm-block'l>p>><'d-flex align-items-center'<'me-5 dillerFilter'><'me-5'f>B>><'card-body pt-0 px-0 pb-0'<'table-responsive't>>"
	});

	<?php if($_SESSION['role_value'] === 'admin'): ?>
	$('.dillerFilter').append(`
	<select class="form-select form-select-solid dillerFilterField" data-control="select2" data-hide-search="true">
		<option value=""><?php echo lot_kir("Barcha diller");?></option>
		<?php foreach ($soldout_goods_dillers as $val) {
			echo '<option value="'.lot_kir($val).'">'.lot_kir($val).'</option>';
		} ?>
	</select>`);

	$('.dillerFilterField').on('change', function(){
		pageTable.column(2).search(this.value).draw()
	})
	<?php endif; ?>

	<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1' || $user_permissions[SCRIPTNAME]['delete_access'] === '1'): ?>
	$("#addTypeModal, #addResidualModal, #editTypeModal, #editResidualModal").on("shown.bs.modal", function(){
		let ID = $(this).attr('id');
		$('select').select2({minimumResultsForSearch: Infinity, dropdownParent: "#"+ID });
	});
	<?php endif; ?>

	<?php if($user_permissions[SCRIPTNAME]['add_access'] === '1'): ?>
		ajaxForm("#addResidualForm", 'actions/add.php', true);
		ajaxForm("#addCategoryForm", 'actions/add.php', true);
		ajaxForm("#addTypeForm", 'actions/add.php', true);
	<?php endif; ?>


	<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1'): ?>
	$('body').on("click", ".editBtn", function(){

		let id = $(this).data('id');
		let qty = $(this).data('qty');
		let product_id = $(this).data('product_id');

		$(".modal#editResidualModal .modal-header h2").html('<?php echo lot_kir("O'zgartirish") ?>: '+name);
		$("#editResidualForm select[name=product_id] option[value=" + product_id + "]").prop('selected', true);
		$("#editResidualForm [name='qty']").val(qty);
		$("#editResidualForm [name='id']").val(id);
	});

	ajaxForm("#editResidualForm", 'actions/edit.php', true);
	<?php endif; ?>

</script>
</body>
</html>