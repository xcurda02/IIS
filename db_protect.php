<? header("Content-Type: text/html; charset=UTF-8");?>
<?php
require_once 'db_init.php';
function protect_string($str){
    $db = get_db();
    $str = stripslashes($str);
    $str = $db->real_escape_string($str);
    $db->close();
    return $str;
}