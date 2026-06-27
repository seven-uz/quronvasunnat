'use strict';

// Qur'on va Sunnat — Service Worker (App Shell kesh strategiyasi)
// Versiyani oshirish: CACHE nomini o'zgartiring → yangi install/activate sikli.
var CACHE = 'qvs-v1';

// App shell: kafolatlangan statik fayllar (o'rnatishda keshlanadi)
var SHELL = [
	'/',
	'/assets/css/style.css?v=2.1.0',
	'/assets/js/main.js',
	'/assets/js/favourites.js',
	'/assets/images/favicon/android-icon-192x192.png',
	'/assets/images/logo-main.png'
];

// O'rnatish: shell fayllarini keshga yozish
self.addEventListener('install', function(e) {
	e.waitUntil(
		caches.open(CACHE).then(function(c) {
			return c.addAll(SHELL);
		}).then(function() {
			return self.skipWaiting();
		})
	);
});

// Faollashtirish: eski keshlarni tozalash
self.addEventListener('activate', function(e) {
	e.waitUntil(
		caches.keys().then(function(keys) {
			return Promise.all(
				keys
					.filter(function(k) { return k !== CACHE; })
					.map(function(k) { return caches.delete(k); })
			);
		}).then(function() {
			return self.clients.claim();
		})
	);
});

// So'rovlarni tutib olish (Fetch)
self.addEventListener('fetch', function(e) {
	// Faqat GET so'rovlari
	if (e.request.method !== 'GET') return;

	var url = e.request.url;

	// Faqat bir xil kelib chiqish (same-origin)
	if (!url.startsWith(self.location.origin)) return;

	// Admin panel va tashqi metrika keshlanmaydi
	if (url.indexOf('/adm/') !== -1) return;
	if (url.indexOf('mc.yandex.ru') !== -1) return;

	// Statik fayllar (CSS, JS, rasmlar, shriftlar, audio)
	var isAsset = /\.(css|js|png|jpg|jpeg|webp|gif|ico|svg|woff2?|ttf|mp3)(\?|$)/.test(url);

	if (isAsset) {
		// Cache-first: tezlik uchun
		e.respondWith(
			caches.match(e.request).then(function(hit) {
				if (hit) return hit;
				return fetch(e.request).then(function(res) {
					if (res && res.ok) {
						var clone = res.clone();
						caches.open(CACHE).then(function(c) { c.put(e.request, clone); });
					}
					return res;
				});
			})
		);
	} else {
		// Network-first: dinamik PHP sahifalari uchun; offline'da keshdan
		e.respondWith(
			fetch(e.request).then(function(res) {
				if (res && res.ok) {
					var clone = res.clone();
					caches.open(CACHE).then(function(c) { c.put(e.request, clone); });
				}
				return res;
			}).catch(function() {
				return caches.match(e.request);
			})
		);
	}
});
