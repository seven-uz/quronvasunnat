# Loyiha xotirasi (Claude memory)

Bu fayl — loyiha bo'yicha **ko'chma xotira**. Maqsadi: boshqa kompyuterda yoki yangi
suhbatda ham Claude (yoki dasturchi) loyiha holatini, qarorlarni va keyingi qadamlarni
darhol tushunib, ishni davom ettira olsin. Repozitoriya bilan birga git'da yuradi.

> Bu yerda **HECH QACHON** maxfiy ma'lumot (parol, token, real kredensial) yozilmaydi.
> Ular faqat gitignore qilingan `blocks/db.local.php` va `adm/config.local.php` da turadi.

Yangilangan sana: 2026-06-05.

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

### Admin'ga kirish
- Standart login/parol: **admin / admin** (faqat dastlabki sozlash uchun).
- Production'da ALBATTA o'zgartiring: `adm/config.local.example.php` ni `adm/config.local.php`
  deb nusxalang va yangi hash qo'ying:
  `php -r "echo password_hash('YANGI_PAROL', PASSWORD_DEFAULT);"`

---

## Keyingi qadamlar / ochiq ishlar

1. **[KRITIK] Tarqalgan production DB paroli git tarixida qoldi** — hostingda **darhol
   almashtiring**. (Qiymat bu yerda yozilmaydi; eski commit'larda bor.)
2. Admin CRUD va yangi dizayn **brauzerda tekshirilmadi** (bu muhitda MySQL yo'q) — egasi
   haqiqiy serverda bir ko'zdan kechirsin.
3. Kichik ochiq kamchiliklar (ANALYSIS.md "QOLGAN" bo'limi): qisqa teglarga bog'liqlik,
   `functions.php` da takror kod, `index.php` audio nomi mantig'i, repozitoriyada DB sxema
   dump'i yo'q (`db/schema.sql` qo'shish tavsiya etiladi).
