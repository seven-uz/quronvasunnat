<?php if($user_permissions['payments']['add_access'] === '1'):
$clients_modal = $Query->getN("clients c", [
	'fields' => 'c.id, u.fio',
	'order' => ['u.fio'],
	'join' => [
		['table' => 'users u', 'on' => 'u.id = c.user_id'],
	],
	'where' => [
		['column' => 'c.active', 'value' => "'1'"],
	],
]); ?>
<!-- Add Payment -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("To'lov qilish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="addPaymentForm">
					<div class="form-floating mb-7">
						<select class="form-select form-select-solid" name="client_id" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Dillerni tanlang") ?>" required>
							<option></option>
							<?php foreach ($clients_modal as $key => $val) {
								echo '<option value="'.$val['id'].'">'.lot_kir($val['fio']).'</option>';
							} ?>
						</select>
						<label><?php echo lot_kir("Diller") ?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid mask_number" name="amount" autocomplete="off" placeholder="<?php echo lot_kir("To'lov summasi") ?>" />
						<label><?php echo lot_kir("To'lov summasi")?></label>
					</div>
					<div class="form-floating mb-7">
						<input type="text" class="form-control form-control-solid time_format_full" name="time" value="<?php echo date("d.m.Y H:i:s", strtotime($now)) ?>" placeholder="<?php echo lot_kir("To'lov vaqti") ?>" />
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

<!--begin::Javascript-->
<script>var hostUrl = "assets/";</script>
<script src="assets/plugins/global/plugins.bundle.js"></script>
<script src="assets/js/scripts.bundle.js"></script>
<?php if($page !== 'auth' && http_response_code() !== 403): ?>
<script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script src="assets/js/widgets.bundle.js"></script>
<?php endif; ?>

<script>

	function ajaxForm(
		formContent,
		actionUrl,
		pageReload = false,
		resetForm = false,
		contentInContent = false,
		method = "POST"
	) {
		let form_button = $(formContent).find(':submit');
		let form_reset_button = $(formContent).find(':reset');

		$(formContent).on("submit", contentInContent, function (e) {
			e.preventDefault();
			$.ajax({
				url: actionUrl,
				type: method,
				data: new FormData(this),
				contentType: false,
				cache: false,
				processData: false,
				beforeSend: function() {
					$(form_reset_button).addClass('d-none');
					$(form_button).attr('disabled', 'disabled');
					$(form_button).find('.indicator-label').addClass('d-none');
					$(form_button).find('.indicator-progress').addClass('d-block');
				},
				success: function (data) {
					$(form_reset_button).removeClass('d-none');
					$(form_button).attr('disabled', false);
					$(form_button).find('.indicator-label').removeClass('d-none');
					$(form_button).find('.indicator-progress').removeClass('d-block');
					if (data == "success") {
						if (pageReload === true) {
							swal
								.fire({
									title: '<?php echo lot_kir("Amaliyot muvoffaqiyatli yakunlandi") ?>',
									icon: "success",
									customClass: {confirmButton: "btn btn-success m-btn m-btn--wide",}
								})
								.then(function () {
									setTimeout(() => {
										location.reload();
									}, 100);
								});
						} else if (pageReload === false) {
							swal.fire({
								title: '<?php echo lot_kir("Amaliyot muvoffaqiyatli yakunlandi") ?>',
								icon: "success",
								customClass: {confirmButton: "btn btn-success m-btn m-btn--wide",}
							});
						}
						if (resetForm === true) {
							$(formContent)[0].reset();
							$(formContent).find("select").selectpicker("refresh");
						}
					} else {
						swal.fire({
							html: data,
							icon: "error",
							customClass: {confirmButton: "btn btn-success m-btn m-btn--wide",}
						});
					}
				},
				error: function (data) {
					$(form_reset_button).removeClass('d-none');
					$(form_button).attr('disabled', false);
					$(form_button).find('.indicator-label').removeClass('d-none');
					$(form_button).find('.indicator-progress').removeClass('d-block');
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
		});
	}

	<?php if($user_permissions['payments']['add_access'] === '1'): ?>
	ajaxForm("#addPaymentForm", 'actions/add.php', true);
	<?php endif; ?>

	<?php if($page !== 'auth' && http_response_code() !== 404 && http_response_code() !== 403): ?>

	$('.table[datatable="true"]').DataTable({
		language: {
			search: '',
			searchPlaceholder: "<?php echo lot_kir("Qidirish...");?>",
      emptyTable: "<?php echo lot_kir("Ma'lumot topilmadi!");?>"
		},
		lengthMenu: [ 10, 25, 50, 100, 500 ],
		buttons: [{extend:'print', text:'<i class="fas fa-print text-primary"></i>'}],
		"aaSorting": [],
		dom: "<'card-header border-bottom-0 align-items-center'<'card-title'<'d-flex align-items-center flex-column flex-sm-row'lp>>B><'card-body pt-0 px-0 pb-0'<'table-responsive't>>"
	});

	$('.table[datatable="modal_type"]').DataTable({
		language: {
			search: '',
			searchPlaceholder: "<?php echo lot_kir("Qidirish...");?>",
      emptyTable: "<?php echo lot_kir("Ma'lumot topilmadi!");?>"
		},
		"aaSorting": [],
		dom: "<'table-reponsive mh-650px overflow-auto'<'d-flex justify-content-between align-items-center'<'d-flex align-items-center flex-column flex-sm-row'<'d-none d-sm-block ms-5'l>p>><'card-body pt-0 px-0 pb-0't>>"
	});

	$(".date_format").flatpickr({
		dateFormat: "d.m.Y",
	});

	$(".time_format").flatpickr({
		dateFormat: "d.m.Y H:i",
		enableTime: true,
		time_24hr: true,
	});

	$(".time_format_only").flatpickr({
		noCalendar: true,
		enableTime: true,
		time_24hr: true,
	});

	$(".time_format_full").flatpickr({
		dateFormat: "d.m.Y H:i:s",
		enableTime: true,
		enableSeconds: true,
		time_24hr: true,
	});

	Inputmask({
    alias: "numeric",
    groupSeparator: " ",
		rightAlign: false,
	}).mask(".mask_number");

	Inputmask({
		mask: "99 999-99-99",
	}).mask(".mask_phone");

	function formatMoney(number, decPlaces, decSep, thouSep) {
    decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
    decSep = typeof decSep === "undefined" ? "." : decSep;
    thouSep = typeof thouSep === "undefined" ? "," : thouSep;
    var sign = number < 0 ? "-" : "";
    var i = String(parseInt(number = Math.abs(Number(number) || 0).toFixed(decPlaces)));
    var j = (j = i.length) > 3 ? j % 3 : 0;

    return sign +
        (j ? i.substr(0, j) + thouSep : "") +
        i.substr(j).replace(/(\decSep{3})(?=\decSep)/g, "$1" + thouSep) +
        (decPlaces ? decSep + Math.abs(number - i).toFixed(decPlaces).slice(2) : "");
	}

	<?php if($user_permissions[SCRIPTNAME]['delete_access'] === '1'): ?>
	$('body').on("click", ".deleteBtn", function () {
		let thisID = $(this).data("id");
		let tableName = $(this).data("table");
		let thisRow = $('table[data-table="'+tableName+'"] tr[data-id="'+thisID+'"]');

		if (tableName != null && tableName != undefined && tableName != '') {
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
						url: "actions/delete.php",
						type: "POST",
						data: {
							id: thisID,
							table: tableName,
						},
						success: function (data) {
							if (data == "success") {
								swal
									.fire({
										title: '<?php echo lot_kir("O'chirildi")?>',
										icon: "success",
										customClass: {confirmButton: "btn btn-success m-btn m-btn--wide",}
									})
									.then(function () {
										$(thisRow).addClass("d-none");
										$(thisRow).remove();
									});
							} else {
								swal.fire({
									html: data,
									icon: "error",
									customClass: {confirmButton: "btn btn-success m-btn m-btn--wide",}
								});
							}
						},
					});
				}
			});
		} else {
			swal.fire({
				html: '<?php echo lot_kir("Jadval topilmadi")?>',
				icon: "error",
				customClass: {confirmButton: "btn btn-success m-btn m-btn--wide",}
			});
		}
	});
	<?php endif; ?>

	<?php endif; ?>
</script>