<body id="kt_app_body"
	data-kt-app-layout="dark-sidebar"
	data-kt-app-header-fixed="true"
	data-kt-app-header-mobile="true"
	data-kt-app-sidebar-enabled="true"
	data-kt-app-sidebar-fixed="true"
	data-kt-app-sidebar-hoverable="true"
	data-kt-app-sidebar-push-header="true"
	data-kt-app-sidebar-push-toolbar="true"
	data-kt-app-sidebar-push-footer="true"
	data-kt-app-toolbar-enabled="<?php echo $showContent['toolbar']; ?>"
	class="app-default"
>
	<script>
	var defaultThemeMode = "light";
	var themeMode;
	if (document.documentElement) {
		if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
			themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
		} else {
			if (localStorage.getItem("data-bs-theme") !== null) {
				themeMode = localStorage.getItem("data-bs-theme");
			} else {
				themeMode = defaultThemeMode;
			}
		}
		if (themeMode === "system") {
			themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
		}
		document.documentElement.setAttribute("data-bs-theme", themeMode);
	}
	</script>
	<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
		<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
			<?php include 'inc/header.php'; ?>
			<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
				<style>
					.image-input-placeholder {
						background-image: url('/assets/media/blank-image.svg');
					}
					[data-bs-theme="dark"] .image-input-placeholder {
						background-image: url('/assets/media/blank-image-dark.svg');
					}
				</style>
				<?php include 'inc/sidebar.php'; ?>
				<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
					<div class="d-flex flex-column flex-column-fluid">
						<?php if($showContent['toolbar'] === true) include 'inc/noneed/toolbar.php'; ?>