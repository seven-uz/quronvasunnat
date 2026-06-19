<?php

// Bu fayl NAMUNA. Uni "config.local.php" deb nusxalang va o'zingiznikini kiriting.
// config.local.php .gitignore ichida — hech qachon commit qilinmaydi.
//
// Yangi parol hash'ini yaratish (terminalda):
//   php -r "echo password_hash('SIZNING_PAROLINGIZ', PASSWORD_DEFAULT), PHP_EOL;"
// Chiqqan qiymatni quyidagi ADMIN_PASS_HASH ga qo'ying.

define('ADMIN_USER', 'admin');

// Quyidagi namuna hash — parol: "admin". Production'da ALBATTA o'zgartiring.
define('ADMIN_PASS_HASH', '$2y$10$0j.f5RoUwuoxRTn0N1VqJuTkGiLwGtHn0uSqrxVU4PgcL.fFvSF4O');
