<?php

//define('ROOT_DIR', str_replace('\\', '/', dirname(__FILE__)));

error_reporting(E_ALL | E_STRICT);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include ROOT_DIR . '/core/api_class.php';

define("KEY", "1234567890abcdefg");

class App
 {
    public $API;

    public static function forcedDownloadFile($file){
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');

            readfile($file);
         } else echo "Файл не существует\n";
    }

    public static function sendCommand($ip, $user, $password, $cmd){
        $API = new RouterosAPI();

        if ($API->connect($ip, $user, $password)){

            $API->comm($cmd);
            
            $API->disconnect();

            return true;
        } else return false;
    }

    public static function sendMailAttachment($mailTo, $from, $subject, $message, $file){

        $separator = "===="; // разделитель в письме
        // Заголовки для письма
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "From: $from\nReply-To: $from\n"; // задаем от кого письмо
        $headers .= "Content-Type: multipart/mixed; boundary=\"$separator\""; // в заголовке указываем разделитель

        $bodyMail = "--$separator\n"; // начало тела письма, выводим разделитель
        $bodyMail .= "Content-type: text/html; charset='utf-8'\n"; // кодировка письма
        $bodyMail .= "Content-Transfer-Encoding: quoted-printable"; // задаем конвертацию письма
        $bodyMail .= "Content-Disposition: attachment; filename==?utf-8?B?" . base64_encode(basename($file))."?=\n"; // задаем название файла
        $bodyMail .= $message."\n"; // добавляем текст письма
        $bodyMail .= "--$separator\n";

        $bodyMail .= "Content-Type: application/zip; name==?utf-8?B?" . base64_encode(basename($file))."?=\n"; 
        $bodyMail .= "Content-Transfer-Encoding: base64\n"; // кодировка файла
        $bodyMail .= "Content-Disposition: attachment; filename==?utf-8?B?" . base64_encode(basename($file))."?=\n";
        $bodyMail .= chunk_split(base64_encode(file_get_contents($file)))."\n"; // кодируем и прикрепляем файл
        $bodyMail .= "--" . $separator . "--\n";

        return mail($mailTo, $subject, $bodyMail, $headers);
    }

    public static function isConnect($ip, $username, $password){
        $API = new RouterosAPI();
        return $API->connect($ip, $username, $password);
    }

    public static function routerMakeBackupFile($ip, $user, $password){
        $API = new RouterosAPI();

        if ($API->connect($ip, $user, $password)){

            $API->comm("/system/backup/save", array(
                "name" => "binary",
            ));

            $API->comm("/export", array(
                "file" => "config",
            ));
            
            $API->disconnect();

            return true;
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

    public static function GetBackupFile($id){
        
         include_once "mysql_db_connecting.php";

         $query = ("SELECT `ip_address`, es_decrypt(`user`, UNHEX('F3229A0B371ED2D9441B830D21A390C3')) AS `user`, aes_decrypt(`password`, UNHEX('F3229A0B371ED2D9441B830D21A390C3')) AS `password`, `device_name` FROM `device_user` WHERE `id` = '$id'");
         $result = mysqli_query($link, $query);
         $device = mysqli_fetch_assoc($result);
         $close = mysqli_close($link);

         $ip = $device["ip_address"];
         $user = $device["user"];
         $password = $device["password"]; 
         $device = $device["device_name"];
         
         $mode = 'rsc';
         if (App::routerMakeBackupFile($ip, $user, $password, $device)){
            $time = time();
            $date = date("[d.m.Y]_H-i-s");
   
            $local_file = ROOT_DIR . "/../files/$date--$device--$user." . $mode;
            $server_file = "ftp://$user:$password@$ip/config." . $mode;
   
            if (copy($server_file, $local_file)) {
               if (file_exists($local_file)) {
                   App::forcedDownloadFile($local_file);
                   unlink($local_file);
                } else echo "Не удалось создать файл  \n";
            } else echo "Не удалось подключиться к устройству $device \n";
         }
    }
    
    public static function GetBackupZip($ids){

        $newarray = implode(", ", $ids);
        $done_ids = array();
        
        require "mysql_db_connecting.php";

        $query = ("SELECT `id`, `ip_address`, aes_decrypt(`user`, UNHEX('F3229A0B371ED2D9441B830D21A390C3')) AS `user`, aes_decrypt(`password`, UNHEX('F3229A0B371ED2D9441B830D21A390C3')) AS `password`, `device_name` FROM `device_user` WHERE `id` IN ($newarray) ");
        $result = mysqli_query($link, $query);

        $devices = array();

		while ($row = mysqli_fetch_assoc($result)){
            array_push($devices, $row);
        }

        $close = mysqli_close($link);
        
        $time = time();
        $date = date('d.m.Y', $time);

        $date_for_db = date('Y-m-d', $time); 
        $time_for_db = date('H:i:s', $time);

        $full_date = date("[d.m.Y] H-i-s");

        for ($i = 0; $i < count($devices); $i++) {

            $ip = $devices[$i]["ip_address"];
            $user = $devices[$i]["user"];
            $password = $devices[$i]["password"];
            $hostname = $devices[$i]["device_name"];
            $id_device = $devices[$i]["id"];

            $modes = ["rsc", "backup"];

            if (App::routerMakeBackupFile($ip, $user, $password)){

                foreach ($modes as $index => $value) {

                    $local_dir = ROOT_DIR . "/../files/";
                    $local_file_name = "$date--$hostname--$user." . $value;
                    $local_file_path =  $local_dir . $local_file_name;
                    $server_file = ($value == "backup") ? "ftp://$user:$password@$ip/binary." . $value : "ftp://$user:$password@$ip/config." . $value;
        
                    if (copy($server_file, $local_file_path)) {
                        if (file_exists($local_file_path)) {
                                $zip = new ZipArchive(); 
                                $zipFile =  ROOT_DIR . "/../files/$full_date--backup.zip";
                
                                if($zip->open($zipFile, ZipArchive::CREATE) !== true){
                                    exit('errors');
                                }
                
                                $zip->addFile($local_file_path, "$hostname/$local_file_name");
                                $zip->close();
                                $done_ids[$id_device] = "1";
                        } else {
                            $done_ids[$id_device] = "0";
                        }
                        unlink($local_file_path);
                    } else {
                        $done_ids[$id_device] = "0";
                    } 
                }
                
            } else {
                $done_ids[$id_device] = "0";
            } 
        }
         
        if(file_exists($zipFile)){
            require "mysql_db_connecting.php";
            foreach ($done_ids as $id => $status) {
                $query = ("INSERT INTO `device_backup` (`id_device`, `date`, `time`, `status`) VALUES ('$id', '$date_for_db', '$time_for_db', '$status')");
                $result = mysqli_query($link, $query); 
            }
            $close = mysqli_close($link);
        } else echo "Не удалось создать архив \n";
    }

    public static function GetUserDevices($global_user){
         include_once "mysql_db_connecting.php";

         $query = ("SELECT `id`, `device_name`, aes_decrypt(`user`, UNHEX('F3229A0B371ED2D9441B830D21A390C3')) AS `user`, aes_decrypt(`password`, UNHEX('F3229A0B371ED2D9441B830D21A390C3')) AS `password`, `ip_address`, `comment` FROM `device_user` WHERE `id_global_user` = (SELECT `id` FROM `global_user` WHERE `name` = '$global_user')");
         $result = mysqli_query($link, $query);
         $close = mysqli_close($link);

         $records = array();
		 while($row = mysqli_fetch_assoc($result)){
            array_push($records, $row);
         }

         for ($i = 0; $i < count($records); $i++) { 
             if(App::isConnect($records[$i]["ip_address"], $records[$i]["user"], $records[$i]["password"])){
                $records[$i]["connection"] = 'connected';
             } else $records[$i]["connection"] = 'disconnected';
             unset($records[$i]["password"]);
         }
         return json_encode($records, JSON_UNESCAPED_UNICODE);
    }

    public static function GetUserBackups($filter){
        require "mysql_db_connecting.php";

        $query = ("SELECT `date`, `time`, `status`, `device_name` FROM `device_user`, `device_backup` WHERE `id_device` = `id` ORDER BY '$filter'");
        $result = mysqli_query($link, $query);

        $juornal = array();

		while ($row = mysqli_fetch_assoc($result)){
            array_push($juornal, $row);
        }
        return json_encode($juornal);
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

         $query = ("INSERT INTO `device_user`(`ip_address`, `user`, `password`, `device_name`, `comment`, `id_global_user`) VALUES ('$ip', aes_encrypt('$login', UNHEX('F3229A0B371ED2D9441B830D21A390C3')), aes_encrypt('$pass', UNHEX('F3229A0B371ED2D9441B830D21A390C3')), '$name', '$comment', (SELECT `id` FROM `global_user` WHERE `name` = '$global_user'))");
         
         if ($result = mysqli_query($link, $query)){
            $close = mysqli_close($link);
            return true;
         } else return false;
    }

    public static function GetDeviceById($id){
        include_once "mysql_db_connecting.php";

         $query = ("SELECT `ip_address`, aes_decrypt(`user`, UNHEX('F3229A0B371ED2D9441B830D21A390C3')) AS `user`, aes_decrypt(`password`, UNHEX('F3229A0B371ED2D9441B830D21A390C3')) AS `password`, `device_name` FROM `device_user` WHERE `id` = '$id'");
         $result = mysqli_query($link, $query);
         $close = mysqli_close($link);

         $records = array();
		 while($row = mysqli_fetch_assoc($result)){
            array_push($records, $row);
         }
         return $records;
    }

    public static function RebootAndShutdownDevices($ids, $opt){

        $newarray = (is_array($ids)) ? implode(", ", $ids) : $ids;
        
        
        require "mysql_db_connecting.php";

        $query = ("SELECT `ip_address`, aes_decrypt(`user`, UNHEX('F3229A0B371ED2D9441B830D21A390C3')) AS `user`, aes_decrypt(`password`, UNHEX('F3229A0B371ED2D9441B830D21A390C3')) AS `password` FROM `device_user` WHERE `id` IN ($newarray) ");
        $result = mysqli_query($link, $query);

        $devices = array();

		while ($row = mysqli_fetch_assoc($result)){
            array_push($devices, $row);
        }
        $close = mysqli_close($link);
        for ($i=0; $i < count($devices); $i++) { 
            App::sendCommand($devices[$i]["ip_address"], $devices[$i]["user"], $devices[$i]["password"], $opt);
        }

        if (is_array($ids)){
            echo "Команда выполнена";
        } else header("Location: /php/page/home.php");
    }

 }

?>