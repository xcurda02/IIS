<?php
include 'admin_session.php';
require_once 'db_init.php';
require_once 'db_protect.php';

$movie = protect_string($_POST['movie']);
$director = protect_string($_POST['director']);
$genre = protect_string($_POST['genre']);

$release_date = $_POST['release_date'];
$price = $_POST['price'];

$db = get_db();
$query = $db->query("INSERT INTO movie (name,director,release_date,genre,price) VALUES ('$movie','$director','$release_date','$genre','$price')");
if ($query == null){
    echo null;
    exit(1);
}else{
    echo "EOK";
    exit(0);
}