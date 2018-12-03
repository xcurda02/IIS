<?php
include 'session.php';
require_once 'db_init.php';
require_once 'db_protect.php';
require_once 'users.php';


/* Checks if seat is already reserved */
function seat_taken($seat,$p_id){
    $db = get_db();

    $query = $db->query("SELECT * FROM ticket INNER JOIN projection 
                                WHERE ticket.fk_projection = '$p_id' AND ticket.seat = '$seat' AND ticket.fk_projection = projection.projection_id");
    $db->close();
    if($query->num_rows == 0){
        return false;
    }else{
        return true;
    }

}



$count = $_POST['count'];                       // Requested seats to reserve count
$projection_id = protect_string($_POST['id']);

/* Checking for taken seats */
$seats_taken = array();
for ($i = 0; $i < $count; $i++) {
    $seat_number = "seat_number_" . $i;
    if (seat_taken($_POST[$seat_number], $projection_id)) {
        $seats_taken[] = $i;
    }
}

/* When no seat is taken, script goes into error reporting mode, sending
    a response with indexes representing taken seats */
if (!empty($seats_taken)){
    echo "{ \"errors\":".json_encode($seats_taken)."}";
    exit(0);
} else{
    if (isset($_POST['payment_type'])){
        $payment_type = $_POST['payment_type'];
    }else{
        $payment_type = "pay_all";
    }

    $total_price = 0;
    /* Creating new tickets */
    for ($i = 0; $i < $count; $i++) {
        $age_group = $_POST["age_group_".$i];
        $seat_number = $_POST["seat_number_".$i];
        $db = get_db();

        // If user pays all, generating price (see price.php function calculate_price())
        // else if user decided to pay cash advance, the price is always 50 and then they pay the remainder of the price at the cinema
        if ($payment_type == "pay_all"){
            require_once 'price.php';
            $final_price = calculate_price($projection_id,$age_group);
            if ($final_price == null){
                echo "unexpected error";
                exit(1);
            }
            $cash_advance = 0;
        } else{                             // User paid cash advance
            $final_price = 50;
            $cash_advance = 1;
        }
        $total_price += $final_price;

        /* Creating new ticket */
        if (check_usergroup($user_in_session,'seller'))
            $user_id = 'NULL';
        else{
            $user_id = "'".get_user_id($user_in_session)."'";
        }

        $query = $db->query("INSERT INTO ticket (seat, price,agegroup, cash_advance, fk_user, fk_projection) VALUES ('$seat_number','$final_price','$age_group','$cash_advance',$user_id,'$projection_id')");
        if ($query == null){
            echo $db->error;
            exit(1);
        }

    }
    $seats_taken[] = null;
    echo "{ \"errors\":".json_encode($seats_taken).",\"price\": ".$total_price."}";         // sending null in errors - all went fine
    exit(0);
}
