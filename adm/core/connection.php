<?php

include 'config.php';

$db = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
if (!$db) {
	echo 'Xato: Xatolik kodi - 7. Iltimos, sayt boshqaruvi mas’ullariga xatolik xaqida habar bering! <a href="mailto:'.DEVELOPER_MAIL.'">Habar yuborish</a><br>';
	echo 'Ошибка: код 7. Пожалуйста, сообщите об ошибке администраторам сайта! <a href="mailto:'.DEVELOPER_MAIL.'">Отправить сообщение</a><br>';
	echo 'Error: code is 7. Please report the error to site administrators! <a href="mailto:'.DEVELOPER_MAIL.'">Send a message</a>';
	exit;
}