# Qur'on va Sunnat

O'zbek tilidagi islomiy ma'lumot sayti: Qur'on (oyatlar, suralar, tarjima va audio qiroat), duolar, hadislar, sunnatlar, Asma ul-Husna (Allohning 99 ismi), namoz vaqtlari, hijriy kalendar va boshqalar.

Sayt to'rt tilda/yozuvda ishlaydi va matnni **vaqtida** (on-the-fly) o'giradi:
`uzl` (lotin), `uzk` (kirill), `ru`, `en`.

---

## Texnologiyalar

| Qatlam        | Texnologiya                                          |
|---------------|------------------------------------------------------|
| Backend       | PHP 8.3 mos (protsedural), `mysqli`                   |
| Ma'lumotlar b.| MySQL / MariaDB (`quronvasunnat`)                    |
| Frontend      | Bootstrap 4, jQuery, OwlCarousel 2, Font Awesome 5   |
| Server        | Apache (`.htaccess` rewrite), mahalliy — OSPanel     |
| Analitika     | Yandex.Metrika                                        |
| Tashqi API    | aladhan.com (hijriy sana), everyayah.com (audio)     |

> Eslatma: kod **qisqa PHP teglari** (`<?` / `<?=`) dan foydalanadi.
> Server `php.ini` da `short_open_tag = On` bo'lishi shart.

---

## Loyiha tuzilishi

```
/                     # Frontend (ommaviy sayt)
├── index.php         # Bosh sahifa (tasodifiy oyat/duo/sunnat + so'nggilar)
├── quron.php         # Qur'on: suralar, juz, sahifa, sajda + audio
├── duo.php           # Duolar ro'yxati va bitta duo (?id=)
├── hadis.php         # Hadislar ro'yxati va bitta hadis (?id=)
├── sunnat.php        # Sunnatlar ro'yxati va bitta sunnat (?id=)
├── asmaulhusna.php   # Allohning 99 ismi
├── search.php        # To'liq qidiruv natijalari sahifasi (?q=)
├── iymon, roza, haj, zakot, namoz, kalendar, prayertimes ...
├── livesearch.php    # AJAX jonli qidiruv (suralar bo'yicha)
├── functions.php     # Yordamchi funksiyalar (word(), tokr(), sana, telefon ...)
│
├── blocks/           # Umumiy bloklar (har sahifa ulaydi)
│   ├── brain.php     # session_start + db.php + cookie/til boshqaruvi + $now
│   ├── db.php        # MySQL ulanishi (mahalliy: db.local.php)
│   ├── head.php      # <!doctype> ... <head> ... <body>
│   ├── header.php    # Yuqori menyu / header
│   ├── footer.php    # Pastki qism + skriptlar
│   └── nav.php, top.php, header2.php
│
├── actions/
│   └── contactform.php  # Aloqa formasi (mail yuborish)
│
├── assets/           # css, js, images, audio, media
│
└── adm/              # Kichik, xavfsiz Qur'on kontent admini (CRUD — pastga qarang)
```

### Sahifa shabloni (har bir frontend sahifasi)

```php
<?
require 'blocks/brain.php';     // session, DB, til, $now
require 'functions.php';        // word(), tokr(), va h.k.
$page       = "quron";          // faol menyu uchun
$pageTitle  = word('Sarlavha'); // <title> uchun
$headerImg  = "quran.webp";     // header foni rasmi
$headerColor= "linear-gradient(...)";
require 'blocks/head.php';
require 'blocks/header.php';
?>
<main> ... </main>
<? include 'blocks/footer.php' ?>
```

---

## Ma'lumotlar bazasi (kuzatilgan jadvallar)

Frontend quyidagi jadvallarni o'qiydi (sxema kodga qarab tiklangan):

| Jadval          | Asosiy ustunlar (kuzatilgan)                                   |
|-----------------|---------------------------------------------------------------|
| `suralar`       | `id, ns, no, np, nj, sajda, title, textar, text, mano`        |
| `suranames`     | `id, title`                                                   |
| `duolar`        | `id, type, title, titlear, textar, text, mano, audio`         |
| `hadislar`      | `id, id_roviy, id_rivoyatchi, title, titlear, textar, text, mano` |
| `roviylar`      | `id, name`                                                    |
| `rivoyatchilar` | `id, id_kunya, name`                                          |
| `kunyalar`      | `id, title`                                                   |
| `sunnatlar`     | `id, title, text`                                             |
| `asmaulhusna`   | `id, title, titlear, text, manbainkoran`                     |
| `quron`         | `id, title, text` (Qur'on haqida matn)                        |
| `qorilar`       | qiroat qiluvchilar ro'yxati                                   |
| `tags`, `words` | teglar / tarjima lug'ati                                      |

> To'liq sxema: [`db/schema.sql`](db/schema.sql) — koddan qayta tiklangan struktura
> (ma'lumot dump'i emas). Yangi muhitda import qiling (pastga qarang).

---

## Mahalliy ishga tushirish (OSPanel, Windows)

1. Repozitoriyni OSPanel domeni papkasiga joylang
   (`OSPanel/domains/quronvasunnat`).
2. OSPanel'da PHP 8.1 va MySQL ni yoqing. `php.ini`: `short_open_tag = On`.
3. MySQL'da bazani yarating va sxemani import qiling:
   ```
   mysql -u root -e "CREATE DATABASE quronvasunnat CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   mysql -u root quronvasunnat < db/schema.sql
   ```
   So'ng kontentni (oyatlar, duolar, hadislar ...) to'ldiring — admin panel orqali yoki o'z dump'ingizdan.
4. Mahalliy ulanish: standart `blocks/db.php` `root` / bo'sh parol bilan ishlaydi.
   Production uchun:
   ```
   cp blocks/db.local.php.example blocks/db.local.php
   # db.local.php ichida haqiqiy host/user/parolni yozing
   ```
5. Saytni oching: `http://quronvasunnat/`.

---

## Xavfsizlik eslatmalari

- **DB kredensiallari** endi `db.local.php` (gitignore) orqali beriladi.
  Parollarni `db.php` ga yozmang.
- Foydalanuvchi kiritmasi SQL'ga qo'shilganda **doim** parametrlangan so'rov
  (`mysqli_prepare` + `bind_param`) yoki butun sonlar uchun `intval()` ishlating.
  Batafsil: [.claude/skills/safe-db-query](.claude/skills/safe-db-query/SKILL.md).
- Sahifada chiqarishdan oldin foydalanuvchi matnini `htmlspecialchars()` bilan eskeyp qiling.
- To'liq audit: [docs/ANALYSIS.md](docs/ANALYSIS.md).

---

## Admin panel (`adm/`)

`adm/` — Qur'on kontentini boshqarish uchun **kichik va xavfsiz** CRUD panel (eski begona
Metronic ERP butunlay o'chirildi). Xususiyatlari:

- `password_verify` + `hash_equals` bilan login, barcha POST'da CSRF token.
- `$ADMIN_TABLES` **oq ro'yxati** (`duolar, hadislar, sunnatlar, asmaulhusna, tags`) —
  SQL'ga faqat shu jadval nomlari tushadi; formalar `SHOW COLUMNS` orqali quriladi.
- CRUD to'liq `mysqli_prepare` + `bind_param`; chiqish `htmlspecialchars`.

Maxfiy login/parol `adm/config.local.php` (gitignore) da turadi. Namuna:
`adm/config.local.example.php`. Yangi parol hash'i:
```
php -r "echo password_hash('YANGI_PAROL', PASSWORD_DEFAULT);"
```

---

## Til / transliteratsiya tizimi

`functions.php` ichida:

- `word($matn)` — joriy til cookie'siga qarab matnni qaytaradi; `uzk` (kirill) bo'lsa
  `tokr()` orqali lotindan kirillga o'giradi.
- `tokr()` / `translitkir_lot()` — lotin↔kirill o'girish jadvallari.
- Til `?lang=uzl|uzk|ru|en` orqali almashtiriladi (cookie'ga yoziladi).

Batafsil: [.claude/skills/uz-i18n](.claude/skills/uz-i18n/SKILL.md).
