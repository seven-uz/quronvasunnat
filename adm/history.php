<?php

include 'core/index.php';

$page_title = lot_kir("Foydalanish tarihi");

include 'inc/head.php';

$header['title'] = lot_kir("Amallar tarixi");
$header['breadcrumb'] = [0 => ['val' => lot_kir("Asosiy sahifa"), 'link' => '/'], $header['title']];

include 'inc/begin_body.php';
?>

<div class="app-content flex-column-fluid">
	<div class="app-container container-fluid">
		<?php
		// $show = mysqli_query($db, "SHOW TABLES");
		// $prefix = 'crm_teaching_gds782g_demo';
		// while($row = $show->fetch_assoc()){
		// 	if(str_replace(['cx6b79680', DB_NAME], '', $row['Tables_in_'.DB_NAME]) == '_ipqulflash') continue;
		// 	echo 'ALTER TABLE `'.$prefix.str_replace(['cx6b79680', DB_NAME], '', $row['Tables_in_'.DB_NAME]).'` ADD `created_user` INT NULL DEFAULT NULL AFTER `created_at`;<br>';
		// 	echo 'ALTER TABLE `'.$prefix.str_replace(['cx6b79680', DB_NAME], '', $row['Tables_in_'.DB_NAME]).'` ADD `updated_user` INT NULL DEFAULT NULL AFTER `updated_at`;<br>';
		// 	if(str_replace(['cx6b79680', DB_NAME], '', $row['Tables_in_'.DB_NAME]) == '_settings') continue;
		// 	echo 'ALTER TABLE `'.$prefix.str_replace(['cx6b79680', DB_NAME], '', $row['Tables_in_'.DB_NAME]).'` ADD `restored_user` INT NULL DEFAULT NULL AFTER `restored`;<br>';
		// }
		?>
		<div class="card card-flush">
			<div class="card-header align-items-center py-5 gap-2 gap-md-5">
				<div class="card-title">
					<?php echo lot_kir("Filtr") ?>
				</div>
			</div>
			<div class="card-body pt-0">
				<div class="row">
					<div class="col-4">
						<div class="form-group element">
							<div class="row">
								<label for="" class="col-form-label"><?php echo lot_kir("Element") ?></label>
								<div class="input ml-2"></div>
							</div>
						</div>
					</div>
					<div class="col-4">
						<div class="form-group user">
							<div class="row">
								<label for="" class="col-form-label"><?php echo lot_kir("Foydalanuvchi") ?></label>
								<div class="input ml-2"></div>
							</div>
						</div>
					</div>
					<div class="col-4">
						<div class="form-group action">
							<div class="row">
								<label for="" class="col-form-label"><?php echo lot_kir("Amal") ?></label>
								<div class="input ml-2"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card card-flush mt-10">
			<div class="card-body pt-0">
				<table class="table table-row-dashed main_table">
					<thead>
						<tr>
							<th><?php echo $words['time'] ?></th>
							<th><?php echo $words['user'] ?></th>
							<th><?php echo $words['item'] ?></th>
							<th><?php echo $words['action'] ?></th>
							<th><?php echo $words['value'] ?></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<?php include 'inc/end_body.php'; ?>

<?php include 'inc/javascript.php'; ?>

<script>
	let table = $(".main_table").DataTable({
		dom: "<'d-flex flex-md-row flex-column'<'d-flex col-md-6 col-12 pl-0'<'mr-2'l>f<'ml-2 userFilterSelect'>><'col-12 col-md-6 d-flex justify-content-md-end justify-content-start pl-md-2 pl-4 pr-0'p>>t",
		responsive: !0,
		lengthMenu: [10, 20, 30, 50, 100],
		pagingType: "simple_numbers",
		"aaSorting": [],
		language: {
			lengthMenu: "_MENU_",
			zeroRecords: "<?=$words['nothingnotfound']?>",
			info: "",
			infoEmpty: "",
			search: "",
			searchPlaceholder: "",
			infoFiltered: "",
			searchPlaceholder: wordsArr.search,
		},
		ajax: {
			url: 'ajax/history.php',
			type: 'POST',
		},
		columns: [
			{ data: 'time' },
			{ data: 'user' },
			{ data: 'item' },
			{ data: 'action' },
			{ data: 'value' },
		],
		initComplete: function() {
			this.api().columns().every(function() {
				var column = this;
				if(column[0] == 1){
					var el = 'user';
				}else if(column[0] == 2){
					var el = 'element';
				}else if(column[0] == 3){
					var el = 'action';
				}

				var select = $('<select class="form-control kt_selectpicker" data-size="6" title="'+wordsArr.choose+'" tabindex="-98"></select>')
					.appendTo($('.filtr .form-group.'+el+' .input').empty())
					.on('change', function() {
							var val = $.fn.dataTable.util.escapeRegex(
								$(this).val()
							);

							column
								.search(val ? '^' + val + '$' : '', true, false)
								.draw();
					});


					column.data().unique().sort().each(function(d, j) {
						select.append('<option value="' + d + '">' + d + '</option>')
					});
					$(".filtr select").selectpicker('refresh');
			});
		},
	});
</script>

</body>
</html>