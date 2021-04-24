<?php
session_start();
error_reporting(E_ALL | E_STRICT);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="../../css/stylesheet.css"> 
    <title>Устройство</title>
</head>
<body>
<div class = "wrapper">

    <!-- Заголовок -->
    <header>
		<div class = "logo"><a href="home.php"><img src="../../img/MikroTik_logo.svg" alt="Logo" height = "35px"></a></div>
		<div class = "title">
            <p style = "font-size: 1.2em; font-weight: 200; line-height: 0.4em; margin-bottom: 5px"><img src="../../img/user1.svg" height = "20px">&nbsp&nbspПользователь&nbsp <?php echo $_SESSION['device'][0]['user'] ?> <b></b></p>
            <p style = "font-weight: 200; line-height: 0.4em; margin-top: 5px"><img src="../../img/router-host1.svg" height = "25px">&nbsp&nbspУстройство <b><?php echo $_SESSION['device'][0]['device_name'] ?></b> на <b><?php echo $_SESSION['device'][0]['ip_address'] ?></b></p>
        </div>
	</header>

    <!-- Основная часть -->
	<main>

        <!-- Сайд-бар -->
        <aside>
            <p style = "font-weight: 100; font-size: 0.85em; line-height: 1em;">Действия</p>

            <!-- Меню навигации -->
            <nav>
                <button><img src="../../img/system.svg" height = "20px">Система</button>
                <button><img src="../../img/port.svg" height = "15px">Интерфейсы</button>
                <button><img src="../../img/routing.svg" height = "20px">Маршрутизация</button>
                <p></p>
                <button><img src="../../img/visualize.svg" height = "20px">Визуализация</button>
                <button><img src="../../img/graph.svg" height = "15px">Мониторинг</button>
                <button><img src="../../img/detection.svg" height = "20px">Угрозы</button>
            </nav>
        </aside>

        <!-- Контент -->
        <article>

            <p>Основное</p>

            <div class = "palette-bar">
                <div class = "bar" id = "bar-cpu-load">
                    <p><b id = "cpu-load"></b></p>
                    <p>&nbsp</p>
                    <p>Загрузка ЦП</p>
                    <p id = "cpu-name"></p>
                </div>

                <div class = "bar" id = "bar-cpu-freq">
                    <p><b id = "cpu-frequency"></b></p>
                    <p>&nbsp</p>
                    <p>Частота ЦП</p>
                    <p id = "cpu-name"></p>
                </div>

                <div class = "bar" id = "bar-cpu-cores">
                    <p><b id = "cpu-cores"></b></p>
                    <p>&nbsp</p>
                    <p>Активно ядер ЦП</p>
                    <p id = "cpu-name"></p>
                </div>

                <div class = "bar" id = "bar-ram">
                    <p><b id = "ram-free"></b></p>
                    <p>&nbsp</p>
                    <p>Свободно памяти</p>
                    <p>ОЗУ</p>
                </div>

                <div class = "bar" id = "bar-hdd">
                    <p><b id = "hdd-free"></b></p>
                    <p>&nbsp</p>
                    <p>Свободно памяти</p>
                    <p>HDD</p>
                </div>
            </div>

            <div id = "info">
            </div>

        </article>

    </main>

</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>

    function getTime()
    {
        let time = new Date();
        return time.getHours()+':'+time.getMinutes()+':'+time.getSeconds();
    }

    $(document).ready(function () {

        setInterval(() => {
            $.ajax({ // Загрузить информацию о системе устройства
            type: "POST",
            url: "../controller.php",
            data: {SYS_DOWNLOAD_INFO: ''},
            success: function (res){
                data = JSON.parse(res);
                console.log(getTime(), ': Информация о системе устройства загружена', data);
                
                $('#info').empty();
                let prop_ru = '';
                for (let i = 0; i < data.length; i++) 
                {         
                    $.each(data[i], function (prop, value) { 
                        switch (prop) {
                            case 'uptime':
                                prop_ru = 'Время работы';
                                break;

                            case 'version':
                                prop_ru = 'Версия RouterOS';
                                break;
                            
                            case 'free-memory':
                                prop_ru = 'Свободно памяти ОЗУ';
                                $('#ram-free').html(Math.round(parseInt(value)/1024) + ' MiB');
                                break;

                            case 'total-memory':
                                prop_ru = 'Всего памяти ОЗУ';
                                break;

                            case 'cpu':
                                prop_ru = 'ЦП';
                                $('#cpu-name').html(value);
                                break;

                            case 'cpu-count':
                                prop_ru = 'Активно ядер ЦП';
                                $('#cpu-cores').html(value);
                                break;
                            
                            case 'cpu-frequency':
                                prop_ru = 'Частота ЦП';
                                $('#cpu-frequency').html(value + ' МГц');
                                break;
                            
                            case 'cpu-load':
                                prop_ru = 'Загрузка ЦП';
                                $('#cpu-load').html(value + '%');
                                //  var bar = document.getElementById('bar-cpu-load');
                                //  var val2 = val + (100 - (100 - val));
                                //  bar.style.background = '-webkit-linear-gradient(bottom, #EEDED5 ' + val + '%, #C4C4C4 ' + val2 + '%)';
                                break;
                            
                            case 'free-hdd-space':
                                prop_ru = 'Свободно памяти HDD';
                                $('#hdd-free').html(Math.round(parseInt(value)/1024) + ' MiB');
                                break;
                            
                            case 'total-hdd-space':
                                prop_ru = 'Всего памяти HDD';
                                break;
                            
                            case 'architecture-name':
                                prop_ru = 'Архитектура';
                                break;
                            
                            case 'software-id':
                                prop_ru = 'Software ID';
                                break;
                            
                            case 'nlevel':
                                prop_ru = 'Уровень лицензии';
                                break;
                            
                            case 'upgradable-to':
                                prop_ru = 'Обновить до';
                                break;

                            case 'features':
                                prop_ru = 'Особенности';
                                break;

                            default:
                                break;
                        }
                        $('#info').append(prop_ru, ': ', value, '<br>');
                    });
                }

            }
        });
        }, 1000);
        
    });
</script>
</html>