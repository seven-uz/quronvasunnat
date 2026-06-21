# Loyiha xotirasi (Claude memory)

Bu fayl — loyiha bo'yicha **ko'chma xotira**. Maqsadi: boshqa kompyuterda yoki yangi
suhbatda ham Claude (yoki dasturchi) loyiha holatini, qarorlarni va keyingi qadamlarni
darhol tushunib, ishni davom ettira olsin. Repozitoriya bilan birga git'da yuradi.

> Bu yerda **HECH QACHON** maxfiy ma'lumot (parol, token, real kredensial) yozilmaydi.
> Ular faqat gitignore qilingan `blocks/db.local.php` va `adm/config.local.php` da turadi.

Yangilangan sana: 2026-06-21.

---

## Ish jarayoni qoidasi (MUHIM)

1. **Har mazmunli o'zgarishdan keyin commit qilinadi va `git push` qilinadi.** Loyiha
   doim git bilan sinxron yuradi (boshqa joyda davom ettirish uchun).
2. **Har yangilanishda shu `MEMORY.md` ham yangilanadi** — pastdagi "Holat" va
   "Keyingi qadamlar" bo'limlari dolzarb saqlanadi. So'ng commit + push.
3. Commit oldidan o'zgargan PHP `php -n -d short_open_tag=On -l <fayl>` bilan lint qilinadi.
4. Maxfiy qiymatlar hech qachon commit qilinmaydi (qarang: yuqoridagi ogohlantirish).

---

## Loyiha haqida (qisqa)

"Qur'on va Sunnat" — o'zbek tilidagi islomiy ma'lumot sayti. Protsedural PHP + `mysqli` +
MySQL; frontend Bootstrap 4 / jQuery (CDN), ustiga custom dizayn-tizim. To'liq
konvensiyalar va xavfsizlik qoidalari: [CLAUDE.md](CLAUDE.md). Audit tarixi:
[docs/ANALYSIS.md](docs/ANALYSIS.md).

- Foydalanuvchi: sayt egasi, o'zbekcha gaplashadi, puxta/batafsil ishni afzal ko'radi.
- Muhit: Windows + OSPanel. PHP 8.1 binari:
  `D:\Workspace\Developing\OSPanel\modules\php\PHP_8.1\php.exe`.
  Lint: `php -n -d short_open_tag=On -l <fayl>` (`-n` buzuq 8.3 php.ini ni chetlab o'tadi).
- **Bu muhitda MySQL/OSPanel ishlamaydi** — UI va DB so'rovlari brauzerda tekshirilmaydi.
  Kod ehtiyotkorlik bilan yoziladi, egasi haqiqiy serverda tekshiradi.
- Repo: `github.com/seven-uz/quronvasunnat`. Asosiy branch: `main`.

---

## Holat (nima qilingan)

- **PHP 5.6 → 8.3 moslik** + xavfsizlik qattiqlashtirildi (SQLi, XSS, ochiq parol,
  har sahifadagi buzg'unchi `UPDATE`, o'lik shablon kodi tuzatildi). Tafsilot: ANALYSIS.md.
- **DB kredensiallari tashqarilandi**: `blocks/db.php` avval default'larni, so'ng (bo'lsa)
  gitignore'dagi `blocks/db.local.php` ni yuklaydi. Namuna: `blocks/db.local.php.example`.
- **Begona ERP admin (`adm/`, ~1280 fayl) butunlay o'chirildi.**
- **Yangi, kichik va xavfsiz Qur'on admini yozildi** (`adm/`):
  - Login: `password_verify` + `hash_equals`, `session_regenerate_id`; barcha POST'da CSRF.
  - `$ADMIN_TABLES` **oq ro'yxati** (duolar, hadislar, sunnatlar, asmaulhusna, tags) —
    SQL'ga faqat shu kalitlar tushadi; formalar `SHOW COLUMNS` orqali quriladi.
  - CRUD to'liq `mysqli_prepare`+`bind_param`; chiqish `htmlspecialchars`; `mysqli_error()`
    mijozga chiqmaydi (log'ga yoziladi). `adm/inc/.htaccess` ichki fayllarni yopadi.
- **Frontend redizayn (iliq minimal: oq + yashil)**: `assets/css/style.css` da 616 ta arab
  `@font-face` (Qur'on o'quvchisi) saqlandi, ustiga yangi dizayn-tizim qo'shildi; Inter+Lora
  shriftlari; mavjud class/ID'lar (JS uchun) saqlandi. Buzuq `kalendar.php`/`prayertimes.php`
  qayta tiklandi; tayyor bo'lmagan bo'limlar uchun `blocks/empty-state.php`.

### 2026-06-21 yangilanishi (UI + kamchiliklar)
- **Ishlaydigan tungi rejim (dark mode)**: toggle id `customSwitch1`→`darkMode` (JS bilan
  moslandi), `body.darkMode` server tomonda cookie'dan qo'yiladi, `style.css` ga to'liq
  to'q dizayn-tizim qo'shildi (CSS o'zgaruvchilarni qayta bo'yash). Brauzerda computed-style
  bilan tekshirildi: fon `#0F1512`, karta `#161C19`, tugma `#36B083` — ishlaydi.
- **Urg'u rangi (accent) endi ishlaydi**: `functions.php` da `accent_css()` tanlangan rangdan
  soyalarni hosil qiladi, `head.php` `globalColor` cookie bo'lsa `:root` ni override qiladi.
  O'lik "Sayt 2-rangi" boshqaruvi olib tashlandi. Sozlama cookie'lariga `path=/;max-age` qo'shildi
  (endi saqlanadi).
- **Mobil sura navigatsiyasi**: `quron.php` da off-canvas drawer (toggle tugma + backdrop + JS),
  ilgari `listSurah` mobil'da butunlay yashirin edi.
- **Yangi sahifalar**: `sunnat.php` (nav va bosh sahifadagi `sunnat?id=` havolalari 404 edi) va
  `search.php` (qidiruv formasi/teglar nishoni — prepared LIKE bir nechta jadval bo'yicha).
  Nav qidiruv formasi GET + `name="q"` ga o'tkazildi; "Sunnat" menyu havolasi tuzatildi.
- **PHP kamchiliklar**: `index.php` `>10` padding bug'i (10-raqam tushib qolardi) `str_pad` ga;
  `asmaulhusna.php` `WHERE id='$thisval'`→`intval`; o'lik `WHERE type=1` so'rovlari (6 fayl) va
  brain.php dagi "Eskidan ketdi" debug echo olib tashlandi; `textType2`→`textType` alias.
- **a11y**: skip-link, `<main id="main-content">`, fokus halqalari, ikonka-tugmalarga
  `aria-label`, chop etish (print) uslublari.
- **DB sxema**: `db/schema.sql` qo'shildi (koddan tiklangan struktura).
- **Tozalash**: 21 ta `desktop.ini` va 2 ta 1-baytli axlat fayl git'dan olib tashlandi.
- **Tekshiruv**: barcha 39 PHP fayl `php-parser` (JS) bilan parse qilindi — 0 xato.
  (Bu muhitda PHP runtime yo'q; sintaksis shu yo'l bilan tekshirildi.)

### Admin'ga kirish
- Standart login/parol: **admin / admin** (faqat dastlabki sozlash uchun).
- Production'da ALBATTA o'zgartiring: `adm/config.local.example.php` ni `adm/config.local.php`
  deb nusxalang va yangi hash qo'ying:
  `php -r "echo password_hash('YANGI_PAROL', PASSWORD_DEFAULT);"`

---

## Keyingi qadamlar / ochiq ishlar

1. **[KRITIK] Tarqalgan production DB paroli git tarixida qoldi** — hostingda **darhol
   almashtiring**. (Qiymat bu yerda yozilmaydi; eski commit'larda bor.)
2. Admin CRUD, yangi dizayn, dark mode, qidiruv va `sunnat.php` **haqiqiy MySQL bilan
   brauzerda tekshirilmadi** (bu muhitda DB yo'q). Egasi serverda bir bor ko'zdan kechirsin.
3. **DB to'ldirish**: `db/schema.sql` faqat struktura. Oyatlar/duolar/hadislar kabi kontentni
   admin panel orqali yoki mavjud dump'dan yuklash kerak.
4. **[O'RTA] Qisqa PHP teglari (`<?`)** — hamon `short_open_tag=On` ga bog'liq (ANALYSIS "C").
   Server sozlamasi hujjatlashtirilgan; xohlasa asta `<?php`/`<?=` ga o'tkazish mumkin.
5. (Ixtiyoriy) `functions.php` dagi `d()/dt()/dwt2()` sana funksiyalari hamon o'xshash —
   kelajakda umumiy yordamchiga birlashtirish mumkin (`textType2` allaqachon alias qilindi).
