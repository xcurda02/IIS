<? header("Content-Type: text/html; charset=UTF-8");?>
<?php
    require_once 'db_init.php';
    session_start();
    $user_in_session = null;
    if (isset($_SESSION['login'])) {
        $user_in_session = $_SESSION['login'];
        $db = get_db();
        $query = $db->query("select login from user where login ='$user_in_session'");
        if ($query->num_rows == 0)
            header("location: index.php");

    }else
        header("location: index.php");

