<?php
include 'admin_session.php';//
$login = $_POST['login'];
$query = $db->query("select * from `user` where login ='$login'");
if ($query->num_rows == 1){
    $row = $query->fetch_assoc();

    /* Removing tickets bound to this account */
    $user_id = $row['user_id'];
    $tickets = $db->query("select * from `ticket` where fk_user ='$user_id'");
    if($tickets->num_rows > 0){
        while($ticket = $tickets->fetch_assoc()){
            $ticket_id = $ticket['ticket_id'];
            $db->query("DELETE FROM `ticket` WHERE ticket_id = '$ticket_id'");
        }
    }
    /* Removing account*/
    $db->query("DELETE FROM `user` WHERE user_id = '$user_id'");
    echo "OK";
    exit(0);
}else{
    echo "ERROR";
    exit(1);
}
