'use strict';
// Sevimlilar (Bookmarks) — localStorage asosida, serverga so'rovsiz ishlaydi.
// Ishlash tartibi:
//   .fav-btn[data-type][data-id][data-title][data-text] elementlarni qo'ying —
//   ushbu skript ularni avtomatik kuzatib, ikonka va aria-pressed ni yangilaydi.
(function () {
	var KEY = 'qvs_favourites';

	// Barcha saqlanganlarni o'qish
	function getAll() {
		try { return JSON.parse(localStorage.getItem(KEY)) || []; } catch (e) { return []; }
	}

	// localStorage ga yozish
	function _save(items) {
		try { localStorage.setItem(KEY, JSON.stringify(items)); } catch (e) {}
	}

	// Mavjudligini tekshirish
	function has(type, id) {
		return getAll().some(function (i) {
			return i.type === type && String(i.id) === String(id);
		});
	}

	// Qo'shish (agar allaqachon yo'q bo'lsa)
	function add(item) {
		var items = getAll();
		if (has(item.type, item.id)) return false;
		items.push({
			type: item.type,
			id: String(item.id),
			title: item.title || '',
			text: item.text ? String(item.text).substring(0, 150) : ''
		});
		_save(items);
		return true;
	}

	// O'chirish
	function remove(type, id) {
		_save(getAll().filter(function (i) {
			return !(i.type === type && String(i.id) === String(id));
		}));
	}

	// Almashtirish (toggle)
	function toggle(type, id, title, text) {
		if (has(type, id)) { remove(type, id); return false; }
		add({ type: type, id: id, title: title, text: text });
		return true;
	}

	// Barcha .fav-btn tugmalarining ikonkasi va holatini yangilash
	function refreshButtons() {
		document.querySelectorAll('.fav-btn').forEach(function (btn) {
			var isFav = has(btn.dataset.type, btn.dataset.id);
			var icon = btn.querySelector('i');
			if (icon) {
				if (isFav) {
					icon.className = icon.className.replace('far fa-heart', 'fas fa-heart');
					if (icon.className.indexOf('fa-heart') === -1) icon.className = 'fas fa-heart';
				} else {
					icon.className = icon.className.replace('fas fa-heart', 'far fa-heart');
					if (icon.className.indexOf('fa-heart') === -1) icon.className = 'far fa-heart';
				}
			}
			btn.setAttribute('aria-pressed', isFav ? 'true' : 'false');
			var tip = isFav ? 'Sevimlilardan olib tashlash' : 'Sevimliga qo\'shish';
			btn.title = tip;
			btn.setAttribute('aria-label', tip);
		});
	}

	// Click — event delegation (barcha sahifalarda)
	document.addEventListener('click', function (e) {
		var btn = e.target.closest('.fav-btn');
		if (!btn) return;
		var type = btn.dataset.type;
		var id = btn.dataset.id;
		if (!type || !id) return;
		var title = btn.dataset.title || '';
		var text = btn.dataset.text || '';
		toggle(type, id, title, text);
		refreshButtons();
		// Kichik animatsiya
		btn.classList.add('fav-anim');
		setTimeout(function () { btn.classList.remove('fav-anim'); }, 400);
	});

	// DOM tayyor bo'lgach holat yangilash
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', refreshButtons);
	} else {
		refreshButtons();
	}

	// Global API (sevimlilar.php va boshqalar uchun)
	window.QvsFavourites = { getAll: getAll, has: has, add: add, remove: remove, toggle: toggle };
})();
