<?php

include 'core/index.php';

$page_title = lot_kir("Asosiy sahifa");

include 'inc/head.php';

$first_day_this_month = date("Y-m-01", strtotime($now));

$header['title'] = lot_kir("Asosiy sahifa");

include 'inc/begin_body.php';

?>

<div class="app-content flex-column-fluid">
	<div class="app-container container-fluid">

	</div>
</div>

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
										<div class="col-6">
											<select class="form-select form-select-solid form-select-sm select_content w-100" name="product_id" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Tovarni tanlang") ?>" required>
												<option></option>
												<?php foreach ($orders_arr as $key => $val) {
													echo '<option value="'.$val['product_id'].'" data-residual_goods_qty="'.($val['amount'] + $val['residual_date_amount']).'" data-cost="'.end($val['prices']).'">'.lot_kir($key).'</option>';
												} ?>
											</select>
										</div>
										<div class="col-6 text-end">
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

<?php include 'inc/end_body.php'; ?>

<?php include 'inc/javascript.php'; ?>

<script>

</script>

</body>
</html>