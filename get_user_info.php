<?php
/* Getting all data for specific user */
require_once 'db_init.php';
$login = $_POST['login'];


$db = get_db();
$query = $db->query("SELECT * FROM user WHERE login='$login'");
$ticket = $query->fetch_assoc();
unset($ticket['password']);
echo json_encode($ticket);
exit(0);
