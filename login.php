<?php
    session_start();
    require_once 'db_protect.php';
    $db = get_db();

    $login = protect_string($_POST['login']);
    $password = protect_string($_POST['password']);

    /* Compare credentials with DB */
    $query = $db->query("select * from `user` where login = '$login' and password = '$password'");
    if ($query->num_rows == 1){
        $_SESSION['login'] = $_POST['login'];
        echo "E_OK";
    } else{
        echo "E_FAIL";
    }
