-- =====================================================================
-- Qur'on va Sunnat — ma'lumotlar bazasi sxemasi (struktura)
-- =====================================================================
-- Bu fayl koddagi so'rovlardan QAYTA TIKLANGAN sxema (ustun nomlari va
-- taxminiy turlar). Bu ma'lumotlar (data) dump'i EMAS — faqat struktura.
-- Maqsad: yangi muhitda loyihani o'rnatishni osonlashtirish.
--
-- O'rnatish:
--   mysql -u root -p -e "CREATE DATABASE quronvasunnat CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
--   mysql -u root -p quronvasunnat < db/schema.sql
--
-- Eslatma: ustun turlari kontentga qarab moslashtirilishi mumkin. Agar sizda
-- haqiqiy to'liq dump bo'lsa, undan foydalaning — bu fayl zaxira reja.
-- =====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----- Sura nomlari (Qur'ondagi 114 sura metama'lumoti) ----------------
CREATE TABLE IF NOT EXISTS `suranames` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,   -- sura raqami (1..114)
  `title`      VARCHAR(255) NOT NULL,                  -- sura nomi (lotin)
  `title_page` VARCHAR(255) DEFAULT NULL,              -- maxsus shrift uchun glif
  `info`       TEXT         DEFAULT NULL,              -- sura haqida qisqa ma'lumot
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----- Oyatlar (har bir oyat — alohida qator) --------------------------
CREATE TABLE IF NOT EXISTS `suralar` (
  `id`     INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ns`     SMALLINT UNSIGNED NOT NULL,                 -- sura raqami
  `no`     SMALLINT UNSIGNED NOT NULL,                 -- oyat raqami
  `np`     SMALLINT UNSIGNED DEFAULT NULL,             -- mushaf sahifa raqami (shrift: page<np>)
  `nj`     SMALLINT UNSIGNED DEFAULT 0,                -- juz (pora) raqami
  `sajda`  TINYINT(1) NOT NULL DEFAULT 0,              -- sajda oyati (1/0)
  `title`  VARCHAR(255) DEFAULT NULL,                  -- sura nomi (qulaylik uchun)
  `textar` TEXT DEFAULT NULL,                          -- oyat (arabcha, standart)
  `textp`  TEXT DEFAULT NULL,                          -- oyat (mushaf shrifti uchun glif kodlari)
  `mano`   TEXT DEFAULT NULL,                          -- ma'nosi (tarjima)
  PRIMARY KEY (`id`),
  KEY `idx_ns` (`ns`),
  KEY `idx_ns_no` (`ns`, `no`),
  KEY `idx_np` (`np`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----- Duolar ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `duolar` (
  `id`      INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title`   VARCHAR(255) NOT NULL,
  `titlear` VARCHAR(255) DEFAULT NULL,                 -- sarlavha (arabcha)
  `textar`  TEXT DEFAULT NULL,                         -- duo matni (arabcha)
  `text`    TEXT DEFAULT NULL,                         -- o'qilishi (transliteratsiya)
  `mano`    TEXT DEFAULT NULL,                         -- ma'nosi
  `audio`   VARCHAR(255) DEFAULT NULL,                 -- assets/audio/duo/<audio>
  `type`    TINYINT(1) NOT NULL DEFAULT 0,             -- 1 = kunlik/maxsus
  PRIMARY KEY (`id`),
  KEY `idx_type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----- Hadislar --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `hadislar` (
  `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title`          VARCHAR(255) NOT NULL,
  `titlear`        VARCHAR(255) DEFAULT NULL,
  `textar`         TEXT DEFAULT NULL,                  -- hadis (arabcha)
  `text`           TEXT DEFAULT NULL,                  -- hadis matni
  `mano`           TEXT DEFAULT NULL,                  -- ma'nosi / izoh
  `id_roviy`       INT UNSIGNED DEFAULT NULL,          -- roviylar.id
  `id_rivoyatchi`  INT UNSIGNED DEFAULT NULL,          -- rivoyatchilar.id
  PRIMARY KEY (`id`),
  KEY `idx_roviy` (`id_roviy`),
  KEY `idx_rivoyatchi` (`id_rivoyatchi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----- Sunnatlar -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `sunnatlar` (
  `id`    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `text`  TEXT DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----- Asma ul-Husna (Allohning 99 ismi) -------------------------------
CREATE TABLE IF NOT EXISTS `asmaulhusna` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title`         VARCHAR(255) NOT NULL,               -- ism (lotin)
  `titlear`       VARCHAR(255) DEFAULT NULL,           -- ism (arabcha)
  `text`          TEXT DEFAULT NULL,                   -- ta'rifi
  `manbainkoran`  VARCHAR(500) DEFAULT NULL,           -- "sura:oyat,sura:oyat" ko'rinishida manbalar
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----- Teglar ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tags` (
  `id`    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(190) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----- "Qur'on haqida" maqolasi (id=1 bosh sahifada) -------------------
CREATE TABLE IF NOT EXISTS `quron` (
  `id`    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `text`  TEXT DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----- Roviylar (hadisni rivoyat qilganlar) ----------------------------
CREATE TABLE IF NOT EXISTS `roviylar` (
  `id`   INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----- Rivoyatchilar ---------------------------------------------------
CREATE TABLE IF NOT EXISTS `rivoyatchilar` (
  `id`       INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`     VARCHAR(255) NOT NULL,
  `id_kunya` INT UNSIGNED DEFAULT NULL,                -- kunyalar.id
  PRIMARY KEY (`id`),
  KEY `idx_kunya` (`id_kunya`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----- Kunyalar --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `kunyalar` (
  `id`    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----- Qorilar (hofizlar ro'yxati — ixtiyoriy) -------------------------
CREATE TABLE IF NOT EXISTS `qorilar` (
  `id`   INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ----- Boshlang'ich namuna ma'lumot (bosh sahifa bo'sh qolmasligi uchun) -----
INSERT INTO `quron` (`id`, `title`, `text`) VALUES
  (1, 'Qur''on haqida', 'Qur''oni Karim — Alloh taoloning Muhammad (sollallohu alayhi vasallam)ga nozil qilgan kalomi.')
  ON DUPLICATE KEY UPDATE `id` = `id`;
