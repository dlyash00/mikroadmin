<?php
session_start();
error_reporting(E_ALL | E_STRICT);

if (isset($_SESSION['isAuth'])){
    if (!$_SESSION['isAuth']) die ("У вас нет доступа");
} else die ("У вас нет доступа");

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="../../css/stylesheet.css"> 
    <link rel = "icon" type = "image/svg" href = "../../img/admin.svg"/>
    <title>Администратор</title>
</head>
<body>
<div class = "wrapper">

    <!-- Заголовок -->
    <header>
		<div class = "logo"><a href="#"><img src="../../img/mikroadmin_logo.svg" alt="Logo" height = "25px"></a></div>
		<div class = "title">
            <p class = "text-black"><img src="../../img/user1.svg" height = "25px">&nbsp&nbspАдминистратор</p>
        </div>
	</header>

    <!-- Основная часть -->
	<main>

        <!-- Сайд-бар -->
        <aside>
            <p></p>

            <!-- Меню навигации -->
            <nav>
                <button id = "btn-goToHome"><img src="../../img/back.svg" height = "20px">Панель управления</button>
            </nav>
        </aside>

        <!-- Контент -->
        <article>
            <p class = "text-medium" style = "margin-bottom: 20px;">
            <svg height="20px" viewBox="0 0 512 511" width="20px" xmlns="http://www.w3.org/2000/svg">
                <path style = "fill: #000" d="m405.332031 256.484375c-11.796875 0-21.332031 9.558594-21.332031 21.332031v170.667969c0 11.753906-9.558594 21.332031-21.332031 21.332031h-298.667969c-11.777344 0-21.332031-9.578125-21.332031-21.332031v-298.667969c0-11.753906 9.554687-21.332031 21.332031-21.332031h170.667969c11.796875 0 21.332031-9.558594 21.332031-21.332031 0-11.777344-9.535156-21.335938-21.332031-21.335938h-170.667969c-35.285156 0-64 28.714844-64 64v298.667969c0 35.285156 28.714844 64 64 64h298.667969c35.285156 0 64-28.714844 64-64v-170.667969c0-11.796875-9.539063-21.332031-21.335938-21.332031zm0 0"/>
                <path style = "fill: #000" d="m200.019531 237.050781c-1.492187 1.492188-2.496093 3.390625-2.921875 5.4375l-15.082031 75.4375c-.703125 3.496094.40625 7.101563 2.921875 9.640625 2.027344 2.027344 4.757812 3.113282 7.554688 3.113282.679687 0 1.386718-.0625 2.089843-.210938l75.414063-15.082031c2.089844-.429688 3.988281-1.429688 5.460937-2.925781l168.789063-168.789063-75.414063-75.410156zm0 0"/>
                <path style = "fill: #000" d="m496.382812 16.101562c-20.796874-20.800781-54.632812-20.800781-75.414062 0l-29.523438 29.523438 75.414063 75.414062 29.523437-29.527343c10.070313-10.046875 15.617188-23.445313 15.617188-37.695313s-5.546875-27.648437-15.617188-37.714844zm0 0"/>
            </svg>
            &nbspИзменить пароль администратора</p>
            <input type="password" name = "user-password" id = "inp-user-password" placeholder = "Новый пароль"/>
            <input type="password" name = "user-password" id = "inp-user-password" placeholder = "Подтвердите новый пароль"/>
            <input type="submit" value="Подтвердить">

            <p></p>
            <p class = "text-medium" style = "margin-bottom: 20px;">
            <svg height="20px" viewBox="0 0 512 511" width="20px" xmlns="http://www.w3.org/2000/svg">
                <path style = "fill: #000" d="m405.332031 256.484375c-11.796875 0-21.332031 9.558594-21.332031 21.332031v170.667969c0 11.753906-9.558594 21.332031-21.332031 21.332031h-298.667969c-11.777344 0-21.332031-9.578125-21.332031-21.332031v-298.667969c0-11.753906 9.554687-21.332031 21.332031-21.332031h170.667969c11.796875 0 21.332031-9.558594 21.332031-21.332031 0-11.777344-9.535156-21.335938-21.332031-21.335938h-170.667969c-35.285156 0-64 28.714844-64 64v298.667969c0 35.285156 28.714844 64 64 64h298.667969c35.285156 0 64-28.714844 64-64v-170.667969c0-11.796875-9.539063-21.332031-21.335938-21.332031zm0 0"/>
                <path style = "fill: #000" d="m200.019531 237.050781c-1.492187 1.492188-2.496093 3.390625-2.921875 5.4375l-15.082031 75.4375c-.703125 3.496094.40625 7.101563 2.921875 9.640625 2.027344 2.027344 4.757812 3.113282 7.554688 3.113282.679687 0 1.386718-.0625 2.089843-.210938l75.414063-15.082031c2.089844-.429688 3.988281-1.429688 5.460937-2.925781l168.789063-168.789063-75.414063-75.410156zm0 0"/>
                <path style = "fill: #000" d="m496.382812 16.101562c-20.796874-20.800781-54.632812-20.800781-75.414062 0l-29.523438 29.523438 75.414063 75.414062 29.523437-29.527343c10.070313-10.046875 15.617188-23.445313 15.617188-37.695313s-5.546875-27.648437-15.617188-37.714844zm0 0"/>
            </svg>
            &nbspПочта</p>
            <input type="email" name="user-mail" id="inp-user-mail" placeholder = "Изменить адрес почты">
            <input type="submit" value="Подтвердить">
        </article>

    </main>

</div>
</body>
<script src="../../js/jquery-3.6.0.min.js"></script>
<script src="../../js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
<script>

    function getTime()
    {
        let time = new Date();
        return time.getHours()+':'+time.getMinutes()+':'+time.getSeconds();
    }

    $('document').ready(function () {

        $('#lnk-toMakeBackup').click(function (e) { 
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "../controller.php",
                data: {SYS_GET_BACKUP: ''},
                success: function (res) {
                    console.log(res);
                }
            });
        });

        $('.accordion').accordion({
            collapsible: true,
	        heightStyle: 'content',
            animate: {
                duration: 200
            }
        });

        $('#btn-goToHome').click(function (e) { 
            e.preventDefault();
            window.location.replace('home.php');
        });

    });
</script>
</html>