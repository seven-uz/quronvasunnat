<?php

include 'core/index.php';

$page_title = lot_kir("Yuborilgan ostatkalar");

include 'inc/head.php';

$header['title'] = lot_kir("Qaytarilgan yuklar");
$header['breadcrumb'] = [0 => ['val' => lot_kir("Asosiy sahifa"), 'link' => '/'], $header['title']];

$residual = $Query->getN("residual r", [
	'fields' => 'r.id, r.user_id, r.client_id, r.product_id, r.qty, r.send_time, r.comment,
	p.title as title, u.fio',
	'join' => [
		['table' => 'users u', 'on' => 'u.id = r.user_id'],
		['table' => 'products p', 'on' => 'p.id = r.product_id'],
	],
	'order' => ['send_time desc'],
	// 'ignore' => ['where' => " AND mp.deleted <> '1'"],
]);

$products = $Query->getN("products", [
	'fields' => 'id, title, compound, reaper_1_price, reaper_2_price, reaper_helper_price, tailor_price, iron_man_price, selling_price, additional_expenses, info, photo, active',
	'order' => ['title'],
]);

include 'inc/begin_body.php';
?>

<div class="app-content flex-column-fluid">
	<div class="app-container container-fluid">
		<div class="card card-flush">
			<table class="table align-middle table-row-dashed" data-table="residual" datatable="true">
				<thead>
					<tr class="text-primary fw-bold text-uppercase">
						<th class="text-center ps-5">№</th>
						<th class="text-center"><?php echo lot_kir("Vaqti") ?></th>
						<th><?php echo lot_kir("Diller") ?></th>
						<th><?php echo lot_kir("Model") ?></th>
						<th class="text-center"><?php echo lot_kir("Miqdori") ?></th>
						<th><?php echo lot_kir("Izoh") ?></th>
						<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1' || $user_permissions[SCRIPTNAME]['delete_access'] === '1'): ?>
							<th class="text-end pe-5"><?php echo lot_kir("Amallar") ?></th>
						<?php endif; ?>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($residual as $row) {
						if($_SESSION['role_value'] !== 'admin'){ if($row['user_id'] != $_SESSION['id']) continue; }
						echo '<tr data-id="'.$row['id'].'">
							<td class="text-center pe-0 ps-5">' . ++$num . '</td>
							<td class="text-center pe-0">' . dwt($row['send_time']) . '</td>
							<td>' . lot_kir($row['fio']) .'</td>
							<td>' . lot_kir($row['title']) .'</td>
							<td class="text-center pe-0" data-order="'.$row['qty'].'">' . nf($row['qty']) . '</td>
							<td>' . $row['comment'] . '</td>
							'.actionsFunction('editResidualModal', [
								'data-id' => $row['id'],
								'data-send_time' => date("d.m.Y H:i:s", strtotime($row['send_time'])),
								'data-user_id' => $row['user_id'],
								'data-client_id' => $row['client_id'],
								'data-product_id' => $row['product_id'],
								'data-qty' => $row['qty'],
								'data-comment' => $row['comment'],
								'data-table' => 'residual',
							], $user_permissions['residual']).'
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
					<div id="residual_repeator">
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
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid time_format_full" name="time" placeholder="<?php echo lot_kir("Jo'natilgan vaqti") ?>" readonly required />
						<label><?php echo lot_kir("Jo'natilgan vaqti") ?></label>
					</div>
					<div class="form-floating mb-7">
						<textarea class="form-control form-control-solid" name="comment" placeholder="Izoh" style="height: 100px"></textarea>
						<label><?php echo lot_kir("Izoh") ?></label>
					</div>
					<div class="text-center pt-5">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
							<input type="hidden" name="table" value="residual">
							<input type="hidden" name="id" />
							<input type="hidden" name="user_id" />
							<input type="hidden" name="client_id" />
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

			let table_name = $(this).data('table');

			if(table_name === 'residual'){
				let id = $(this).data('id');
				let send_time = $(this).data('send_time');
				let user_id = $(this).data('user_id');
				let client_id = $(this).data('client_id');
				let product_id = $(this).data('product_id');
				let qty = $(this).data('qty');
				let comment = $(this).data('comment');

				$(".modal#editResidualModal .modal-header h2").html('<?php echo lot_kir("O'zgartirish") ?>');
				$("#editResidualForm [name='time']").val(send_time);
				$("#editResidualForm [name='user_id']").val(user_id);
				$("#editResidualForm [name='client_id']").val(client_id);
				$("#editResidualForm [name='product_id']").val(product_id);
				$("#editResidualForm [name='qty']").val(qty);
				$("#editResidualForm [name='comment']").val(comment);
				$("#editResidualForm [name='id']").val(id);
			}
		});

		ajaxForm("#editResidualForm", 'actions/edit.php', true);

	<?php endif; ?>

</script>
</body>
</html>