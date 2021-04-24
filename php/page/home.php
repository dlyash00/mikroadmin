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

    <div class = "add-edit-device">
        <div class = "btn-close"><svg height="12px" viewBox="0 0 365.71733 365" xmlns="http://www.w3.org/2000/svg"><g fill="#ffffff"><path d="m356.339844 296.347656-286.613282-286.613281c-12.5-12.5-32.765624-12.5-45.246093 0l-15.105469 15.082031c-12.5 12.503906-12.5 32.769532 0 45.25l286.613281 286.613282c12.503907 12.5 32.769531 12.5 45.25 0l15.082031-15.082032c12.523438-12.480468 12.523438-32.75.019532-45.25zm0 0"/><path d="m295.988281 9.734375-286.613281 286.613281c-12.5 12.5-12.5 32.769532 0 45.25l15.082031 15.082032c12.503907 12.5 32.769531 12.5 45.25 0l286.632813-286.59375c12.503906-12.5 12.503906-32.765626 0-45.246094l-15.082032-15.082032c-12.5-12.523437-32.765624-12.523437-45.269531-.023437zm0 0"/></g></svg></div>
        <p class = "text-medium" style = "color: #7AB9E5"><svg height="15px" viewBox="0 0 469.33333 469.33333" xmlns="http://www.w3.org/2000/svg"><path style = "fill: #7AB9E5" d="m437.332031 192h-160v-160c0-17.664062-14.335937-32-32-32h-21.332031c-17.664062 0-32 14.335938-32 32v160h-160c-17.664062 0-32 14.335938-32 32v21.332031c0 17.664063 14.335938 32 32 32h160v160c0 17.664063 14.335938 32 32 32h21.332031c17.664063 0 32-14.335937 32-32v-160h160c17.664063 0 32-14.335937 32-32v-21.332031c0-17.664062-14.335937-32-32-32zm0 0"/></svg>Добавить устройство</p>
        <form>
            <input type="text" id="inp-ip-addr" placeholder = "IP-адрес">
            <input type="text" id="inp-user-name" placeholder = "Логин">
            <input type="password" id="inp-password" placeholder = "Пароль">
            <p></p>
            <input type="text" id="inp-name" placeholder = "Название">
            <input type="text" id="inp-comment" placeholder = "Комментарий">
            <input type="submit" id = "btn-add-device-submit" value="Добавить">
        </form>
    </div>
    <div id="white-block"></div>

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

                    <button>Панель управления</button>
                    <div></div>

                    <button>Действия</button>
                    <div>
                        <div class = "nav-link"><p><img src = "../../img/download.svg" height = "13px">&nbsp <a id = "toMakeZip">Загрузить архив резервных копий</a></p></div>
                        <div class = "nav-link"><p><img src = "../../img/cmd.svg" height = "13px">&nbsp Отправить команду</p></div>
                    </div>

                    <button id = "btn-goToAdmin">Администратор</button>
                    <div></div>
                </div>
            </nav>
        </aside>

        <!-- Контент -->
        <article>

            <p class = "text-medium" id = "todownload">Панель управления</p>
            <div id = "table-device">
                <button id = "btn-add-device"><img src="../../img/add.svg" height = "12px"> Добавить устройство</button>
                <table>
                    <thead>
                        <th>ID</th>
                        <th>Название устройства</th>
                        <th>IP-адрес</th>
                        <th>Комментарий</th>
                        <th>Пользователь</th>
                        <th>Статус</th>
                        <th>Управление</th>
                    </thead>
                    <tbody id = "table-device-content"></tbody>
                </table>
            </div>
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
                window.devices = data;
                $('#table-device-content').empty(); 
                        
                $.each(data, function (prop, value) { 
                    $('#table-device-content').append(
                    `<tr>
                        <td>${value.id}</td>
                        <td>${value.device_name}</td>
                        <td>${value.ip_address}</td>
                        <td>${value.comment}</td>
                        <td><center>${value.user}</center></td>
                        <td><p class = "text-medium-green"><img src="../../img/done.svg" height = "12px">&nbsp Соединено</p></td>
                        <td>
                            <a href = "../controller.php?select_device=${value.id}" title = "Перейти к устройству"><img src="../../img/dashboard.svg" height = "25px"></a>&nbsp
                            <img src="../../img/edit.svg" height = "25px">
                            <a href = "../controller.php?delete_device=${value.id}" title = "Удалить устройство"><img src="../../img/delete.svg" height = "25px"></a>&nbsp
                            <a href = "../controller.php?make_backup_ip=${value.ip_address}&make_backup_user=${value.user}&make_backup_password=${value.password}&make_backup_device=${value.device_name}" title = "Скачать файл конфигурации"><img src="../../img/backup-copy.svg" height = "25px"></a>
                        </td>
                    </tr>`
                    );
                });
            }
        });

        $('.accordion').accordion({
            collapsible: true,
	        heightStyle: 'content',
            animate: {
                duration: 200
            }
        });

        $('#btn-add-device').click(function (e) { 
            e.preventDefault();
            $('#white-block').css('display', 'block');
            $('.add-edit-device').css('display', 'flex');
        });

        $('.btn-close').click(function (e) { 
            e.preventDefault();
            $('#white-block').css('display', 'none');
            $('.add-edit-device').css('display', 'none');
        });

        $('#btn-add-device-submit').click(function (e) { 
            e.preventDefault();

            let data = {};
            data.ip = $('#inp-ip-addr').val();
            data.user = $('#inp-user-name').val();
            data.pass = $('#inp-password').val();
            data.name = $('#inp-name').val();
            data.comment = $('#inp-comment').val();

            $('#inp-ip-addr').val('');
            $('#inp-user-name').val('');
            $('#inp-password').val('');
            $('#inp-name').val('');
            $('#inp-comment').val('');

            $.ajax({
                type: "POST",
                url: "../controller.php",
                data: {ADD_DEVICE: data},
                success: function (res) {
                    alert(res);
                }
            });
        });

        $('#btn-goToAdmin').click(function (e) { 
            e.preventDefault();
            window.location.replace('admin.php');
        });


        $('#toMakeZip').click(function (e) { 
            e.preventDefault();
            $.ajax({
                type: "GET",
                url: "../controller.php",
                data: {SYS_MAKE_BACKUP: window.devices},
                success: function (res) {
                    console.log(res);
                }
            });
        });
    });
    
</script>
</html>