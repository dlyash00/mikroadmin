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
    <link rel = "icon" type = "image/svg" href = "../../img/router-host1.svg"/>
    <title>&nbsp Устройство <?php echo $_SESSION['device'][0]['device_name'] ?></title>
</head>
<body>
<div class = "wrapper">

    <!-- Заголовок -->
    <header>
		<div class = "logo"><a href="home.php"><img src="../../img/mikroadmin_logo.svg" alt="Logo" height = "25px"></a></div>
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
                    <img src="../../img/cpu.svg" height="70px" style="position: relative; top: -86px; left: 185px; mix-blend-mode: soft-light;">
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
                //console.log(getTime(), ': Информация о системе устройства загружена', data);
                
                $('#info').empty();
                let prop_ru = '';
                for (let i = 0; i < data.length; i++) 
                {         
                    $.each(data[i], function (prop, value) { 
                        switch (prop) {

                            case 'architecture-name':
                                prop_ru = 'Архитектура ЦП';
                                break;

                            case 'bad-blocks':
                                prop_ru = 'Испорченных секторов';
                                break;

                            case 'board-name':
                                prop_ru = 'Модель RouterBOARD';
                                break;

                            case 'build-time':
                                prop_ru = 'Дата и время первого запуска';
                                break;

                            case 'cpu':
                                prop_ru = 'Модель ЦП';
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
                                  let bar = document.getElementById('bar-cpu-load');
                                  let end = Number.parseInt(value);
                                  let jitter = 15;
                                  let start = end - jitter;

                                  bar.style.background = '-webkit-linear-gradient(90deg, #97cff5d9 ' + start + '%, #C4C4C4 ' + end + '%)';
                                break;
                            
                            case 'factory-software':
                                prop_ru = 'Заводская версия RouterOS';
                                break;

                            case 'free-hdd-space':
                                prop_ru = 'Свободно памяти HDD';
                                $('#hdd-free').html(parseFloat(value)/(1024*1000) + ' MiB');
                                value = parseFloat(value)/(1024*1000) + ' MiB';
                                break;

                            case 'free-memory':
                                prop_ru = 'Свободно памяти ОЗУ';
                                $('#ram-free').html(parseFloat(value)/(1024*1000) + ' MiB');
                                value = parseFloat(value)/(1024*1000) + ' MiB';
                                break;
                            
                            case 'platform':
                                prop_ru = 'Платформа';
                                break;

                            case 'total-hdd-space':
                                prop_ru = 'Всего памяти HDD';
                                value = parseFloat(value)/(1024*1000) + ' MiB';
                                break;

                            case 'total-memory':
                                prop_ru = 'Всего памяти ОЗУ';
                                value = parseFloat(value)/(1024*1000) + ' MiB';
                                break;

                            case 'uptime':
                                prop_ru = 'Продолжительность работы';
                                break;

                            case 'version':
                                prop_ru = 'Версия RouterOS';
                                break;  

                            case 'write-sect-since-reboot':
                                prop_ru = 'Количество записанных секторов на HDD с момента последней перезагрузки';
                                break; 

                            case 'write-sect-total':
                                prop_ru = 'Общее количество записанных секторов';
                                break; 

                            //-------------------------------      
                            
                            case 'features':
                                prop_ru = 'Особенности';
                                break;
                            
                            case 'nlevel':
                                prop_ru = 'Уровень лицензии';
                                break;
                            
                            case 'software-id':
                                prop_ru = 'Software ID';
                                break;
                            
                            case 'upgradable-to':
                                prop_ru = 'Обновляемо до';
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