<nav class="navbar navbar-expand-lg navbar-light bg-light w-100" id="nav">
	<a class="navbar-brand" href="/">
		<img src="assets/images/favicon/android-icon-192x192.png" alt="Logotip" height="40">
	</a>
	<div class="d-flex align-items-center order-lg-2">
		<div class="form-inline my-2 my-lg-0">
			<div class="search-box">
				<form action="search" class="inside d-flex justify-content-center align-items-center search-form" method="post" accept-charset="utf-8">
					<input class="form-control mr-sm-2" type="search" placeholder="<?php echo word('Qidirish') ?>" aria-label="Search">
					<div class="result"></div>
				</form>
			</div>
		</div>
		<!-- <i class="fas fa-times fz-20 ml-1 p-2 c-pointer search-close d-none"></i> -->
		<!-- <i class="fas fa-search fz-20 ml-1 p-2 c-pointer searchBtn"></i> -->
		<i class="fas fa-cog fz-20 ml-1 p-2 c-pointer" data-toggle="modal" data-target="#modal-settings"></i>
		<!-- <i class="fas fa-user fz-20 ml-1 mr-lg-0 mr-2 p-2 c-pointer" id="userBtn" data-toggle="modal" data-target="#auth"></i> -->
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
	</div>
	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<?php echo word('Islom') ?>
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<a class="dropdown-item" href="iymon">
						<?php echo word('Iymon'); ?></a>
					<a class="dropdown-item" href="namoz">
						<?php echo word('Namoz'); ?></a>
					<a class="dropdown-item" href="roza">
						<?php echo word('Roza'); ?></a>
					<a class="dropdown-item" href="zakot">
						<?php echo word('Zakot'); ?></a>
					<a class="dropdown-item" href="haj">
						<?php echo word('Haj'); ?></a>
				</div>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<?php echo word('Quron') ?>
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<a class="dropdown-item text-primary" href="quron">
						<?php echo word('Barcha suralar') ?></a>
					<a class="dropdown-item" href="quron?sura=18#gosidebar18">
						<?php echo word('Kahf surasi') ?></a>
					<a class="dropdown-item" href="quron?sura=36#gosidebar36">
						<?php echo word('Yasin surasi') ?></a>
					<a class="dropdown-item" href="quron?sura=55#gosidebar55">
						<?php echo word('Rohman (Ar-Rohman) surasi') ?></a>
					<a class="dropdown-item" href="quron?sura=67#gosidebar67">
						<?php echo word('Mulk (Taborak) surasi') ?></a>
					<a class="dropdown-item" href="quron?sura=78#gosidebar78">
						<?php echo word('Naba (Amma) surasi') ?></a>
				</div>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="#">
					<?php echo word('Sunnat') ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="hadis">
					<?php echo word('Hadis') ?></a>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<?php echo word('Duolar') ?>
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<a class="dropdown-item text-primary" href="duo">
						<?php echo word('Barcha duolar') ?></a>
					<a class="dropdown-item" href="duo?oyatal-kursi">
						<?php echo word('Oyatal kursi') ?></a>
					<a class="dropdown-item" href="duo?40-robbana">
						<?php echo word('40 Robbana') ?></a>
				</div>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="asmaulhusna">
					<?php echo word('Asma ul-Husna') ?></a>
			</li>
		</ul>
	</div>
</nav>