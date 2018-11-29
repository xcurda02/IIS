<? header("Content-Type: text/html; charset=UTF-8");?>
<?php
/* Getting all data for specific user */
require_once 'db_init.php';
$login = $_POST['login'];


$db = get_db();
$query = $db->query("SELECT * FROM user WHERE login='$login'");
$row = $query->fetch_assoc();
unset($row['password']);
echo json_encode($row);
exit(0);
