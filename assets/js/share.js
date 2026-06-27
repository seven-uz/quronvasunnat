'use strict';
// Ulashish (Web Share API) — .share-btn[data-title][data-text][data-url?]
// Mobil: native share dialog; desktop: clipboard nusxalash + snackbar.
(function () {
	function _abs(url) {
		if (!url) return window.location.href;
		if (/^https?:\/\//.test(url)) return url;
		return url.charAt(0) === '/' ? window.location.origin + url : window.location.href;
	}

	function _toast(msg) {
		var el = document.getElementById('qvsShareSnack');
		if (!el) {
			el = document.createElement('div');
			el.id = 'qvsShareSnack';
			el.setAttribute('role', 'status');
			el.setAttribute('aria-live', 'polite');
			el.style.cssText = 'position:fixed;bottom:80px;left:50%;transform:translateX(-50%);'
				+ 'background:var(--green,#36B083);color:#fff;padding:8px 20px;border-radius:6px;'
				+ 'z-index:9999;font-size:14px;pointer-events:none;opacity:0;transition:opacity .25s';
			document.body.appendChild(el);
		}
		el.textContent = msg;
		el.style.opacity = '1';
		clearTimeout(el._t);
		el._t = setTimeout(function () { el.style.opacity = '0'; }, 2200);
	}

	function _copy(text) {
		if (navigator.clipboard && navigator.clipboard.writeText) {
			navigator.clipboard.writeText(text).then(function () {
				_toast('Matn nusxalandi!');
			}).catch(function () { _legacy(text); });
		} else {
			_legacy(text);
		}
	}

	function _legacy(text) {
		var ta = document.createElement('textarea');
		ta.value = text;
		ta.style.cssText = 'position:fixed;opacity:0;top:0;left:0;width:1px;height:1px';
		document.body.appendChild(ta);
		ta.focus();
		ta.select();
		try { document.execCommand('copy'); _toast('Matn nusxalandi!'); } catch (e) {}
		document.body.removeChild(ta);
	}

	function share(title, text, url) {
		url = _abs(url || '');
		title = title || document.title;
		var body = text ? text + '\n' + url : url;
		if (navigator.share) {
			navigator.share({ title: title, text: body, url: url }).catch(function () {});
		} else {
			_copy(title + '\n' + body);
		}
	}

	document.addEventListener('click', function (e) {
		var btn = e.target.closest('.share-btn');
		if (!btn) return;
		e.preventDefault();
		share(
			btn.dataset.title || '',
			btn.dataset.text  || '',
			btn.dataset.url   || window.location.href
		);
	});

	window.QvsShare = { share: share };
})();
