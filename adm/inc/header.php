<?php

// $result_h = mysqli_query($db, "SELECT * FROM ". PREFIX."users WHERE id='" . $_SESSION['id'] . "'");
// $row_h = mysqli_fetch_assoc($result_h);

?>
<div id="kt_app_header" class="app-header">
	<div class="app-container container-fluid d-flex align-items-stretch justify-content-between"
		id="kt_app_header_container">
		<div class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" title="Show sidebar menu">
			<div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
				<i class="ki-duotone ki-abstract-14 fs-2 fs-md-1">
					<span class="path1"></span>
					<span class="path2"></span>
				</i>
			</div>
		</div>
		<div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
			<a href="<?php echo ADMIN_PAGE?>" class="d-lg-none">
				<span class="d-sm-inline-block d-none">Textile</span>
				<!-- <img alt="Logo" src="assets/media/logos/default-small.svg" class="h-30px" /> -->
			</a>
		</div>
		<div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">

			<?php if($showContent['header']['menu'] === true) include 'noneed/header_menu.php'; ?>

			<div data-kt-swapper="true" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}"
				data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_header_wrapper'}"
				class="page-title d-flex flex-column justify-content-center flex-wrap me-3 mb-5 mb-lg-0">
				<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0"><?php echo $header['title']; ?></h1>
				<?php if(!empty($header['breadcrumb'])): ?>
				<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
					<?php
					foreach ($header['breadcrumb'] as $key => $val) {
						if(is_array($val) && array_key_exists('link', $val)){
							echo '<li class="breadcrumb-item text-muted">';
								echo ($val['link']) ? '<a href="'.$val['link'].'" class="text-primary">'.$val['val'].'</a>' : $val;
							echo '</li>';
						}else{
							echo '<li class="breadcrumb-item text-muted">'.$val.'</li>';
						}

						if(array_key_exists(($key+1), $header['breadcrumb'])) {
							echo '<li class="breadcrumb-item">
								<span class="bullet bg-gray-400 w-5px h-2px"></span>
							</li>';
						}
					}
					?>

				</ul>
				<?php endif; ?>
			</div>

			<div class="app-navbar flex-shrink-0">
				<?php
				echo $header['add_btn'];
				?>
				<div class="app-navbar-item ms-1 ms-md-3" id="kt_header_user_menu_toggle">
					<div class="cursor-pointer symbol symbol-30px symbol-md-40px"
						data-kt-menu-trigger="{default: 'click'}" data-kt-menu-attach="parent"
						data-kt-menu-placement="bottom-end">
						<div class="fw-bold d-flex align-items-center fs-5"><?php echo mb_strimwidth($_SESSION['fio'], 0, 12, '...') ?></div>
					</div>
					<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
						data-kt-menu="true">
						<div class="menu-item px-3">
							<div class="menu-content d-flex align-items-center px-3">
								<div class="d-flex flex-column">
									<div class="fw-bold d-flex align-items-center fs-5"><?php echo $_SESSION['fio'] ?></div>
									<?php if($_SESSION['email'] != '') echo '<a href="mailto:'.$_SESSION['email'].'" class="fw-semibold text-muted text-hover-primary fs-7">'.$_SESSION['email'].'</a>'; ?>
								</div>
							</div>
						</div>
						<div class="separator my-2"></div>
						<div class="menu-item px-5">
							<a href="users?id=<?php echo $_SESSION['id']?>" class="menu-link px-5"><?php echo lot_kir("Profil") ?></a>
						</div>
						<div class="separator my-2"></div>
						<div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
							<a href="#" class="menu-link px-5">
								<span class="menu-title position-relative"> <?php echo lot_kir("Mavzu") ?>
								<span class="ms-5 position-absolute translate-middle-y top-50 end-0">
										<i class="ki-duotone ki-night-day theme-light-show fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span></i>                        <i class="ki-duotone ki-moon theme-dark-show fs-2"><span class="path1"></span><span class="path2"></span></i>                    </span>
								</span>
							</a>
							<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
									<div class="menu-item px-3 my-0">
										<a href="#" class="menu-link px-3 py-2 active" data-kt-element="mode" data-kt-value="light">
											<span class="menu-icon" data-kt-element="icon">
												<i class="ki-duotone ki-night-day fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span></i>            </span>
											<span class="menu-title">
												<?php echo lot_kir("Yorug'") ?>
											</span>
										</a>
									</div>
									<div class="menu-item px-3 my-0">
										<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
											<span class="menu-icon" data-kt-element="icon">
												<i class="ki-duotone ki-moon fs-2"><span class="path1"></span><span class="path2"></span></i>
											</span>
											<span class="menu-title">
												<?php echo lot_kir("Qorong'u") ?>
											</span>
										</a>
									</div>
									<div class="menu-item px-3 my-0">
										<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
											<span class="menu-icon" data-kt-element="icon">
												<i class="ki-duotone ki-screen fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>            </span>
											<span class="menu-title">
												<?php echo lot_kir("Tizim") ?>
											</span>
										</a>
									</div>
							</div>
						</div>
						<div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
							<a href="#" class="menu-link px-5">
								<span class="menu-title position-relative">
										<?php echo lot_kir("Yozuv") ?>
										<span class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0">
											<?php if($_COOKIE['lang'] === 'uzl') echo lot_kir("Lotincha"); elseif($_COOKIE['lang'] === 'uzk') echo lot_kir("Kirilcha"); ?>
										</span>
								</span>
							</a>
							<div class="menu-sub menu-sub-dropdown w-175px py-4">
								<div class="menu-item px-3">
									<a href="?lang=uzk" class="menu-link px-3 py-2">
										<span class="menu-icon" data-kt-element="icon">
											<img class="rounded-1" src="assets/media/flags/uzbekistan.svg" alt="" />
										</span>
										<span class="menu-title">Кирилча</span>
									</a>
								</div>
								<div class="menu-item px-3">
									<a href="?lang=uzl" class="menu-link px-3 py-2">
										<span class="menu-icon" data-kt-element="icon">
											<img class="rounded-1" src="assets/media/flags/uzbekistan.svg" alt="" />
										</span>
										<span class="menu-title">Lotincha</span>
									</a>
								</div>
							</div>
						</div>
						<div class="separator my-2"></div>
						<div class="menu-item px-5">
							<a href="exit" class="menu-link px-5"><?php echo lot_kir("Chiqish") ?></a>
						</div>
					</div>
				</div>
				<?php if($showContent['header']['menu'] === true): ?>
				<div class="app-navbar-item d-lg-none ms-2 me-n2" title="Show header menu">
					<div class="btn btn-flex btn-icon btn-active-color-primary w-30px h-30px" id="kt_app_header_menu_toggle">
						<i class="ki-duotone ki-element-4 fs-1">
							<span class="path1"></span>
							<span class="path2"></span>
						</i>
					</div>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>