<? header("Content-Type: text/html; charset=UTF-8");?>
<?php
require_once 'db_init.php';
include 'session.php';

/*
 * Stránka zobrazující rezervace daného uživatele
 */
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>

<head>
    <title> Multikina </title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <script type="application/javascript" src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <style type="text/css">
        .cancel {
            position: absolute;
            top: 0;
            right: 0;
            font-size: 20px;
            color: black;
            font-family: "sans-serif";
            padding: 10px;
            cursor : pointer;
            z-index: 12;
            pointer-events: all;
        }
        .result_item{
            margin-top: 0;
            margin-bottom: 5px
        }

        .result{
            width: 100%;
            position: relative;
            pointer-events: none;

        }
        .result:hover{
            background-color: #e4e4e4;
        }

    </style>
    <script type="application/javascript">


        function cancel_reservation(ticket_id) {
            $.ajax({
                url: "cancel_reservation.php",
                method: "POST",
                data: "id=" + ticket_id,
                success: function (data) {
                    location.reload();
                },
                error: function (data) {
                    alert("Neočekávaná chyba");
                }
            })
        };

        function view_result(movie, cinema, date, auditorium, seat, price, agegroup, cash_advance, ticket_id){
            var result_div = document.createElement("div");
            result_div.className = "result";
            result_div.id = "id_"+ ticket_id;
            result_div.onclick = function (){
                cancel_reservation(ticket_id);

            };


            var cancel_div = document.createElement("div");
            cancel_div.innerHTML = "Zrušit rezervaci";
            cancel_div.className = "cancel";



            var agegroup_cz;
            switch (agegroup){
                case 'adult': agegroup_cz = "Dospělý";
                    break;
                case 'senior': agegroup_cz = "Důchodce";
                    break;
                case 'child': agegroup_cz = "Dítě";
                    break;
            }
            var c_a = cash_advance === "1" ? "ano" : "ne";

            result_div.appendChild(cancel_div);
            result_div.innerHTML += "<p style='font-size: 25px; font-weight: bold' class='result_item'>" + movie+"</p>\
            <p class='result_item'>Kino:  " + cinema +"</p>\
            <p class='result_item'>Čas:  " + date+"</p>\
            <p class='result_item'>Číslo sálu:  " + auditorium +"</p>\
            <p class='result_item'>Sedadlo:  " + seat + "</p>\
            <p class='result_item'>Věková skupina diváka:  " + agegroup_cz +"</p>\
            <p class='result_item'>Placena záloha:  " + c_a + "</p>\
            <p class='result_item'>Cena:  " + price + " Kč</p>";




            $('#center-box').append(result_div);

        }


    </script>


</head>

<body>
<?php

include 'menu.php';
?>

<div id="wrapper">
    <div id="center-box">
    <?php
        require_once 'users.php';
        require_once 'price.php';
        $db = get_db();
        $user_id = get_user_id($user_in_session);
        $query = $db->query("SELECT p.projection_id AS id,p.date AS timedate, m.name AS movie, c.name AS cinema, a.number AS auditorium,
                                    t.seat AS seat, t.ticket_id AS ticket_id, t.agegroup AS agegroup, t.cash_advance AS cash_advance
                                    FROM cinema c
                                    INNER JOIN auditorium a
                                    INNER JOIN projection p
                                    INNER JOIN movie m
                                    INNER JOIN ticket t
                                    WHERE c.cinema_id = a.fk_cinema
                                    AND a.auditorium_id = p.fk_auditorium
                                    AND p.fk_movie = m.movie_id
                                    AND t.fk_user = '$user_id'
                                    AND t.fk_projection = p.projection_id
                                    AND p.date > NOW()");
        if ($query->num_rows !== 0 ){
            while ($ticket = $query->fetch_assoc()){
                $price = calculate_price($ticket['id'],$ticket['agegroup']);

                echo "<script> view_result(\"{$ticket['movie']}\",\"{$ticket['cinema']}\",\"{$ticket['timedate']}\",\"{$ticket['auditorium']}\",\"{$ticket['seat']}\",\"{$price}\",\"{$ticket['agegroup']}\",\"{$ticket['cash_advance']}\",\"{$ticket['ticket_id']}\")</script>";


            }
        }else{
            echo "<h5>Nemáte zakoupeny žádné vstupenky na budoucí projekce.</h5>";
        }
    ?>
    </div>
</div>


</body>

</html>
<script type="application/javascript">
    $('.result').on('transitionend', function(e){
        $(e.target).remove();
        console.log("removing element");
    });
</script>