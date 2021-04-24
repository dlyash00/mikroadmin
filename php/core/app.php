<?php

error_reporting(E_ALL | E_STRICT);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include "api_class.php";

class App
 {
    public $API;

    public static function isConnect($ip, $username, $password){
        $API = new RouterosAPI();
        return $API->connect($ip, $username, $password);
    }

    public static function routerMakeBackup($ip, $user, $password, $device, $mode){
        $API = new RouterosAPI();

        if ($API->connect($ip, $user, $password)){

            switch ($mode) {
                case 'backup':
                    $API->comm("/system/backup/save", array(
                        "name" => "$device--$user--backup--config",
                    ));
                    $API->disconnect();
                    return true;
                    break;
                
                case 'rsc':
                    $API->comm("/export", array(
                        "file" => "$device--$user--backup--config",
                    ));
                    $API->disconnect();
                    return true;
                    break;
                    
                default:
                    return false;
                    break;
            }
        } else return false;
    }

    public static function UploadSystemInfo($ip, $user, $password){
        $API = new RouterosAPI();

        if ($API->connect($ip, $user, $password)){

            $API->write('/system/resource/print'); // Информация о системных ресурсах;

            $READ = $API->read(false);
            $RES1 = $API->parseResponse($READ);

            $API->write('/system/license/print'); // Информация о лицензии;

            $READ = $API->read(false);
            $RES2 = $API->parseResponse($READ);
                
            $ARRAY = array_merge($RES1, $RES2); // ARRAY - массив вида: ARRAY[0] - инф-я о '/system/resource/print'; ARRAY[1] - инф-я о '/system/license/print';
            $API->disconnect();

            include_once "mysql_db_connecting.php";

            // for ($i = 0; $i < count($ARRAY); $i++) // Записать в БД. Внешний массив: ARRAY[0] - инф-я о системе; ARRAY[1] - инф-я о лицензии. Внутренние массивы - ассоциативные;
            // { 
            //     foreach ($ARRAY[$i] as $prop => $value) {
            //         $query = ("INSERT INTO system (`name`, `value`) values ('$prop','$value')");
            //         $result = mysqli_query($link, $query);
            //     }
            // }

            $close = mysqli_close($link);

            return json_encode($ARRAY);
        } else exit;
    }

    public static function GetBackupFile($ip, $user, $password, $device){
         $mode = 'rsc';
         if (App::routerMakeBackup($ip, $user, $password, $device,  $mode)){
            $time = time();
            $date = date('d-m-Y', $time);
   
            $local_file = "../files/$date--$device--$user." . $mode;
            $server_file = "ftp://$user:$password@$ip/$device--$user--backup--config." . $mode;
   
            if (copy($server_file, $local_file)) {
               if (file_exists($local_file)) {
   
                   header('Content-Description: File Transfer');
                   header('Content-Type: application/octet-stream');
                   header('Content-Disposition: attachment; filename=' . basename($local_file));
                   header('Content-Transfer-Encoding: binary');
                   header('Expires: 0');
                   header('Cache-Control: must-revalidate');
                   header('Pragma: public');
   
                   readfile($local_file);
                   unlink($local_file);
                }
            }
         }
    }
    
    public static function GetBackupZip($devices){
        $mode = 'rsc';
        for ($i = 0; $i < count($devices); $i++) {

            $ip = $devices[$i]["ip_address"];
            $user = $devices[$i]["user"];
            $password = $devices[$i]["password"];
            $hostname = $devices[$i]["device_name"];

            if (App::routerMakeBackup($ip, $user, $password, $hostname, $mode)){
                $time = time();
                $date = date('d-m-Y', $time);
       
                $local_file = "../files/$date--$hostname--$user." . $mode;
                $server_file = "ftp://$user:$password@$ip/$hostname--$user--backup--config." . $mode;
       
                if (copy($server_file, $local_file)) {
                   if (file_exists($local_file)) {
                    $time = time();
                    $date = date('d-m-Y', $time);
    
                    $zip = new ZipArchive(); 
                    $zipFile = "../files/$date--backup.zip";
    
                    if($zip->open($zipFile, ZipArchive::CREATE) !== true){
                        exit('errors');
                    }
    
                    $zip->addFile($local_file);
                    $zip->close();
                    }

                    unlink($local_file);
                }
            }
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($zipFile));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
    
        readfile($zipFile);
        //unlink($zipFile);
    }

    public static function GetUserDevices($global_user){
         include_once "mysql_db_connecting.php";

         $query = ("SELECT `id`, `device_name`, `password` , `user`, `ip_address`, `comment` FROM `device_user` WHERE `id_global_user` = (SELECT `id` FROM `global_user` WHERE `name` = '$global_user')");
         $result = mysqli_query($link, $query);
         $close = mysqli_close($link);

         $records = array();
		 while($row = mysqli_fetch_assoc($result)){
            array_push($records, $row);
         }
         return json_encode($records, JSON_UNESCAPED_UNICODE);
    }

    public static function DeleteUserDevice($id){
         include_once "mysql_db_connecting.php";

         $query = ("DELETE FROM `device_user` WHERE `id` = '$id'");
         $result = mysqli_query($link, $query);
         $close = mysqli_close($link);

         return header('Location: page/home.php');
    }

    public static function AddDevice($ip, $login, $pass, $name, $comment, $global_user){
        include_once "mysql_db_connecting.php";

         $query = ("INSERT INTO `device_user`(`ip_address`, `user`, `password`, `device_name`, `comment`, `id_global_user`) VALUES ('$ip', '$login', '$pass', '$name', '$comment', (SELECT `id` FROM `global_user` WHERE `name` = '$global_user'))");
         
         if ($result = mysqli_query($link, $query)){
            $close = mysqli_close($link);
            return true;
         } else return false;
    }

    public static function GetDeviceById($id){
        include_once "mysql_db_connecting.php";

         $query = ("SELECT `ip_address`, `user`, `password`, `device_name` FROM `device_user` WHERE `id` = '$id'");
         $result = mysqli_query($link, $query);
         $close = mysqli_close($link);

         $records = array();
		 while($row = mysqli_fetch_assoc($result)){
            array_push($records, $row);
         }
         return $records;
    }

 }

?>