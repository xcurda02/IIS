<?php
    include 'config.php';
    $db = mysqli_init();
    if (!mysqli_real_connect($db, $server , $user, $password, $user, 0, '/var/run/mysql/mysql.sock')) {
        die('cannot connect '.mysqli_connecterror());
    }
    $db->set_charset('utf8')

?>