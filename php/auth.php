<?php 
// АВТОРИЗАЦИЯ ПОЛЬЗОВАТЕЛЯ

	session_start();
	error_reporting(E_ALL | E_STRICT);
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	header('Content-Type: text/html; charset=utf-8');

	$this_username = $_POST['user-name'];	// Имя пользователя
	$this_password = $_POST['user-pass'];	// Пароль
	$_SESSION['name'] = $this_username;		// Заполняем переменную сессии 'Имя'

	include "core/mysql_db_connecting.php";	    // Файл для подключения к БД MySQL

	mysqli_set_charset($link, 'utf8');	// Устанавливаем кодировку utf-8

    $query = ("SELECT `name`, `password` FROM `global_user` WHERE `name` = '$this_username'");	// Запрос к БД для получения массива зарегистрированных пользователей
	$result = mysqli_query($link, $query);					// Выполнение запроса
	$records = mysqli_fetch_all($result, MYSQLI_ASSOC);		// Добавляем строки результата запроса в ассоциативный массив (MYSQLI_ASSOC)
    $close = mysqli_close($link);

    if($this_password == $records[0]['password']){
        header('Location: page/home.php');
    } else echo 'Неверные данные';
 ?>