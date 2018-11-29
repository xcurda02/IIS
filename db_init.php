<? header("Content-Type: text/html; charset=UTF-8");?>
<?php

    function get_db(){
        $db = mysqli_init();
        $config = json_decode(file_get_contents("config.json"),true);
        if (!mysqli_real_connect($db, $config['server'] , $config['user'], $config['password'], $config['user'], 0, '/var/run/mysql/mysql.sock')) {
            die('cannot connect '.mysqli_connect_error());
        }
        $db->set_charset('utf8');
        return $db;
    }

?>