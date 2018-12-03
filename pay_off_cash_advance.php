<?php
require_once 'price.php';
require_once 'db_init.php';
include 'seller_session.php';

$ticket_id = $_POST['id'];
$db = get_db();
$query = $db->query("SELECT * FROM ticket WHERE ticket_id='$ticket_id'");             // Getting ticket existence

if ($query->num_rows ==1 ){
    $ticket = $query->fetch_assoc();
    $final_price = calculate_price($ticket['fk_projection'],$ticket['agegroup']);           // Calculating price

    /* Updating ticket */
    $query = $db->query("UPDATE ticket SET price = '$final_price', cash_advance = 0 WHERE ticket_id = '$ticket_id'");
    if ($query == null){
        echo null;
        exit(1);
    }else{
        echo "OK";
        exit(0);
    }
} else{
    echo null;
    exit(1);
}
