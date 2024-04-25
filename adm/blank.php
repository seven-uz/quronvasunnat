<?php

include 'core/index.php';

$page_title = lot_kir("Test");

include 'inc/head.php';

// $header['title'] = lot_kir("Harajatlar");
// $header['breadcrumb'] = [0 => ['val' => lot_kir("Asosiy sahifa"), 'link' => '/'], $header['title']];
// $header['add_btn'] = '<div class="app-navbar-item ms-1 ms-md-3"><a data-bs-toggle="modal" data-bs-target="#addInvestingModal" class="btn btn-sm btn-primary"><i class="fas fa-plus pe-0 d-sm-none d-inline-block"></i><span class="d-sm-inline-block d-none">'.lot_kir("Kiritish").'</span></a></div>';

include 'inc/begin_body.php';
?>

<div class="app-content flex-column-fluid">
	<div class="app-container container-fluid">
		<div class="card card-flush">
			<div class="card-header align-items-center py-5 gap-2 gap-md-5">
				<div class="card-title">
					Title
				</div>
				<div class="card-toolbar flex-row-fluid justify-content-end gap-5">
					<a href="/metronic8/demo1/../demo1/apps/ecommerce/catalog/add-product.html" class="btn btn-primary">
						Toolbar
					</a>
				</div>
			</div>
			<div class="card-body pt-0">
					Content
			</div>
		</div>
	</div>
</div>
<?php include 'inc/end_body.php'; ?>

<?php include 'inc/drawers.php'; ?>
<?php include 'inc/modals.php'; ?>
<?php include 'inc/javascript.php'; ?>

</body>
</html>