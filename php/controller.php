<?php
// КОНТРОЛЛЕР ЗАПРОСОВ НА СЕРВЕР

define('ROOT_DIR', str_replace('\\', '/', dirname(__FILE__)));

session_start();
error_reporting(E_ALL | E_STRICT);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include_once ROOT_DIR . "/core/app.php";
	
    if(isset($_POST['SYS_DOWNLOAD_INFO'])){
        print(App::UploadSystemInfo($_SESSION['device'][0]['ip_address'], $_SESSION['device'][0]['user'], $_SESSION['device'][0]['password']));
    }

    if(isset($_POST['SYS_REBOOT_DEVICES'])){
        App::RebootAndShutdownDevices($_POST['SYS_REBOOT_DEVICES'], "/system/reboot");
    }

    if(isset($_POST['SYS_SHUTDOWN_DEVICES'])){
        App::RebootAndShutdownDevices($_POST['SYS_SHUTDOWN_DEVICES'], "/system/shutdown");
    }

    if(isset($_GET['SYS_MAKE_BACKUP'])){
        App::GetBackupZip($_GET['SYS_MAKE_BACKUP']);
    }

    if(isset($_POST['GET_DEVICE_TABLE'])){
        print_r(App::GetUserDevices($_SESSION['name']));
	}

    if(isset($_POST['GET_BACKUP_TABLE'])){
        print_r(App::GetUserBackups($_POST['GET_BACKUP_TABLE']));
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

    if(isset($_GET['select_device'])){
        $_SESSION['device'] = App::GetDeviceById($_GET['select_device']);
        header('Location: page/device.php');
    }

    if(isset($_GET['edit_device'])){
        echo $_GET['edit_device'];
    }

    if(isset($_GET['delete_device'])){
        $id = $_GET['delete_device'];
        App::DeleteUserDevice($id);
    }

    if(isset($_GET['make_backup_id'])){
        $id = $_GET['make_backup_id'];
        App::GetBackupFile($id);
    }

    if(isset($_GET['reboot_id'])){
        $id = $_GET['reboot_id'];
        App::RebootAndShutdownDevices($id, "/system/reboot");
    }


?>