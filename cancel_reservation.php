<?php
include 'session.php';
require_once 'db_init.php';

$ticket_id = $_POST['id'];
$db = get_db();
$db->query("DELETE FROM `ticket` WHERE ticket_id = '$ticket_id'");
$db->close();
exit(0);
