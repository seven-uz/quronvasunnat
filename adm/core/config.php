<?php

$versions = [
	'main' => [
		0, 1,
	],
	'fixes' => [
		0, 1, 2, 3
	],
	'caching' => [
		0,8
	],
];

define('VERSION', end($versions['main']) . '.' . end($versions['fixes']) . '.' . end($versions['caching']));
define('BUNDLE_VERSION', '1.0.1');
unset($versions);
define('ROOT_DIR', $_SERVER['DOCUMENT_ROOT']);
define('DEVELOPER_MAIL', 'seven.uz@mail.ru');
define('SITE', explode('/', $_SERVER['SERVER_PROTOCOL'])[0] . '://' . DOMAIN . '/');
define('ADMIN_PAGE', SCHEME . '://' . SUBDOMAIN . DOMAIN . ADMIN_STRING);
define('OFFSET', 5);
define('SCRIPTNAME', explode('.', end(explode('/', $_SERVER['PHP_SELF'])))[0]);
define('LOGOUTTIME', (5 * 60 * 60));
define('USER_UPDATE_TIME', (3 * 60));

define('LANGUAGES', [
	'uzl' => ['title' => 'O‘zbekcha', 'icon' => 'uzbekistn', 'status' => true],
	'uzk' => ['title' => 'Ўзбекча', 'icon' => 'uzbekistn', 'status' => true],
	// 'ru' => ['title' => 'Русский', 'icon' => 'russia', 'status' => true],
	// 'en' => ['title' => 'English', 'icon' => 'united-states-of-america', 'status' => true],
	// 'ar' => ['title' => 'العربية', 'icon' => 'saudi-arabia', 'status' => false],
	// 'tr' => ['title' => 'Türkçe', 'icon' => 'turkey', 'status' => false],
	// 'de' => ['title' => 'Deutsch', 'icon' => 'germany', 'status' => false],
	// 'cn' => ['title' => '中文', 'icon' => 'china', 'status' => false],
	// 'in' => ['title' => 'भारतीय', 'icon' => 'india', 'status' => false],
	// 'kr' => ['title' => 'Кыргыздар', 'icon' => 'kyrgyzstan', 'status' => false],
	// 'kz' => ['title' => 'Қазақ', 'icon' => 'kazakhstan', 'status' => false],
	// 'tm' => ['title' => 'Türkmenler', 'icon' => 'turkmenistan', 'status' => false],
	// 'tj' => ['title' => 'Тоҷикӣ', 'icon' => 'tajikistan', 'status' => false],
]);