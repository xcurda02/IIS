<?php

    function get_db(){
        $db = mysqli_init();
        $config = json_decode(file_get_contents("config.json"),true);
        if (!mysqli_real_connect($db, $config['server'] , $config['user'], $config['password'], $config['db_name'], $config['port'], $config['socket'])) {
            die('cannot connect '.mysqli_connect_error());
        }
        $db->set_charset('utf8');
        return $db;
    }

?>