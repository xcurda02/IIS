<?php
include 'admin_session.php';
require_once 'db_init.php';
require_once 'db_protect.php';

$db = get_db();

/* Checking auditorium number existence in given cinema */
$auditorium_number = protect_string($_POST['auditorium']);
$cinema = protect_string($_POST['cinema']);
$query = $db->query("SELECT * FROM auditorium INNER JOIN cinema ON cinema.cinema_id = auditorium.fk_cinema
                            WHERE cinema.cinema_id = '$cinema'
                            AND auditorium.number = '$auditorium_number'");

/* If auditorium exists, creating new projection */
if ($query->num_rows == 1){
    $row = $query->fetch_assoc();
    $auditorium_id = $row['auditorium_id'];
    $datetime = $_POST['date']." ".$_POST['time'];
    $movie = $_POST['movie'];
    $query = $db->query("INSERT INTO projection (date, income, fk_auditorium, fk_movie) VALUES('$datetime',0,'$auditorium_id','$movie')");
    if ($query != null){
        echo "E_OK";
        exit(0);
    } else{
        echo null;
        exit(1);
    }

} else{
    echo "Zadaný sál v daném kině neexistuje";
    exit(1);
}