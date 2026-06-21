<main id="main-content" tabindex="-1">
	<section>
		<div class="container">
			<div class="empty-state">
				<i class="fas fa-mosque"></i>
				<h2><?= htmlspecialchars($pageTitle ?? '', ENT_QUOTES, 'UTF-8') ?></h2>
				<p><?php echo word('Bu bo‘lim hozircha tayyorlanmoqda. Tez orada ma’lumotlar bilan to‘ldiriladi, insha’Alloh.') ?></p>
				<a href="/" class="btn btn-primary"><?php echo word('Bosh sahifaga qaytish') ?></a>
			</div>
		</div>
	</section>
</main>
