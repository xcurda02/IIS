<? header("Content-Type: text/html; charset=UTF-8");?>
<?php
require_once 'db_init.php';
include 'admin_session.php';
/*
 * Stránka umožňuje administrátorovi vložení filmu do databáze
 * */
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>

<head>
    <title> Multikina </title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <script type="application/javascript" src="js/jquery-3.3.1.min.js"></script>
    <script type="application/javascript" src="js/valid_input.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">




</head>

<body>
<?php
include 'menu.php';
?>
<div id="wrapper">
    <div id="center-box">

        <h2>Přidání filmu do databáze</h2>
        <form id="movie_form" onkeypress="return event.keyCode !== 13;">

            <div class="form-group required">
                <label class="control-label" for="movie">Název filmu:</label>
                <input class="form-control" name="movie" id="movie" type="text">
            </div>


            <div class="form-group required">
                <label class="control-label" for="director">Režisér:</label>
                <input class="form-control" id="director" name="director" type="text">
            </div>

            <div class="form-group required">
                <label class="control-label" for="release_date">Rok vydání filmu:</label>
                <input class="form-control" name="release_date" id="release_date" type="text">
            </div>

            <div class="form-group required">
                <label class="control-label" for="genre">Žánr:</label>
                <input class="form-control" id="genre" name="genre" type="text">
            </div>

            <div class="form-group required">
                <label class="control-label" for="price">Základní cena:</label>
                <input class="form-control" id="price" name="price" type="text">
            </div>

            <div class="row">

                <div class="col-md-6">
                    <input  class='btn btn-primary' type="button" name="submit" id="submit" value="Přidat film do databáze">
                </div>
                <div class="col-md-6">
                    <span id="error_message"></span>
                </div>

            </div>



        </form>

    </div>


</div>
</body>

</html>

<script>


    $(document).ready(function(){
        $('#submit').click(function(){

            var required = ['movie','director','release_date','genre','price'];
            $('#error_message').html("");
            set_white(required);
            var all_ok = true;

            // Required fields check
            if (!check_required(required)){
                $('#error_message').html("Vyplňte povinné pole");
                all_ok = false
            }

            // Maximum input characters check (60)
            if (all_ok) {
                var _60_chars = ['movie','director'];
                if (!max_char_check(60,_60_chars)){
                    all_ok = false;
                    $('#error_message').html("Překročen znakový limit (60)");
                }
            }

            // Maximum input characters check (30)
            if (all_ok){
                if (!max_char_check(30,['genre'])) {
                    all_ok = false;
                    $('#error_message').html("Překročen znakový limit (30)");
                }
            }

            // Release date input check
            if (all_ok){
                if (isNaN($('#release_date').val())){
                    all_ok = false;
                    $('#error_message').html("Zadejte validní rok vydání");
                    $('#release_date').css('background-color', '#f47070');
                }
            }

            // Price input check
            if (all_ok){
                if (isNaN($('#price').val())){
                    all_ok = false;
                    $('#error_message').html("Zadejte validní cenu");
                    $('#price').css('background-color', '#f47070');
                }

            }

            /* Sending form data */
            if (all_ok) {
                var form_data = $('#movie_form').serialize();
                $.ajax({
                    url: "create_new_movie.php",
                    method: "POST",
                    data: form_data,
                    success: function (data) {
                        $('#error_message').html("Film přidán do databáze");
                        $('#movie_form').trigger("reset");
                    },
                    error: function (data) {
                        $('#error_message').html("ERROR");
                    }
                });
            }
        });
    });
</script>