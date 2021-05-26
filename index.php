<?php
	session_start();
	error_reporting(E_ALL | E_STRICT);
	//header('Content-Type: text/html; charset=utf-8');
	define('DOCROOT', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
	<link rel = "stylesheet" type = "text/css" href="css/stylesheet.css"> 
	<link rel = "icon" type = "image/svg" href = "img/admin.svg"/>
    <title>Авторизация</title>
</head>
<body>
<div class = "wrapper">

	<!-- Заголовок -->
	<header>
		<div class = "logo"><img src="../../img/mikroadmin_logo.svg" alt="Logo" height = "25px"></div>
		<div class = "title"><h1>Авторизация</h1></div>
	</header>

	<!-- Контент -->
	<main id ='content'>

		<!-- Форма авторизации -->
		<div id = "auth-form">
			<p style = "font-size: 0.8em; color: #7AB9E5; margin-bottom: 20px"><b>Введите данные для входа:</b></p>
			<form name = "auth" id = "auth" method = "POST" action = "php/auth.php">
				<input type="text" form = "auth" name = "user-name" id = "inp-user-name" placeholder = "Имя пользователя"/>
				<input type="password" form = "auth" name = "user-pass" id = "inp-user-pass" placeholder = "Пароль"/>
				<input type="submit" name = "submit" form = "auth" title = "Вход"/>
			</form>
		</div>
		
	</main>

</div>
</body>
</html>