---
name: uz-i18n
description: Understand and correctly use the Uzbek Latin/Cyrillic transliteration and multi-language system (word(), tokr(), translitkir_lot(), the lang cookie) in the Qur'on va Sunnat site. Use whenever adding or editing displayed text, building a content page, debugging wrong-script output, or working with the ?lang switch.
---

# Uzbek i18n & transliteration

The site stores its source text in **Latin Uzbek** and converts to Cyrillic
**on the fly** for display. There is no separate translation table for uz —
conversion is algorithmic. (`ru`/`en` strings are written inline where needed.)

All helpers live in `functions.php`.

## The language cookie
- Cookie `lang` ∈ `uzl` (Latin), `uzk` (Cyrillic), `ru`, `en`.
- Default is set in `blocks/brain.php`: if unset, `uzk`.
- Switched via URL `?lang=uzl|uzk|ru|en` (handled in `brain.php`/`head.php`):
  it sets the cookie and redirects back.

## `word($value)` — the main function
```php
function word($value){
    if(($_COOKIE['lang'] ?? '') == 'uzk'){
        return tokr($value);   // Latin -> Cyrillic
    }else{
        return $value;         // Latin / ru / en as-is
    }
}
```
Wrap every **displayed, translatable** string in `word(...)`:
```php
<?php echo word('To‘liq o‘qish') ?>
<?php echo word($row['title']) ?>
```

## Do NOT pass these through `word()`
- **Arabic** columns (`titlear`, `textar`) — they must stay Arabic.
- Numbers, URLs, CSS, audio file names, DB identifiers.
- Already-Cyrillic or `ru`-only strings.

## `tokr()` and `translitkir_lot()`
- `tokr($s)` — Latin → Cyrillic map (used by `word()` for `uzk`). Handles digraphs
  first (`o‘`→`ў`, `g‘`→`ғ`, `sh`→`ш`, `ch`→`ч`, `ya`→`я`...) then single letters,
  and preserves a small set of HTML tags.
- `translitkir_lot($s)` — Cyrillic → Latin map (used when normalizing input in
  `textType*`).

## Apostrophes / special letters (important)
Uzbek Latin uses `oʻ gʻ` (oʻzbekcha) and curly quotes. `textType()` / `textTypeWT()`
normalize typed input: `O'`→`O‘`, `g'`→`g‘`, `'`→`’`, straight quotes → `“ ”`.
When inserting user-provided Uzbek text, run it through the appropriate
`textType*` helper so the script/quotes stay consistent.

## Adding a new visible string — checklist
1. Write it in **Latin Uzbek**.
2. Wrap in `word('...')`.
3. If it contains `o‘`, `g‘`, or apostrophes, use the proper `‘`/`’` characters so
   `tokr()` maps them correctly (plain `'` becomes `’`, not the digraph).
4. Leave Arabic (`*ar`) untouched.

## Debugging wrong-script output
- All Cyrillic / all Latin regardless of menu → check the `lang` cookie value and
  that `word()` actually wraps the string.
- A few letters wrong in Cyrillic → the Latin source used a plain `'`/`o'` instead
  of the curly `‘`/`o‘`; fix the source characters.
- Arabic showing as garbled Cyrillic → an `*ar` value was wrongly passed through
  `word()`; remove the wrapper.
