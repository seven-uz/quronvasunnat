<?php

session_start();

include 'unique/config.php';
include 'core/connection.php';
include 'core/functions.php';
include 'core/scripts_for_all_page.php';

$page_title = lot_kir("Ruhsat etilmagan sahifa");

include 'inc/head.php';
include 'inc/begin_body.php';
?>
<style>
	.error-header {
		text-align: center;
		color: #616464;
		font-size: 20px;
		font-weight: 600;
	}

	.error-main {
		text-align: center;
		color: #DB8C15;
	}

	.error-main span {
		display: inline-block;
		font-size: 200px;
		font-weight: 900;
	}
	@media (max-width: 576px) {
		.error-main span {
			display: inline-block;
			font-size: 100px;
			font-weight: 900;
		}
	}

	.error-description {
		text-align: center;
		color: #616464;
		font-size: 20px;
		font-weight: 500;
	}
</style>
<div class="app-content flex-column-fluid">
	<div class="app-container container-fluid">
		<div class="card card-flush">
			<div class="card-body">
				<?php
					echo '<div class="error-header">' . lot_kir("Xatolik: 403. Taʼqiqlangan sahifa") . '</div>';
					echo '<div class="error-main"><span>4</span><span>Ø</span><span>3</span></div>';
					echo '<div class="error-description">' . lot_kir("Ushbu sahifa uchun sizda foydalanish huquqi mavjud emas") . '</div>';
				?>
			</div>
		</div>
	</div>
</div>
<?php include 'inc/end_body.php'; ?>

<?php include 'inc/javascript.php'; ?>

</body>
</html>