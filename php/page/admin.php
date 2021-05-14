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
    <title>Администратор</title>
</head>
<body>
<div class = "wrapper">

    <!-- Заголовок -->
    <header>
		<div class = "logo"><a href="#"><img src="../../img/MikroTik_logo.svg" alt="Logo" height = "35px"></a></div>
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
                <div class = "accordion">

                    <button id = "btn-goToHome">Панель управления</button>
                    <div></div>

                    <button>Действия</button>
                    <div>
                        <div class = "nav-link" id = 'lnk-toMakeBackup'><p><img src = "../../img/download.svg" height = "13px">&nbsp Загрузить резервную копию</p></div>
                        <div class = "nav-link"><p><img src = "../../img/cmd.svg" height = "13px">&nbsp Отправить команду</p></div>
                    </div>

                    <button>Администратор</button>
                    <div></div>
                </div>
            </nav>
        </aside>

        <!-- Контент -->
        <article>
            <p></p>
            <p class = "text-medium">Изменить пароль администратора</p>
            <input type="password" name = "user-password" id = "inp-user-password" placeholder = "Новый пароль"/>
            <input type="password" name = "user-password" id = "inp-user-password" placeholder = "Подтвердите новый пароль"/>
            <input type="submit" value="Подтвердить">

            <p></p>
            <p class = "text-medium">Почта</p>
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