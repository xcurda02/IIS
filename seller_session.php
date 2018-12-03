<?php
include 'session.php';
include "users.php";
if(!check_usergroup($user_in_session,'seller') && !check_usergroup($user_in_session,'admin')){
    header("location: no_access_page.php");
}
?>