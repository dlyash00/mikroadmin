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
				<input type="submit" name = "submit" form = "auth" title = "Вход" id = "btn_enter_submit"/>
			</form>
		</div>
		
	</main>

</div>
<script src="js/jquery-3.6.0.min.js"></script>
<script src="js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
<script src="js/crypto-js.min.js"></script>
<script>
	$('document').ready(function () {
		$('#btn_enter_submit').click(function (e) { 
			e.preventDefault();

			let username = $('#inp-user-name').val();
			let pass_hash = CryptoJS.MD5($('#inp-user-pass').val()).toString();
			let array = {
				username: username,
				pass_hash: pass_hash
			};

			$.ajax({
                type: "GET",
                url: "php/auth.php",
                data: {USER_ENTER: array},
				success: function(res){
					window.open(res);
				}
            });
		});
	});
</script>
</body>
</html>