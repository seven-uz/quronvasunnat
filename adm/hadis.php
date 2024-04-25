<?php

include 'core/index.php';

$page_title = lot_kir("Yetkazib beruvchilar");

include 'inc/head.php';

$header['title'] = lot_kir("Tovarlar");
$header['breadcrumb'] = [0 => ['val' => lot_kir("Asosiy sahifa"), 'link' => '/'], $header['title']];
$header['add_btn'] = '<div class="app-navbar-item ms-1 ms-md-3"><a data-bs-toggle="modal" data-bs-target="#addHadis" class="btn btn-sm btn-primary"><i class="fas fa-plus pe-0 d-sm-none d-inline-block"></i><span class="d-sm-inline-block d-none">'.lot_kir("Qo'shish").'</span></a></div>';

$hadislarQ = mysqli_query($db, "SELECT h.id, h.title, rv.name as roviy, rvch.name as rivoyatchi, k.title as kunya FROM hadislar h LEFT JOIN roviylar rv ON h.id_roviy = rv.id LEFT JOIN rivoyatchilar rvch ON h.id_rivoyatchi = rvch.id LEFT JOIN kunyalar k ON rvch.id_kunya = k.id ORDER BY h.id DESC");
while($row = $hadislarQ->fetch_assoc()){
	$hadislar[] = $row;
}
$roviylar = $Query->getN("roviylar", [
	'order' => ['name'],
]);
$rivoyatchi = $Query->getN("rivoyatchilar", [
	'order' => ['name'],
]);
include 'inc/begin_body.php';

?>

<div class="app-content flex-column-fluid">
	<div class="app-container container-fluid">
		<div class="card card-flush">
			<table class="table align-middle table-row-dashed" data-table="hadislar" datatable="true">
				<thead>
					<tr class="text-primary fw-bold text-uppercase">
						<th class="ps-4">№</th>
						<th><?php echo lot_kir("Sarlavha") ?></th>
						<th><?php echo lot_kir("Roviy") ?></th>
						<th><?php echo lot_kir("Rivoyatchi") ?></th>
						<th class="text-end pe-5"><?php echo lot_kir("Amallar") ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($hadislar as $key => $val) {
						echo '<tr>
							<td class="ps-4">'.++$hadislar_num.'</td>
							<td><a href="?id='.$val['id'].'" class="text-gray-800 text-hover-primary fs-5 fw-bold">'.$val['title'].'</a></td>
							<td>'.$val['roviy'].'</td>
							<td>'.$val['rivoyatchi'].'</td>
							<td class="text-end pe-5 text-nowrap">
								<button class="btn btn-icon btn-light-warning w-30px h-30px me-3 editBtn" data-bs-toggle="modal" data-bs-target="#editHadis">
									<i class="ki-duotone ki-notepad-edit fs-3">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
										<span class="path4"></span>
									</i>
								</button>
								<button class="btn btn-icon btn-light-danger w-30px h-30px deleteBtn" data-bs-toggle="modal" data-id="'.$val['id'].'" data-table="hadislar">
									<i class="ki-duotone ki-trash fs-3">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
										<span class="path4"></span>
										<span class="path5"></span>
									</i>
								</button>
							</td>
							' . actionsFunction('editHadis', [
								'data-id' => $val['id'],
								'data-active' => $val['active'],
								'data-table' => 'hadislar',
							], $user_permissions['hadislar']).'
						</tr>';
					} ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php include 'inc/end_body.php'; ?>

<!-- Add  -->
<div class="modal fade" id="addHadis" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Tovar qo'shish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="addHadisForm">
					<div class="form-floating mb-4">
						<select class="form-select" name="id_roviy" data-control="select2" data-placeholder="<?php echo lot_kir("Birini tanlang") ?>" data-dropdown-parent="#addHadis" required>
							<option></option>
							<?php foreach ($roviylar as $key => $val) {
							echo '<option value="'.$val['id'].'">'.$val['name'].'</option>';
							} ?>
						</select>
						<label><?php echo lot_kir("Roviy") ?></label>
					</div>
					<div class="form-floating mb-4">
						<select class="form-select" name="id_roviy" data-control="select2" data-placeholder="<?php echo lot_kir("Birini tanlang") ?>" data-dropdown-parent="#addHadis" required>
							<option></option>
							<?php foreach ($rivoyatchi as $key => $val) {
							echo '<option value="'.$val['id'].'">'.$val['name'].'</option>';
							} ?>
						</select>
						<label><?php echo lot_kir("Rivoyatchi") ?></label>
					</div>
					<div class="form-floating mb-4">
						<input type="text" class="form-control" name="name" placeholder="<?php echo lot_kir("Sarlavha AR") ?>" required />
						<label><?php echo lot_kir("Sarlavha AR") ?></label>
					</div>
					<div class="form-floating mb-4">
						<input type="text" class="form-control" name="name" placeholder="<?php echo lot_kir("Sarlavha UZ") ?>" required />
						<label><?php echo lot_kir("Sarlavha UZ") ?></label>
					</div>
					<div class="form-floating mb-4">
						<textarea class="form-control" name="info" placeholder="Matni AR" style="height: 100px"></textarea>
						<label><?php echo lot_kir("Matni AR") ?></label>
					</div>
					<div class="form-floating mb-4">
						<textarea class="form-control" name="info" placeholder="Matn UZ" style="height: 100px"></textarea>
						<label><?php echo lot_kir("Matn UZ") ?></label>
					</div>
					<div class="form-floating mb-4">
						<textarea class="form-control" name="mano" placeholder="Izoh" style="height: 100px"></textarea>
						<label><?php echo lot_kir("Manosi") ?></label>
					</div>
					<div class="text-center pt-5">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
							<input type="hidden" name="table" value="hadislar">
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

<!-- Edit  -->
<div class="modal fade" id="editHadis" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
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
				<form id="editHadisForm">
					<div class="input-group mb-4">
						<div class="form-floating">
							<select class="form-control form-select" name="id_roviy" data-control="select2" data-placeholder="<?php echo lot_kir("Birini tanlang") ?>" data-dropdown-parent="#editHadis" required>
								<option></option>
								<?php foreach ($roviylar as $key => $val) {
								echo '<option value="'.$val['id'].'">'.$val['name'].'</option>';
								} ?>
							</select>
							<label><?php echo lot_kir("Roviy") ?></label>
						</div>
						<div>
							<a data-bs-toggle="modal" data-bs-target="#addRoviyModal" class="btn btn-sm btn-primary ms-4 p-6"><i class="fas fa-plus pe-0"></i></a>
						</div>
					</div>
					<div class="input-group mb-4">
						<div class="form-floating">
							<select class="form-select" name="id_roviy" data-control="select2" data-placeholder="<?php echo lot_kir("Birini tanlang") ?>" data-dropdown-parent="#editHadis" required>
								<option></option>
								<?php foreach ($rivoyatchi as $key => $val) {
								echo '<option value="'.$val['id'].'">'.$val['name'].'</option>';
								} ?>
							</select>
							<label><?php echo lot_kir("Rivoyatchi") ?></label>
						</div>
						<div>
							<a data-bs-toggle="modal" data-bs-target="#addRivoyatchiModal" class="btn btn-sm btn-primary ms-4 p-6"><i class="fas fa-plus pe-0"></i></a>
						</div>
					</div>
					<div class="form-floating mb-4">
						<input type="text" class="form-control" name="name" placeholder="<?php echo lot_kir("Sarlavha AR") ?>" required />
						<label><?php echo lot_kir("Sarlavha AR") ?></label>
					</div>
					<div class="form-floating mb-4">
						<input type="text" class="form-control" name="name" placeholder="<?php echo lot_kir("Sarlavha UZ") ?>" required />
						<label><?php echo lot_kir("Sarlavha UZ") ?></label>
					</div>
					<div class="form-floating mb-4">
						<textarea class="form-control" name="info" placeholder="Matni AR" style="height: 100px"></textarea>
						<label><?php echo lot_kir("Matni AR") ?></label>
					</div>
					<div class="form-floating mb-4">
						<textarea class="form-control" name="info" placeholder="Matn UZ" style="height: 100px"></textarea>
						<label><?php echo lot_kir("Matn UZ") ?></label>
					</div>
					<div class="form-floating mb-4">
						<textarea class="form-control" name="mano" placeholder="Izoh" style="height: 100px"></textarea>
						<label><?php echo lot_kir("Manosi") ?></label>
					</div>
					<div class="text-center pt-5">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
							<input type="hidden" name="table" value="hadislar">
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

<div class="modal fade" id="addRoviyModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Roviy qo'shish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="addRoviyForm">
					<div class="form-floating mb-4">
						<input type="text" class="form-control" name="name" placeholder="<?php echo lot_kir("Nomi nomi") ?>" required />
						<label><?php echo lot_kir("Roviy nomi") ?></label>
					</div>
					<div class="text-center pt-5">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
							<input type="hidden" name="table" value="roviylar">
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

<div class="modal fade" id="addRivoyatchiModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-450px">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h2 class="fw-bold"><?php echo lot_kir("Rivoyatchi qo'shish") ?></h2>
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
			</div>
			<div class="modal-body">
				<form id="addRivoyatchiForm">
					<div class="form-floating mb-4">
						<select class="form-control form-select" name="id_roviy" data-hide-search="true" data-control="select2" data-placeholder="<?php echo lot_kir("Birini tanlang") ?>" data-dropdown-parent="#addRivoyatchiModal" required>
							<option value="2">Roziyallohu anhu</option>
							<option value="3">Roziyallohu anho</option>
							<option value="1">Sollallohu alayhi va sallam</option>
						</select>
						<label><?php echo lot_kir("Kunya") ?></label>
					</div>
					<div class="form-floating mb-4">
						<input type="text" class="form-control" name="name" placeholder="<?php echo lot_kir("Nomi") ?>" required />
						<label><?php echo lot_kir("Sarlavha AR") ?></label>
					</div>
					<div class="text-center pt-5">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"><?php echo lot_kir("Inkor etish") ?></button>
						<button type="submit" class="btn btn-primary">
							<input type="hidden" name="table" value="rivoyatchilar">
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

<?php include 'inc/javascript.php'; ?>

<script>

	<?php if($user_permissions[SCRIPTNAME]['add_access'] === '1'): ?>
	ajaxForm("#addHadisForm", 'actions/add.php', true);
	<?php endif; ?>

	<?php if($user_permissions[SCRIPTNAME]['edit_access'] === '1'): ?>
	$('body').on("click", ".editBtn", function(){

		let id = $(this).data('id');
		let name = $(this).data('name');
		let phone = $(this).data('phone');
		let info = $(this).data('info');
		let active = $(this).data('active');

		$(".modal#editHadis .modal-header h2").html('<?php echo lot_kir("O'zgartirish") ?>: '+name);
		$("#editHadisForm [name='name']").val(name);
		$("#editHadisForm [name='phone']").val(phone);
		$("#editHadisForm [name='info']").val(info);
		$("#editHadisForm [name='id']").val(id);

		if(active == '1'){ $("#editHadisForm [name='active']").prop('checked', true); }else{ $("#editHadisForm [name='active']").prop('checked', false); }
	});

	ajaxForm("#addHadisForm", 'actions/add.php', true);
	ajaxForm("#addRoviyForm", 'actions/add.php', true);
	ajaxForm("#addRivoyatchiForm", 'actions/add.php', true);
	ajaxForm("#editHadisForm", 'actions/edit.php', true);
	<?php endif; ?>

</script>

</body>
</html>