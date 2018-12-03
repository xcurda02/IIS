<? header("Content-Type: text/html; charset=UTF-8");?>
<?php
require_once 'db_init.php';
include 'session.php';
/*
 * Stránka jednotlivé projekce umožňující uživateli vytvožit rezervaci
 * nebo prodavačovi prodat vstupenku
 * */
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
        #payment_type td{
            padding-left: 30px;
        }
        form {
            max-width: 990px;
            margin-right: 10px;
        }
    </style>


</head>

<body>
<?php

include 'menu.php';
?>

<div id="wrapper">
<?php
require_once 'db_protect.php';

function no_projection(){
    echo "<h1>Projekce neexistuje</h1>";
}

/* Getting projection info */
if (isset($_GET['id'])) {
    $id = protect_string($_GET['id']);
    $db = get_db();
    $query = $db->query("SELECT  p.date AS timedate,
                                    c.name AS cinema, c.address AS address,c.phone AS phone, c.web AS web,
                                    a.number AS auditorium, a.capacity AS capacity,
                                    m.genre AS genre, m.director AS director,  m.name AS movie, m.price AS price, m.release_date as release_date
                                    FROM cinema c
                                    INNER JOIN auditorium a
                                    INNER JOIN projection p
                                    INNER JOIN movie m
                                    WHERE c.cinema_id = a.fk_cinema
                                    AND a.auditorium_id = p.fk_auditorium
                                    AND p.fk_movie = m.movie_id
                                    AND p.projection_id = '$id'
                                    AND p.date > NOW()");
}else{
    header("location: index.php");
    exit(1);
}
    /* Printing projection info */
if($query->num_rows == 1) {
    $data = $query->fetch_assoc();
    echo "<div id='projection_info'>";
    echo "<div class='row'>";
    echo "<div class='col-md-6' >";
    echo "<h1>" . $data['movie'] . "</h1><br>";
    echo "(" . $data['timedate'] . ")<br>";
    echo "Režie: " . $data['director'] . "<br>";
    echo "Žánr: " . $data['genre'] . "<br>";
    echo "Datum vydání: " . $data['release_date'] . "<br>";
    echo "Základní cena: " . $data['price'] . " Kč<br><br>";
    echo "</div>";

    echo "<div class='col-md-4'>";
    echo "<br><h3>" . $data['cinema'] . "</h3><br>";
    echo "Adresa: " . $data['address'] . "<br>";
    echo "Telefon: " . $data['phone'] . "<br>";
    echo "Web: " . $data['web'] . "<br><br>";
    echo "</div>";
    echo "</div>";

    echo "<div class='row'>";
    echo "<div class='col-md-2' >";
    echo "Sál: " . $data['auditorium'] . "</div>";
    echo "<div class='col-md-2' > Kapacita: <span id='capacity'>" . $data['capacity'] . "</span></div>";
    $tickets_sold = $db->query("SELECT * FROM ticket t INNER JOIN projection p WHERE  t.fk_projection = '$id'
                                    AND p.projection_id = '$id'");
    $empty_seats = intval($data['capacity']) - $tickets_sold->num_rows;
    echo "<div class='col-md-2' >Počet volných míst: " . $empty_seats . "</div>";
    echo "</div>";

    /* If there is less than 60 empty seats left, list of empty seat numbers will be shown */
    if ($empty_seats < 60) {
        $all_seats = array();
        for ($i = 1; $i <= $data['capacity']; $i++) {
            $all_seats[] = $i;
        }

        $reserved_seats = array();
        while ($ticket = $tickets_sold->fetch_assoc()) {
            $reserved_seats[] = $ticket['seat'];
        }
        $empty_seat_arr = array_diff($all_seats, $reserved_seats);
        echo "Volná sedadla: ";
        foreach ($empty_seat_arr as $seat) {
            echo "$seat, ";
        }

    }

    echo "</div>";
}
?>
    <h3>Rezervace míst:</h3>
    <div class="row">
        <div class="col-md-10">
            <form id='reservation_form' class='form-group' onkeypress="return event.keyCode !== 13;">


                <div class='row'>


                        <table style="padding: 20px; margin: 0px 0px 15px 15px"><tr><td>
                            <input class='form-control' id='add_seat' name='add_seat' type='button' value='+'>
                        </td></tr><tr><td>
                            <input class='form-control' id='del_seat' name='del_seat' type='button' value='-' disabled>
                        </td></tr>
                        </table>




                    <div class='col-md-4' style="padding-left: 0px">
                        <table id='reservation_table'>
                            <tr>
                            <td>
                                <select class='form-control' name='age_group_0'>
                                    <option value='adult'>Dospělý</option>
                                    <option value='child'>Dítě</option>
                                    <option value='senior'>Důchodce</option>
                                </select>
                            </td>
                            <td>
                                <input class='form-control' name='seat_number_0' type='text' placeholder="číslo sedadla">
                            <td>
                            </tr>
                        </table>

                    </div>
                    <div class='col-md-2'>
                        <?php
                        require_once 'users.php';
                        if (check_usergroup($user_in_session,'seller')){
                            $button_title = "Prodat vstupenku";
                        } else{
                            $button_title = "Zarezervovat";
                        }
                        ?>
                        <input class='btn btn-primary' id='submit' name='submit' type='button' value=<?php echo "\"{$button_title}\"";?>>
                    </div>
                    <div class='col-md-5'>
                        <?php
                            require_once 'users.php';
                            if (!check_usergroup($user_in_session,'seller')) {
                                echo "<table id='payment_type'><tr><td>";
                                echo "Typ účtu:</td><td style='width: 200px'>";
                                echo "<label class=\"form-check-label\" >
                                        <input type=\"radio\" class=\"form-check-input\" name=\"payment_type\" value='cash_advance'> Zaplatit zálohu
                                        </label></td></tr><tr><td></td><td>";

                                echo "<label class=\"form-check-label\" >
                                        <input type=\"radio\" class=\"form-check-input\" name=\"payment_type\" value='pay_all' checked> Zaplatit vše
                                        </label></td></tr></table>";
                            }
                        ?>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-2">
            <span id='error_message'></span>
        </div>
    </div>

</div>


</body>

</html>

<script>
    function agegroup_option(age_group){
        var option = document.createElement("option");
        option.value = age_group;
        switch (age_group) {
            case 'adult':
                option.text = 'Dospělý';
                break;
            case 'child':
                option.text = 'Dítě';
                break;
            case 'senior':
                option.text = 'Důchodce';
                break;
        }
        return option;
    }

    $(document).ready(function () {
        $('#add_seat').click(function () {         // Adding new table row with select and input
            $('#del_seat').prop('disabled', false);



            var select = document.createElement("select");
            var rows = document.getElementById("reservation_table").rows.length;
            select.name = "age_group_" + rows;
            select.className = "form-control";

            select.appendChild(agegroup_option('adult'));
            select.appendChild(agegroup_option('child'));
            select.appendChild(agegroup_option('senior'));

            var td_select = document.createElement("td");
            td_select.appendChild(select);

            var input = document.createElement("input");
            input.className = "form-control";
            input.name = "seat_number_"+rows;
            input.type = "text";
            input.placeholder = "číslo sedadla";

            var td_input = document.createElement("td");
            td_input.appendChild(input);

            var new_row = document.createElement("tr");
            new_row.appendChild(td_select);
            new_row.appendChild(td_input);

            $('#reservation_table tr:last').after(new_row);

        });
        $('#del_seat').click(function () {         // Adding new table row with select and input
            $('#reservation_table tr:last').remove();
            if (document.getElementById("reservation_table").rows.length === 1)
                $('#del_seat').prop('disabled', true);
        });

        /* Checks if values in all inputs are unique */
        function get_non_unique_vals(){
            //var inputs = document.getElementsByClassName("seat_number");
            var inputs = document.querySelectorAll("#reservation_table input[type=text]");

            var non_unique = [];
            for(i = 0; i < inputs.length; i++){
                for (j = i+1; j < inputs.length;j++){
                    if(inputs[i].value === inputs[j].value){
                        non_unique.push(i);
                        non_unique.push(j);
                    }
                }
            }
            return non_unique;
        }

        $('#submit').click(function () {         // Adding new table row with select and input
            /* Resetting input color and error message */
            $('#error_message').html("");

            var status = true;

            /* Traversing through inputs, looking for invalid values*/
            //var inputs = document.getElementsByClassName("seat_number");
            var inputs = document.querySelectorAll("#reservation_table input[type=text]");


            for(i = 0; i < inputs.length; i++){
                var input = inputs[i];      // getting second cell in row (input)
                input.style.backgroundColor = '#ffffff';
                if (input.value === "" || isNaN(input.value) || parseInt(input.value) > parseInt($('#capacity').text()) || parseInt(input.value) < 1){
                    $('#error_message').html("Zadejte validní číslo sedadla (rozsah 1-"+$('#capacity').text()+")");
                    input.style.backgroundColor = '#f47070';
                    status = false;
                }
            }

            if (status === true){
                var non_unique = get_non_unique_vals();
                if(non_unique.length > 0){
                    status = false;
                    for (var i = 0; i < non_unique.length; i++){
                        inputs[non_unique[i]].style.backgroundColor = '#f47070';
                        $('#error_message').html("Požadovaná rezervace obsahuje několik stejných sedadel, opravte prosím svoji rezervaci.");
                    }
                }

            }

            if (status === true) {

                var url = new URL(window.location.href);
                var id = url.searchParams.get("id");
                $.ajax({
                    url: "reserve_seats.php",
                    method: "POST",
                    dataType: "json",
                    data: "id="+id+"&count="+inputs.length + "&" + $('#reservation_form').serialize(),
                    success: function (data) {
                        if (data['errors'][0] == null) {
                            $('#reservation_form')[0].reset();
                            $('#error_message').html("Rezervace vytvořena, k zaplacení: "+data['price']+" Kč");
                        } else {
                            var errors = data['errors'];
                            for (var i = 0; i < errors.length; i++){
                                var input_name = "seat_number_"+errors[i];
                                document.getElementsByName(input_name)[0].style.backgroundColor = '#f47070';
                            }

                            $('#error_message').html("Označená pole obsahují již zarezervovaná sedadla," +
                                "vyplňte znovu s jinými čísly sedadel.");

                        }
                    },
                    error: function (data) {
                        $('#error_message').html("Neočekávaná chyba");
                    }
                });
            }


        });

    });

</script>