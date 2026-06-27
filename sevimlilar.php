<?
require 'blocks/brain.php';
require 'functions.php';

$page = "sevimlilar";
$pageTitle = word('Sevimlilar') . ' | ';
$siteTitle = "Qur'on va Sunnat";
$headerImg = "koran.webp";
$headerColor = "linear-gradient(109.6deg, rgba(9,15,33,1) 16%, #233E67 91.1%);";

require 'blocks/head.php';
require 'blocks/header.php';
?>
<main id="main-content" tabindex="-1">
	<section class="section">
		<div class="container">
			<h4 class="h2 mb-4"><?php echo word('Sevimlilar') ?> <i class="fas fa-heart text-danger" aria-hidden="true"></i></h4>
			<p class="text-muted mb-4"><?php echo word('Saqlangan oyatlar, duolar, hadislar va sunnatlar') ?></p>
			<p id="sevimlilar-empty" class="text-muted py-5 text-center d-none">
				<i class="far fa-heart fa-3x mb-3 d-block" aria-hidden="true"></i>
				<?php echo word('Hozircha sevimlilar yo\'q.') ?><br>
				<?php echo word('Oyat, duo, hadis yoki sunnat sahifasida') ?>
				<i class="far fa-heart" aria-hidden="true"></i>
				<?php echo word('belgisini bosing — bu yerda paydo bo\'ladi.') ?>
			</p>
			<div class="row" id="sevimlilar-list"></div>
		</div>
	</section>
</main>
<?include 'blocks/footer.php'?>
<script>
(function () {
	var items = [];
	try { items = JSON.parse(localStorage.getItem('qvs_favourites')) || []; } catch (e) {}

	var listEl = document.getElementById('sevimlilar-list');
	var emptyEl = document.getElementById('sevimlilar-empty');

	if (!items.length) {
		emptyEl.classList.remove('d-none');
		return;
	}

	var typeLabels = {
		ayah:   '<?php echo word('Oyat') ?>',
		duo:    '<?php echo word('Duo') ?>',
		hadis:  '<?php echo word('Hadis') ?>',
		sunnat: '<?php echo word('Sunnat') ?>'
	};

	function buildLink(item) {
		if (item.type === 'ayah') {
			var parts = String(item.id).split('-');
			return parts.length === 2
				? 'quron?sura=' + encodeURIComponent(parts[0]) + '#' + encodeURIComponent(parts[1])
				: 'quron';
		}
		if (item.type === 'duo')    return 'duo?id='    + encodeURIComponent(item.id);
		if (item.type === 'hadis')  return 'hadis?id='  + encodeURIComponent(item.id);
		if (item.type === 'sunnat') return 'sunnat?id=' + encodeURIComponent(item.id);
		return '#';
	}

	function esc(s) {
		return String(s)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;');
	}

	items.forEach(function (item) {
		var label = typeLabels[item.type] || item.type;
		var link = buildLink(item);
		var col = document.createElement('div');
		col.className = 'col-lg-4 mb-3 d-flex align-items-stretch';
		col.id = 'fav-card-' + item.type + '-' + item.id;
		col.innerHTML = '<div class="card w-100">'
			+ '<div class="card-header d-flex justify-content-between align-items-center">'
			+ '<span class="badge badge-success">' + esc(label) + '</span>'
			+ '<button class="btn btn-sm btn-link text-danger p-0 fav-remove-btn"'
			+ ' data-type="' + esc(item.type) + '"'
			+ ' data-id="' + esc(item.id) + '"'
			+ ' aria-label="<?php echo word('Sevimlilardan olib tashlash') ?>">'
			+ '<i class="fas fa-heart" aria-hidden="true"></i>'
			+ '</button>'
			+ '</div>'
			+ '<div class="card-body d-flex flex-column">'
			+ '<h5 class="card-title">' + esc(item.title) + '</h5>'
			+ (item.text ? '<p class="card-text text-muted">' + esc(item.text) + '</p>' : '')
			+ '<a href="/' + link + '" class="btn btn-primary btn-sm mt-auto align-self-start">'
			+ '<?php echo word('Ko\'rish') ?>'
			+ '</a>'
			+ '</div>'
			+ '</div>';
		listEl.appendChild(col);
	});

	// O'chirish tugmasi
	listEl.addEventListener('click', function (e) {
		var btn = e.target.closest('.fav-remove-btn');
		if (!btn) return;
		var type = btn.dataset.type;
		var id = btn.dataset.id;

		// localStorage dan o'chirish
		var saved = [];
		try { saved = JSON.parse(localStorage.getItem('qvs_favourites')) || []; } catch (ex) {}
		saved = saved.filter(function (i) {
			return !(i.type === type && String(i.id) === String(id));
		});
		try { localStorage.setItem('qvs_favourites', JSON.stringify(saved)); } catch (ex) {}

		// DOM dan o'chirish
		var card = document.getElementById('fav-card-' + type + '-' + id);
		if (card) card.remove();

		// Bo'sh holat
		if (!listEl.querySelector('.col-lg-4')) {
			emptyEl.classList.remove('d-none');
		}
	});
})();
</script>
</body>

</html>
