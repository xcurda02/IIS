<? header("Content-Type: text/html; charset=UTF-8");?>
<?php
    include 'session.php';
    include "users.php";
    if(!check_usergroup($user_in_session,'admin')){
        header("location: no_access_page.php");
    }
?>
