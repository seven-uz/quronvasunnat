# Loyiha tahlili va audit

Sana: 2026-06-04. "Qur'on va Sunnat" (legacy PHP + mysqli) saytining to'liq tahlili.
Quyida aniqlangan kamchiliklar, ularning xavf darajasi va holati (TUZATILDI / QOLDI) keltirilgan.

## Umumiy xulosa

Sayt ishlaydigan, lekin tipik legacy PHP kamchiliklari bor edi: SQL injection,
ochiq qolgan production paroli, har sahifada ishlaydigan buzg'unchi `UPDATE`,
o'lik shablon kodi va PHP 8.1 da eskirgan funksiyalar. Eng kritiklari shu branchda
tuzatildi; qolganlari pastda ro'yxatlangan.

---

## TUZATILGAN kamchiliklar

### 1. [KRITIK] Production DB paroli ochiq matnda — `blocks/db.php`
Izohga olingan ulanish qatorida haqiqiy login/parol bor edi
(`mysqli_connect("localhost","sevenuz","O1Mdsa9p&...","quronvasunnat")`).
**Tuzatish:** kredensiallar `blocks/db.local.php` (gitignore) ga ko'chirildi; `db.php`
mavjud bo'lsa uni `require` qiladi. `utf8mb4` charset qo'shildi.
> ⚠️ Bu parol git tarixida qoldi — **production'da darhol almashtiring**.

### 2. [KRITIK] Har sahifa ochilganda ma'lumotni buzuvchi `UPDATE` — `duo.php`
`$update = mysqli_query($db, update('duolar','id=3','id=14'));` har safar duolar
ro'yxati renderlanganda `UPDATE duolar SET id=3 WHERE id=14` ni bajarardi.
**Tuzatish:** olib tashlandi.

### 3. [KRITIK] SQL injection — bir nechta sahifa
- `duo.php`: `WHERE id = '{$_GET['id']}'` → `intval()`.
- `hadis.php`: `WHERE h.id = $_GET[id]` (tirnoqsiz!) → `intval()`.
- `quron.php`: `?sura` va `?kitob` to'g'ridan-to'g'ri `WHERE` ga qo'shilardi → `intval()`.
- `livesearch.php`: `term` SQL'ga qo'shilib, keyin parametrsiz `mysqli_prepare`
  qilinardi (foydasiz). → haqiqiy parametrlangan so'rov (`?` + `bind_param`).

### 4. [YUQORI] Reflected XSS + regex injection — `livesearch.php`
Qidiruv so'zi (`term`) eskeyplanmasdan sahifaga va `preg_replace` naqshiga qo'yilardi.
**Tuzatish:** chiqarishda `htmlspecialchars(..., ENT_QUOTES)`, naqshda `preg_quote()`.
`mysqli_error()` mijozga chiqishi ham olib tashlandi.

### 5. [YUQORI] PHP 8.1 da 500 xatosi xavfi — `quron.php`
`$_GET['sura'] - 1` kabi arifmetikada raqamsiz kiritma PHP 8 da `TypeError` beradi.
**Tuzatish:** `intval()` qilingan `$getsura`/`$kitob` ishlatildi.

### 6. [O'RTA] Eskirgan `FILTER_SANITIZE_STRING` — `actions/contactform.php`
PHP 8.1 da eskirgan (deprecated). **Tuzatish:** `trim(strip_tags(...))` ga almashtirildi.

### 7. [O'RTA] ~400 qator o'lik e-commerce shabloni — `duo.php`
"Top Sale", "$433.21", "In the Cart", Lorem Ipsum — `if($show)` ichida edi, `$show`
hech qachon aniqlanmagan (har doim o'lik). **Tuzatish:** butun blok olib tashlandi.

### 8. [PAST] Mayda buglar — `functions.php`
- `phone_number12/9/9w`: `preg_replace("[^0-9]", ...)` — delimitersiz, noto'g'ri regex →
  `"/[^0-9]/"`.
- `getArabicDate`: `curl_close()` `return` dan keyin (hech ishlamasdi) → tartibga keltirildi.
- `word()`: `$_COOKIE['lang']` `isset` tekshiruvsiz → null-xavfsiz `?? ''`.

### 9. [O'RTA] Lokalda chiqayotgan PHP ogohlantirishlari (hosting'da yo'q)
Lokal OSPanel'da `display_errors = On`, hosting'da `Off` — shuning uchun eski koddagi
"Undefined variable / array key" ogohlantirishlari faqat lokalda ko'rinardi.
**Tuzatishlar:**
- `blocks/brain.php`: `error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING & ~E_NOTICE)`
  + `log_errors` — eski shovqin yashirildi, fatal xatolar baribir ko'rinadi va loglanadi.
- `blocks/header.php`: `$arabic_date` izohga olingan (5–11-qatorlar) → barcha kirishlar
  `?? ''` bilan himoyalandi; `$words['bugun']` → `?? ''`.
- `index.php`: `$a`/`$randomAuH` siklidan oldin initsializatsiya qilindi;
  `$getSunnat->fetch_row()` ikki marta chaqirilardi (ikkinchisi `null`) → bir marta
  `$sunnatCount` ga olindi.
- `functions.php`: `modalScs/modalErr` da aniqlanmagan `$words['success']`/`$words['error']`
  → `word('Muvaffaqiyatli')`/`word('Xatolik')`; `phone_number12` da 105-qator `$sArea`
  o'rniga `$sArea2` ga yozadi (bug tuzatildi).
- `blocks/top.php`, `quron.php`: aniqlanmagan `$words[...]` → `?? ''`.

---

## 2026-06-05: PHP 5.6 → 8.3 ko'tarish + admin panel xavfsizligi

Hosting PHP 5.6 da edi; maqsad — 8.3. Quyidagilar shu kunda bajarildi. Barcha o'zgargan
fayllar lokal PHP 8.3 bilan lint qilindi: butun loyiha (85 fayl, `assets/` uchinchi
tomon bundle'laridan tashqari) — 0 sintaksis xatosi, 0 deprecation.

### Frontend
- `blocks/footer.php`: `globalColor` cookie'si `value="..."` ichiga eskeyplanmasdan
  chiqarilardi (cookie XSS) → `htmlspecialchars(..., ENT_QUOTES)`.
- `blocks/header2.php`: aladhan API javobi kelmasa `$data2['data']['hijri'][...]`
  "undefined key" berardi → barcha kalitlar `?? ''`. 41-qatorda bug: `$hijriDayName`
  `['hijri']['year']` ni olardi → `['hijri']['weekday']['en']` ga tuzatildi.
- `actions/contactform.php`: `$mt1`/`$mt2` POST'da bo'lmasa "undefined variable" → `?? 0`.

### Admin — PHP 8.3 moslik
- `adm/core/config.php`, `adm/core/functions.php`: `end(explode(...))` — vaqtinchalik
  natijani referens orqali uzatish 7.x+ da xato berardi → o'zgaruvchiga olib uzatildi.
- `adm/core/functions.php`: `preg_replace("[^0-9]", ...)` (delimitersiz, 3 joyda) →
  `"/[^0-9]/"`. Bu telefon formatlashni buzardi (8.x da `null` qaytarardi).
- `adm/core/functions.php`: `arrayColumn`, `FormBuilder`, `actionsFunction` da
  "ixtiyoriy parametr majburiydan oldin" deprecation → default'lar olib tashlandi
  (xulq o'zgarmadi — ular allaqachon amalda majburiy edi).

### Admin — xavfsizlik
- `adm/actions/login.php`: `$ip` (`HTTP_CLIENT_IP`/`X-Forwarded-For` — soxtalashtirsa
  bo'ladigan header) va `$device` (`User-Agent`) to'g'ridan-to'g'ri SQL'ga kirardi
  (SQL injection) → `mysqli_real_escape_string`; `$myrow['id']` → `intval`.
- `adm/actions/delete.php`: `$_POST['column']` (backtick ichidagi ustun nomi),
  `$_POST['time']`, `$_POST['second_table']` xom ishlatilardi → ustun nomi
  `[a-zA-Z0-9_]` allow-list, `time` eskeyp, `second_table` boshqa jadvallar kabi
  tozalandi; sessiya `id` `intval`; `mysqli_error()` mijozga chiqishi olib tashlandi
  (log'ga yoziladi, umumiy xabar ko'rsatiladi).
- `adm/core/brain.php`: `last_enter` UPDATE'da sessiya `id` `intval`; muhitga qarab
  xatolar — `DEVELOPING_MODE` (config.php) yoqilgan bo'lsa ekranda, aks holda yashirin + log.

### Admin — DB kredensiallari tashqarilandi
- `adm/unique/config.php`: `DB_*` endi avval `config.local.php` (gitignore) dan o'qiladi,
  topilmasa lokal default'lar ishlatiladi. Namuna: `adm/unique/config.local.example.php`.

### 5.6 → 8.3 skaner xulosasi
Olib tashlangan funksiyalar (`each`, `create_function`, `mysql_*`, `ereg*`, `split`,
`money_format`), eskirgan sintaksis (`$var{0}`, `${var}` interpolatsiyasi) yoki boshqa
fatal bloker **topilmadi**. "Tag-tugi bilan qayta yozish" shart emas — mavjud kod 8.3 da
ishlaydi; yuqoridagi nuqtali tuzatishlar yetarli.

---

## 2026-06-05: Begona ERP o'chirildi + yangi admin + frontend redizayn

Foydalanuvchi qarori: begona loyihaga tegishli hamma narsani o'chirib, saytni to'liq
ishchi holatga keltirish; ochiq (public) tomonni iliq-minimal, zamonaviy dizaynga o'tkazish.

### Begona ERP admin butunlay o'chirildi
Eski `adm/` (Metronic ERP, ~1280 fayl: products/orders/clients/attendance/...) `git rm`
bilan to'liq olib tashlandi. Quyidagi A va B kamchiliklari shu bilan yopildi.

### Yangi, kichik va xavfsiz Qur'on admini yozildi
Yangi `adm/` schema-agnostik kontent boshqaruvi (CRUD):
- `adm/config.php`: `ADMIN_USER`/`ADMIN_PASS_HASH` (maxfiylar `config.local.php` — gitignore;
  namuna `config.local.example.php`) va `$ADMIN_TABLES` **oq ro'yxati** (duolar, hadislar,
  sunnatlar, asmaulhusna, tags). SQL'ga faqat shu kalitlar tushadi.
- `adm/inc/bootstrap.php`: sessiya, muhitga qarab xatolar, DB (`blocks/db.php` qayta
  ishlatiladi), yordamchilar: `e()` (htmlspecialchars), CSRF token/maydon/tekshiruv,
  `require_login()`, `table_config()` (allow-list), `table_columns()` (`SHOW COLUMNS`).
- `login.php`: `password_verify` + `hash_equals`, muvaffaqiyatda `session_regenerate_id`,
  umumiy xato, brute-force'ga `usleep`. `logout.php`: sessiyani to'liq tozalash.
- `index.php`: bo'limlar va yozuvlar soni. `list.php`: tayyorlangan `LIKE` qidiruv +
  sahifalash (`LIMIT/OFFSET` bind). `edit.php`: `SHOW COLUMNS` asosida forma; INSERT/UPDATE
  to'liq `mysqli_prepare`+`bind_param` bilan; bo'sh+nullable ustun → `NULL`. `delete.php`:
  faqat POST + CSRF tasdiq sahifasi, `WHERE id=?`.
- `adm/inc/.htaccess`: ichki fayllarga to'g'ridan-to'g'ri kirish taqiqlandi. Barcha
  chiqish `e()` bilan eskeyplanadi; `mysqli_error()` mijozga chiqmaydi (log'ga yoziladi).
> MySQL bu muhitda yo'q — admin CRUD brauzerda **tekshirilmadi**. Birinchi kirishdan keyin
> `config.local.php` yaratib, default "admin/admin" parolini ALBATTA o'zgartiring.

### Frontend redizayn (iliq minimal: oq + yashil)
- `assets/css/style.css`: 616 ta arab `@font-face` (Qur'on o'quvchisi uchun, 1–3110-qator)
  **saqlandi**; ustiga yangi dizayn-tizim qo'shildi (`:root` o'zgaruvchilari, yangi
  tugma/navbar/karta/jadval/forma/footer uslublari). Mavjud class/ID'lar (JS uchun) saqlandi.
- `blocks/head.php`: Inter+Lora (Google Fonts), `style.css?v=2.0.0`, `theme-color`.
- `blocks/empty-state.php` (yangi): hali tayyor bo'lmagan bo'limlar uchun ko'rkam holat.
- `namoz/roza/zakot/haj/kalendar/prayertimes.php`: standart zanjirга keltirildi va
  `empty-state` ishlatildi (`kalendar`/`prayertimes` ilgari buzuq edi: `brain.php` yo'q,
  jonli curl, debug chiqishi, Lorem ipsum).

---

## QOLGAN kamchiliklar (kelgusi ish)

> A va B (begona ERP admin paneli va uning `strrev(md5())` parol xeshlashi) 2026-06-05 da
> ERP butunlay o'chirilib, o'rniga bcrypt'li yangi admin yozilgani bilan **hal qilindi**.

### C. [O'RTA] Qisqa PHP teglariga bog'liqlik (`<?`)
`short_open_tag = Off` bo'lgan serverda kod xom PHP sifatida bosilib chiqadi (manba
kodi oshkor bo'lishi mumkin). **Tavsiya:** asta-sekin `<?php` / `<?= ?>` ga o'tish yoki
server sozlamasini hujjatlashtirish (README da bor).

### D. [PAST] Takrorlangan kod (refactor)
- `functions.php`: `textType`, `textType2`, `d()`, `dt()`, `dwt2()` deyarli bir xil
  (kod takrori).
**Tavsiya:** birlashtirish/tozalash (xavfsiz refactor).
*(`phone_number12` `$sArea2` va `modalScs/modalErr` `$words` buglari 9-bo'limda tuzatildi.)*

### E. [PAST] `index.php` audio fayl nomi mantig'i
26–42-qatorlardagi `$audioFNo` hisobi (`'00'.$no - 1`) shubhali (string+arifmetika).
Audio havolasini tekshirish kerak.

### F. [PAST] DB sxemasi repozitoriyda yo'q
`.sql` dump yo'q — yangi muhitni o'rnatish qiyin. `db/schema.sql` qo'shish tavsiya etiladi.

---

## Tekshirish
O'zgartirilgan barcha fayllar `php -d short_open_tag=On -l` bilan lint qilindi — sintaksis
xatosi yo'q. UI brauzerda tekshirilmadi (bu muhitda MySQL/OSPanel yo'q).


---

## 2026-06-21: UI yaxshilash + qolgan kamchiliklarni yopish

Maqsad: butun loyihani ko'rib chiqib, qolgan kamchiliklarni tuzatish va UI'ni yaxshilash.
Barcha o'zgargan/yangi PHP fayllar `php-parser` (JS) bilan parse qilindi — **39/39 fayl, 0 xato**
(bu muhitda PHP runtime yo'q). Dizayn brauzerda computed-style orqali tekshirildi.

### UI / UX
- **Tungi rejim (dark mode) endi to'liq ishlaydi.** Ilgari toggle JS'da `#darkMode`, HTML'da
  `#customSwitch1` edi (umuman bog'lanmagan) va to'q tema CSS yo'q edi. Tuzatish:
  - `blocks/footer.php`: checkbox id `darkMode`, cookie holatiga qarab `checked`.
  - `blocks/head.php`: `<body class="darkMode">` server tomonda cookie'dan; `theme-color` ham.
  - `blocks/brain.php`: `darkMode` cookie default.
  - `assets/css/style.css`: `body.darkMode` uchun dizayn-tizim o'zgaruvchilarini qayta bo'yash
    (fon, sirt, matn, chiziq, urg'u) + navbar/karta/forma/jadval moslamalari.
  - Tekshiruv: fon `#0F1512`, karta `#161C19`, tugma `#36B083`, matn ochiq — ✓.
- **Urg'u rangi (accent) sozlamasi ishga tushirildi.** `globalColor` cookie ilgari faqat
  input qiymatida aks etardi, hech narsani bo'yamasdi. Endi `functions.php` da `accent_css()`
  tanlangan rangdan soyalar hosil qiladi, `head.php` `:root` ni override qiladi. O'lik
  "Sayt 2-rangi" boshqaruvi olib tashlandi. Sozlama cookie'lariga `path=/;max-age` qo'shildi.
- **Mobil sura navigatsiyasi.** `quron.php` da sura ro'yxati mobil'da `d-none` + CSS
  `display:none` tufayli butunlay yashirin edi. Endi off-canvas drawer (tugma + backdrop +
  `main.js` handlerlari, Esc bilan yopiladi).
- **a11y:** "mazmunga o'tish" skip-link, `<main id="main-content">`, klaviatura fokus
  halqalari, ikonka-tugmalarga `aria-label` (sozlama, scroll), chop etish (print) uslublari.

### Yangi sahifalar (sinmagan havolalar)
- **`sunnat.php`** — nav "Sunnat" va bosh sahifadagi `sunnat?id=` / "Barchasini ko'rish"
  havolalari 404 berardi (sahifa yo'q edi). Endi ro'yxat + bitta sunnat ko'rinishi (hadis.php
  uslubida, `intval` bilan).
- **`search.php`** — qidiruv formasi va footer teglari `search` ga yo'naltirardi, lekin
  sahifa yo'q edi va formada `name` yo'q edi. Endi: nav forma GET + `name="q"`; `search.php`
  bir nechta jadval bo'yicha **prepared LIKE** qidiradi (suralar, duolar, hadislar, sunnatlar,
  asmaulhusna) va natijalarni guruhlab ko'rsatadi. "Sunnat" menyu havolasi ham tuzatildi.

### Kod kamchiliklari (ANALYSIS "QOLGAN" — yopildi)
- **E (audio nomi):** `index.php` da `> 10` shartli padding **10-raqamni tushirib qoldirardi**
  (ns/no = 10 da bo'sh qiymat). `str_pad(..., 3, '0')` ga almashtirildi; o'lik `$audioFNs`/
  `$audioFNo` hisoblari olib tashlandi. (`quron.php` da `> 9` ishlatilgan — u to'g'ri edi.)
- **D (takror kod):** `textType2` `textType` ga alias qilindi (bayt-bayt bir xil edi).
- **SQLi:** `asmaulhusna.php` `WHERE id='$thisval'` → `intval($thisval)`.
- **O'lik kod:** ishlatilmagan `SELECT * FROM duolar WHERE type=1` (6 sahifada) va
  `brain.php` dagi "Eskidan ketdi..." debug `echo` bloki olib tashlandi.
- **Sinmagan sahifa:** `iymon.php` ning birinchi kartasi (`reloadableContent`) bo'sh edi
  (JS handler yo'q) — tasodifiy oyat bilan to'ldirildi; duo kartasi yorlig'i tuzatildi.

### F (DB sxema) — yopildi
- `db/schema.sql` qo'shildi: barcha jadvallar (`suralar, suranames, duolar, hadislar,
  sunnatlar, asmaulhusna, tags, quron, roviylar, rivoyatchilar, kunyalar, qorilar`) uchun
  `CREATE TABLE` (koddan tiklangan struktura — ma'lumot dump'i emas).

### Tozalash
- 21 ta `desktop.ini` va 2 ta 1-baytli axlat fayl (`quronvasunnat`, `www`) git'dan
  olib tashlandi (`.gitignore` ularni allaqachon e'tiborsiz qoldiradi).

### Hamon ochiq (C)
- Qisqa PHP teglari (`<?`) — `short_open_tag=On` ga bog'liqlik saqlanib qoldi (hujjatlashtirilgan;
  ixtiyoriy migratsiya). `d()/dt()/dwt2()` sana funksiyalari hamon o'xshash (kelajak refactor).
