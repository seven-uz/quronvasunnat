<?php

$page = 'auth';

session_start();

if(isset($_SESSION['id'])) {header("Location: /"); exit;}

include 'core/index.php';

$page_title = lot_kir("Boshqaruv paneliga kirish");

include 'inc/head.php';

?>
<body id="kt_body" class="app-blank bgi-size-cover bgi-attachment-fixed bgi-position-center bgi-no-repeat">
	<div class="d-flex flex-root" id="kt_app_root">
		<style>body { background-image: url('assets/media/auth/bg4.jpg'); } [data-bs-theme="dark"] body { background-image: url('assets/media/auth/bg4-dark.jpg'); }</style>
		<div class="d-flex justify-content-center flex-column-fluid flex-lg-row">
			<div class="d-flex justify-content-center justify-content-lg-end p-12 p-lg-20">
				<div class="bg-body d-flex flex-column align-items-stretch flex-center rounded-4 w-md-600px p-20">
					<div class="d-flex align-items-center justify-content-between mb-15">
						<h1 class="text-dark fw-bolder mb-3"><?php echo lot_kir("Saytga kirish") ?></h1>
						<div class="d-flex flex-stack">
							<button class="btn btn-flex btn-link btn-color-gray-700 btn-active-color-primary rotate fs-base" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
								<img data-kt-element="current-lang-flag" class="w-20px h-20px rounded me-3" src="assets/media/flags/uzbekistan.svg" />
								<span data-kt-element="current-lang-name" class="me-1"><?php echo ($_COOKIE['lang'] === 'uzk') ? 'Кирил' : 'Lotin'; ?></span>
								<i class="ki-duotone ki-down fs-5 text-muted rotate-180 m-0"></i>
							</button>
							<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-4 fs-7" data-kt-menu="true" id="kt_auth_lang_menu">
								<div class="menu-item px-3">
									<a href="<?php if($_COOKIE['lang'] === 'uzk') echo '#'; else echo '?lang=uzk'; ?>" class="menu-link d-flex px-5<?php if($_COOKIE['lang'] === 'uzk') echo ' active'; ?>">
										<span class="symbol symbol-20px me-4">
											<img data-kt-element="lang-flag" class="rounded-1" src="assets/media/flags/uzbekistan.svg" />
										</span>
										<span data-kt-element="lang-name">Kiril</span>
									</a>
								</div>
								<div class="menu-item px-3">
									<a href="<?php if($_COOKIE['lang'] === 'uzl') echo '#'; else echo '?lang=uzl'; ?>" class="menu-link d-flex px-5<?php if($_COOKIE['lang'] === 'uzl') echo ' active'; ?>">
										<span class="symbol symbol-20px me-4">
											<img data-kt-element="lang-flag" class="rounded-1" src="assets/media/flags/uzbekistan.svg" />
										</span>
										<span data-kt-element="lang-name">Lotin</span>
									</a>
								</div>
							</div>
						</div>
					</div>
					<form class="form w-100" id="loginForm">
						<div id="output"></div>
						<div class="form-floating fv-row mb-8">
							<input type="text" placeholder="<?php echo lot_kir("Email, Login yoki Telefon");?>" name="email" autocomplete="off" <?php if(isset($_COOKIE['email'])) echo ' value="'.$_COOKIE['email'].'"'; ?> class="form-control form-control-solid" required />
							<label><?php echo lot_kir("Email, Login yoki Telefon") ?></label>
						</div>
						<div class="fv-row mb-8">
							<div class="input-group mb-5 showHidePass cursor-pointer">
								<div class="form-floating">
									<input type="password" placeholder="<?php echo lot_kir("Parol");?>" name="password" autocomplete="off" <?php if(isset($_COOKIE['password'])) echo ' value="'.$_COOKIE['password'].'"'; ?> class="form-control form-control-solid password_input" required />
									<label><?php echo lot_kir("Parol") ?></label>
								</div>
								<span class="input-group-text border-0">
									<i class="ki-duotone ki-eye-slash fs-4" data-function="show_hide" data-content=".password_input">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
										<span class="path4"></span>
									</i>
									<i class="ki-duotone ki-eye fs-4 d-none" data-function="show_hide" data-content=".password_input">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
										<span class="path4"></span>
									</i>
								</span>
							</div>
						</div>
						<div class="d-grid mb-10 d-flex align-items-center">
							<div class="col-9">
								<div class="form-check form-check-custom form-check-solid">
									<input class="form-check-input" type="checkbox" name="save" <?php if(isset($_COOKIE['email'])) echo ' checked'; ?> id="flexCheckDefault"/>
									<label class="form-check-label" for="flexCheckDefault">
										<?php echo lot_kir("Parolni eslab qolish") ?>
									</label>
								</div>
							</div>
							<div class="col-3 text-end">
								<button type="submit" class="btn btn-primary w-100">
									<span class="indicator-label"><?php echo lot_kir("Kirish") ?></span>
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<?php include 'inc/javascript.php'; ?>

	<script>
		var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }
		$("#loginForm").on('submit', (function(e) {
			e.preventDefault();
			$.ajax({
				url: "actions/login.php",
				type: "POST",
				data: new FormData(this),
				contentType: false,
				cache: false,
				processData: false,
				success: function(data) {
					$("#output").html(data);
				}
			});
		}));

	$('[data-function="show_hide"]').on("click", function(){
		let password_input = $(this).data('content');
		$('[data-function="show_hide"]').toggleClass('d-none');
		if($(password_input).attr('type') == 'password'){
			$(password_input).attr('type', 'text');
		}else if($(password_input).attr('type') == 'text'){
			$(password_input).attr('type', 'password');
		}
	});
	</script>
</body>
</html>