<?php
require_once 'db_init.php';

/* SQL injection prevention */
function protect_string($str){
    $db = get_db();
    $str = stripslashes($str);
    $str = $db->real_escape_string($str);
    $db->close();
    return $str;
}