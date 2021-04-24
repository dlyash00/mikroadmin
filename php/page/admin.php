<?php
session_start();
error_reporting(E_ALL | E_STRICT);
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>

    function getTime()
    {
        let time = new Date();
        return time.getHours()+':'+time.getMinutes()+':'+time.getSeconds();
    }

    $('document').ready(function () {

        $.ajax({
            type: "POST",
            url: "../controller.php",
            data: {GET_DEVICE_TABLE: ''},
            success: function (res) {
                data = JSON.parse(res);
                console.log(data);

                $('#table-device-content').empty();         
                $.each(data, function (prop, value) { 
                    $('#table-device-content').append(
                    `<tr>
                        <td>${value.id}</td>
                        <td>${value.name}</td>
                        <td>${value.ip_address}</td>
                        <td>${value.comment}</td>
                        <td><center>${value.user}</center></td>
                        <td><p class = "text-medium-green"><img src="../../img/done.svg" height = "12px">&nbsp Соединено</p></td>
                        <td>
                            <img src="../../img/dashboard.svg" height = "25px">&nbsp
                            <img src="../../img/edit.svg" height = "25px">
                            <a href = "../controller.php?delete_device=${value.id}" title = "Удалить устройство"><img src="../../img/delete.svg" height = "25px"></a>&nbsp
                            <a href = "../controller.php?make_backup_ip=${value.ip_address}&make_backup_user=${value.user}&make_backup_password=${value.password}&make_backup_device=${value.name}" title = "Скачать файл конфигурации"><img src="../../img/backup-copy.svg" height = "25px"></a>
                        </td>
                    </tr>`
                    );
                });
            }
        });

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