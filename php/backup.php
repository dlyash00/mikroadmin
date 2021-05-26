<?php
    define('ROOT_DIR', str_replace('\\', '/', dirname(__FILE__)));
 
    require_once ROOT_DIR.'/core/mysql_db_connecting.php';
    require_once ROOT_DIR.'/core/app.php';

    $query = ("SELECT `id` FROM `device_user`");
    $result = mysqli_query($link, $query);

    $ids = array();

	while ($row = mysqli_fetch_assoc($result)){
        array_push($ids, $row);
    }
    $close = mysqli_close($link);
    
    $ids2 = array();

    foreach ($ids as $key => $value) {
        array_push($ids2, $value["id"]);
    }

    App::GetBackupZip($ids2); 
?>