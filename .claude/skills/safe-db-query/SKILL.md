---
name: safe-db-query
description: Write safe procedural mysqli queries for the Qur'on va Sunnat PHP codebase. Use whenever code reads $_GET/$_POST/$_REQUEST/$_COOKIE and uses it in a SQL query, when adding a new DB read/write, or when fixing/reviewing SQL injection. Covers intval for integers, mysqli_prepare + bind_param for text/LIKE, and the patterns to avoid.
---

# Safe mysqli queries (procedural)

This codebase uses **procedural `mysqli`** with a global `$db` (from
`blocks/db.php`). Do NOT introduce PDO. Treat every `$_GET/$_POST/$_REQUEST/
$_COOKIE/$_SERVER` value as attacker-controlled.

## Rule of thumb
- The value is an **integer** (id, sura number, page) → wrap in `intval()`.
- The value is **anything else** (text, search term, LIKE, name) → use a
  **prepared statement** with `?` placeholders and `bind_param`.
- A column/table **name** can never be parameterized → validate against an
  allow-list of known names; never build it from raw input.

## Pattern 1 — integer id (most content pages)

```php
// BAD — SQL injection:
$row = mysqli_query($db, "SELECT * FROM duolar WHERE id = '{$_GET['id']}'");

// GOOD:
$id  = intval($_GET['id']);
$row = mysqli_query($db, "SELECT * FROM duolar WHERE id = $id");
```

Also use the safe integer for any later arithmetic/echo (`$id - 1`, hrefs), so a
non-numeric `?id=abc` can't cause a PHP 8 `TypeError` (HTTP 500).

## Pattern 2 — text / LIKE search (prepared statement)

```php
// BAD — interpolation, and a prepare with no placeholders is still injectable:
$sql = "SELECT * FROM suralar WHERE mano LIKE '%$word%'";
$stmt = mysqli_prepare($db, $sql);   // useless: no ? to bind

// GOOD:
$like = '%' . $word . '%';
$sql  = "SELECT * FROM suralar WHERE mano LIKE ? OR text LIKE ? OR textar LIKE ?";
if ($stmt = mysqli_prepare($db, $sql)) {
    mysqli_stmt_bind_param($stmt, 'sss', $like, $like, $like);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        while ($r = mysqli_fetch_assoc($result)) {
            // ... use $r
        }
    }
}
```

`bind_param` type chars: `s` string, `i` integer, `d` double, `b` blob.
One char per `?`, in order.

## Pattern 3 — INSERT / UPDATE with user data

```php
$title = $_POST['title'];
$text  = $_POST['text'];
$stmt  = mysqli_prepare($db, "INSERT INTO sunnatlar (title, text) VALUES (?, ?)");
mysqli_stmt_bind_param($stmt, 'ss', $title, $text);
mysqli_stmt_execute($stmt);
$newId = mysqli_insert_id($db);
```

## Pattern 4 — column/table name from input (allow-list)

```php
$allowed = ['title', 'text', 'mano'];
$col = in_array($_POST['column'], $allowed, true) ? $_POST['column'] : 'title';
// safe to embed $col now (it can only be a known value)
```

## Output safety (XSS) — always pair with queries
When echoing DB or user values into HTML:

```php
echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
```
Note: Arabic content columns (`*ar`) in this site are intentionally rendered as
HTML in places; for **user** input there is no such exception — always escape.

## Never do this
- Never send `mysqli_error($db)` to the browser. Show a generic message; log details.
- Never put a raw `$_GET/$_POST` value inside a `preg_replace` pattern without
  `preg_quote($v, '/')`.
- Never hardcode DB credentials — they live in `blocks/db.local.php` (gitignored).

## Verify
After writing a query, lint the file:
```
php -d short_open_tag=On -l <file>
```
