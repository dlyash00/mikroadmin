<?php
// КОНТРОЛЛЕР ЗАПРОСОВ НА СЕРВЕР
session_start();
error_reporting(E_ALL | E_STRICT);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include_once "core/app.php";
	
    if(isset($_POST['SYS_DOWNLOAD_INFO'])){
        print(App::UploadSystemInfo($_SESSION['device'][0]['ip_address'], $_SESSION['device'][0]['user'], $_SESSION['device'][0]['password']));
    }

    if(isset($_GET['SYS_MAKE_BACKUP'])){
        App::GetBackupZip($_GET['SYS_MAKE_BACKUP']);
    }

    if(isset($_POST['GET_DEVICE_TABLE'])){
        print_r(App::GetUserDevices($_SESSION['name']));
	}

    if(isset($_POST['ADD_DEVICE'])){
        $ip = $_POST['ADD_DEVICE']['ip'];
        $user = $_POST['ADD_DEVICE']['user'];
        $pass = $_POST['ADD_DEVICE']['pass'];
        $name = $_POST['ADD_DEVICE']['name'];
        $comment = $_POST['ADD_DEVICE']['comment'];

        if(App::AddDevice($ip, $user, $pass, $name, $comment, $_SESSION['name'])){
            echo "Устройство добавлено";
        } else echo "Устройство не добавлено";
    }

    if(isset($_GET['delete_device'])){
        $id = $_GET['delete_device'];
        App::DeleteUserDevice($id);
    }

    if(isset($_GET['make_backup_ip']) && isset($_GET['make_backup_user']) && isset($_GET['make_backup_password']) && isset($_GET['make_backup_device'])){
        $ip = $_GET['make_backup_ip'];
        $user = $_GET['make_backup_user'];
        $password = $_GET['make_backup_password'];
        $device = $_GET['make_backup_device'];
        App::GetBackupFile($ip, $user, $password, $device);
    }

    if(isset($_GET['select_device'])){
        $_SESSION['device'] = App::GetDeviceById($_GET['select_device']);
        header('Location: page/device.php');
    }

?>