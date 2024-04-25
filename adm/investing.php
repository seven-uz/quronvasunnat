<?php

include 'core/index.php';

$page_title = lot_kir("Investitsiya");

include 'inc/head.php';

$header['title'] = lot_kir("Invistitsiya");
$header['breadcrumb'] = [0 => ['val' => lot_kir("Asosiy sahifa"), 'link' => '/'], $header['title']];
$header['add_btn'] = '<div class="app-navbar-item ms-1 ms-md-3"><a data-bs-toggle="modal" data-bs-target="#addInvestingModal" class="btn btn-sm btn-primary"><i class="fas fa-plus pe-0 d-sm-none d-inline-block"></i><span class="d-sm-inline-block d-none">'.lot_kir("Investitsiya kiritish").'</span></a></div>';

include 'inc/begin_body.php';

$investing = $Query->getN("investing", [
	'fields' => 'id, amount, date, comment',
	'order' => ['date desc', 'id desc'],
]);
?>

<div class="app-content flex-column-fluid">
	<div class="app-container container-fluid">
		<div class="card card-flush">
			<div class="card-body p-0">
				<div class="table-responsive">
					<table class="table align-middle table-row-dashed">
						<thead>
							<tr class="text-primary fw-bold text-uppercase">
								<th class="text-center mw-50px"><?php echo lot_kir("Kiritilgan sana") ?></th>
								<th class="text-center"><?php echo lot_kir("Miqdori") ?></th>
								<th><?php echo lot_kir("Izoh") ?></th>
								<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1' || $user_permissions[SCRIPTNAME]['delete_access'] === '1'): ?>
									<th class="text-end pe-5"><?php echo lot_kir("Amallar") ?></th>
								<?php endif; ?>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($investing as $key => $val) {
								echo '<tr>
									<td align="center" data-order="'.$val['date'].'">'.dod($val['date']).'</td>
									<td align="center" data-order="'.$val['amount'].'">'.nf($val['amount']).'</td>
									<td>'. $val['comment'] .'</td>'.
									actionsFunction('editInvestingModal', [
										'data-id' => $val['id'],
										'data-amount' => $val['amount'],
										'data-date' => dateSimple($val['date']),
										'data-comment' => $val['comment'],
										'data-table' => 'investing',
									], $user_permissions['investing']).'
								</tr>';
							} ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include 'inc/end_body.php'; ?>

<?php if($user_permissions[SCRIPTNAME]['add_access'] === '1'): ?>
<!-- Add investing -->
<div class="modal fade" id="addInvestingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Invistitsiya qilish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="addInvestingForm">
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid mask_number" name="amount" placeholder="<?php echo lot_kir("Investitsiya summasini kiriting") ?>" required />
						<label><?php echo lot_kir("Miqdori") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid date_format" name="date" value="<?php echo date("d.m.Y")?>" placeholder="<?php echo lot_kir("Investitsiya kiritilgan vaqti") ?>" readonly required />
						<label><?php echo lot_kir("Kiritilgan vaqti") ?></label>
					</div>
					<div class="form-floating mb-7">
						<textarea class="form-control form-control-solid" name="comment" placeholder="Izoh" style="height: 100px"></textarea>
						<label><?php echo lot_kir("Izoh") ?></label>
					</div>
					<div class="text-center pt-5">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
							<input type="hidden" name="table" value="investing">
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
<!-- Edit investing -->
<div class="modal fade" id="editInvestingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Invistitsiyani o'zgartirish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="editInvestingForm">
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid mask_number" name="amount" placeholder="<?php echo lot_kir("Investitsiya summasini kiriting") ?>" required />
						<label><?php echo lot_kir("Miqdori") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid date_format" name="date" value="<?php echo date("d.m.Y")?>" placeholder="<?php echo lot_kir("Investitsiya kiritilgan vaqti") ?>" readonly required />
						<label><?php echo lot_kir("Kiritilgan vaqti") ?></label>
					</div>
					<div class="form-floating mb-7">
						<textarea class="form-control form-control-solid" name="comment" placeholder="Izoh" style="height: 100px"></textarea>
						<label><?php echo lot_kir("Izoh") ?></label>
					</div>
					<div class="text-center pt-5">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
							<input type="hidden" name="id">
							<input type="hidden" name="table" value="investing">
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
	ajaxForm("#addInvestingForm", 'actions/add.php', true);
	<?php endif; ?>

	<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1'): ?>
	$('body').on("click", ".editBtn", function(){

		let id = $(this).data('id');
		let amount = $(this).data('amount');
		let date = $(this).data('date');
		let comment = $(this).data('comment');

		$(".modal#editInvestingModal .modal-header h2").html('<?php echo lot_kir("O'zgartirish") ?>: '+amount);
		$("#editInvestingForm [name='amount']").val(amount);
		$("#editInvestingForm [name='date']").val(date);
		$("#editInvestingForm [name='comment']").val(comment);
		$("#editInvestingForm [name='id']").val(id);

	});

	ajaxForm("#editInvestingForm", 'actions/edit.php', true);
	<?php endif; ?>

</script>

</body>
</html>