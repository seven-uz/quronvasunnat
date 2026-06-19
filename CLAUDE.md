# CLAUDE.md

Bu fayl Claude Code (va boshqa AI agentlar) uchun ushbu repozitoriyda ishlash yo'riqnomasi.

## Xotira va ish jarayoni (har doim)

- Loyiha holati, qarorlar va keyingi qadamlar [MEMORY.md](MEMORY.md) da (ko'chma xotira).
  Suhbat boshida uni o'qing; loyiha boshqa kompyuterda ham shu fayldan davom etadi.
- **Har mazmunli o'zgarishdan keyin:** `MEMORY.md` ni yangilang (holat/keyingi qadamlar) →
  commit qiling → `git push` qiling. Loyiha doim git bilan sinxron yuradi.
- Commit oldidan o'zgargan PHP ni `php -d short_open_tag=On -l` bilan lint qiling.
- Maxfiy qiymatlar (parol/token/kredensial) hech qachon commit qilinmaydi — faqat
  gitignore'dagi `blocks/db.local.php` va `adm/config.local.php` da.

@MEMORY.md

## Loyiha haqida

"Qur'on va Sunnat" — O'zbek tilidagi islomiy ma'lumot sayti. Protsedural PHP + `mysqli` +
MySQL, frontend Bootstrap 4/jQuery. Batafsil: [README.md](README.md).

## Muhit

- PHP: `D:\Workspace\Developing\OSPanel\modules\php\PHP_8.1\php` (8.1.9).
- Sayt **qisqa teglar** ishlatadi (`<?`, `<?=`). Lint qilishda:
  ```
  php -d short_open_tag=On -l <fayl>
  ```
- Fayllar **CRLF** qator tugashidan foydalanadi. Tahrirlashda buni hisobga oling
  (ko'p qatorli moslash CRLF tufayli buzilishi mumkin — bitta qatorli almashtirish ishonchli).
- Saytni shu yerda ishga tushirib bo'lmaydi (OSPanel + MySQL kerak). UI tekshirilmasa,
  buni ochiq ayting.

## Konvensiyalar (mavjud uslubni saqlang)

- Har frontend sahifasi: `blocks/brain.php` → `functions.php` → `blocks/head.php` →
  `blocks/header.php` → `<main>` → `blocks/footer.php`. Yangi sahifa shu naqshga ergashsin.
- Indentatsiya: TAB.
- DB: protsedural `mysqli_*` (PDO emas). Yangi kod ham `mysqli` da bo'lsin.
- Chiqariladigan matnni tarjima uchun `word()` ga o'rang (qarang: i18n bo'limi).
- Kichik fayllar uchun emas — har gal `<?` ochish/yopishni mavjud kabi qoldiring.

## Xavfsizlik qoidalari (MUHIM)

Bu legacy kodda tarixan SQL injection va XSS bo'lgan. Yangi yoki tahrirlangan kodda:

1. **SQL'ga foydalanuvchi kiritmasi (`$_GET/$_POST/$_REQUEST/$_COOKIE`) hech qachon
   to'g'ridan-to'g'ri qo'shilmasin.**
   - Butun son (id, sura raqami): `intval($_GET['id'])`.
   - Matn / LIKE: `mysqli_prepare` + `mysqli_stmt_bind_param` (`?` placeholderlar).
   - Naqsh va misollar: [.claude/skills/safe-db-query](.claude/skills/safe-db-query/SKILL.md).
2. **HTML'ga chiqarishdan oldin** foydalanuvchi matni `htmlspecialchars($v, ENT_QUOTES, 'UTF-8')`.
3. **`mysqli_error()` ni mijozga chiqarmang** — umumiy xabar bering, log'ga yozing.
4. **DB parollari** faqat `blocks/db.local.php` (gitignore) da. `db.php` ga yozmang.
5. `preg_replace` ga foydalanuvchi matni kirsa — `preg_quote($v, '/')`.

Har commit'dan oldin o'zgargan PHP'ni `php -d short_open_tag=On -l` bilan lint qiling.

## Til / transliteratsiya (i18n)

- `word($matn)`: til cookie `uzk` (kirill) bo'lsa `tokr()` orqali o'giradi, aks holda
  matnni o'zini qaytaradi. Sayt manba matni **lotin** o'zbekchada saqlanadi.
- Yangi ko'rinadigan matnlarni `word('...')` ichida bering.
- Batafsil: [.claude/skills/uz-i18n](.claude/skills/uz-i18n/SKILL.md).

## Admin panel (`adm/`)

Qur'on kontentini boshqarish uchun **yangi, kichik va xavfsiz** panel (eski begona ERP
o'chirildi). Arxitektura:

- `adm/config.php` + `config.local.php` (gitignore): `ADMIN_USER`/`ADMIN_PASS_HASH` va
  `$ADMIN_TABLES` **oq ro'yxati** — SQL'ga faqat shu kalitlar (jadval nomlari) tushadi.
- `adm/inc/bootstrap.php`: sessiya, DB (`blocks/db.php`), CSRF, `require_login()`,
  `SHOW COLUMNS` introspeksiyasi. CRUD: `login/logout/index/list/edit/delete.php`.
- Yangi jadvalni boshqarishga qo'shish: `$ADMIN_TABLES` ga kalit + `label` + `list` ustunlar.
  Formalar `SHOW COLUMNS` orqali avtomatik quriladi.

## Agentlar va skillar

- `.claude/agents/php-security-reviewer.md` — diff'ni SQLi/XSS/sir uchun ko'rib chiqadi.
- `.claude/agents/content-manager.md` — duo/hadis/sunnat kabi kontent qo'shish/tahrirlash.
- `.claude/skills/safe-db-query/` — xavfsiz `mysqli` so'rovlari naqshi.
- `.claude/skills/uz-i18n/` — `word()`/`tokr()` transliteratsiya tizimi.

## E'tibor bermaslik kerak

- `assets/` ostidagi katta CSS/JS bundle'lar — uchinchi tomon (Metronic/Bootstrap), tahrirlamang.
  `assets/css/style.css` ICHIDA: 1–3110-qatorlar arab `@font-face` bloki (Qur'on o'quvchisi
  uchun) — tegmang; yangi dizayn-tizim shundan keyin keladi.
