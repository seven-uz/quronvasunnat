<div id="kt_header_mobile" class="kt-header-mobile kt-header-mobile--fixed">
	<div class="kt-header-mobile__logo">
		<a href="<?php echo ADMIN_PAGE?>">
			<img alt="Logo" src="media/logos/sidebar.png" height="30">
		</a>
	</div>
	<div class="kt-header-mobile__toolbar">
		<button class="kt-header-mobile__toolbar-toggler kt-header-mobile__toolbar-toggler--left" id="kt_aside_mobile_toggler"><span></span></button>
		<?php if ($dsa) echo '<button class="kt-header-mobile__toolbar-toggler" id="kt_header_mobile_toggler"><span></span></button>'; ?>
		<button class="kt-header-mobile__toolbar-topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more"></i></button>
	</div>
</div>