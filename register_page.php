<? header("Content-Type: text/html; charset=UTF-8");?>
<?php
require_once 'db_init.php';
session_start();
if (isset($_SESSION['login']))
    $user_in_session = $_SESSION['login'];
else
    $user_in_session = NULL;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>

<head>
    <title> Multikina </title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
    <link rel="stylesheet" href="styles/style.css" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script type="application/javascript" src="js/valid_input.js"></script>


</head>

<body>
<?php
include 'menu.php';
?>


<h2>Registration Form</h2>
<form id="reg_form">

    <label>Uživatelské jméno:</label>
    <input id="login" name="login" type="text">*
    <br>

    <label>Jméno:</label>
    <input id="name" name="name" type="text">*
    <br>

    <label>Příjmení:</label>
    <input id="surname" name="surname" type="text">*
    <br>

    <label>Emailová adresa:</label>
    <input id="email" name="email" type="text">*
    <br>

    <label>Telefon:</label>
    <input id="phone" name="phone" type="text">
    <br>

    <label>Heslo:</label>
    <input id="password" name="password" type="password">*
    <br>

    <label>Potvrdit heslo:</label>
    <input id="password_again" name="password_again" type="password">*
    <br>
    <?php
    require_once 'users.php';
        if (check_usergroup($user_in_session,'admin')) {
            echo "<label> Typ účtu: </label>";
            echo "<input type='radio' name='usergroup' value='admin'>Administrátor<br>";
            echo "<input type='radio' name='usergroup' value='seller'>Obchodník<br>";
            echo "<input type='radio' name='usergroup' value='customer' checked>Zákazník<br>";
        }
    ?>
    *Povinné <input id="submit" name="submit" type="button" value="Registrovat">



    <span id="error_message"></span>
    <span id="succ_message"></span>

</form>



</body>

</html>

<script>
    $(document).ready(function(){
        $('#submit').click(function(){
            var required = ['login', 'name', 'surname', 'email', 'password', 'password_again'];
            $('#error_message').html("");
            set_white(required);
            var all_ok = true;

            // Required fields check
            if (!check_required(required)){
                $('#error_message').html("Vyplňte povinné pole");
                all_ok = false
            }

            // valid username check
            if (all_ok){
                if (!is_valid_login($('#login').val())){
                    all_ok = false;
                    $('#login').css('background-color', '#f47070');
                    $('#error_message').html("Nesprávný formát loginu. Délka: min: 4, max 30 znaků, povolené znaky: a-z,A-Z,0-9,-,_ ");
                }
            }

            // Password uniformity check
            if (all_ok) {
                if ($('#password').val() !== $('#password_again').val()) {
                    all_ok = false;
                    $('#password').css('background-color', '#f47070');
                    $('#password_again').css('background-color', '#f47070');
                    $('#error_message').html("Zadaná hesla se neshodují");
                }
            }

            // Password length check
            if (all_ok){
                if ($('#password').val().length < 6){
                    all_ok = false;
                    $('#password').css('background-color', '#f47070');
                    $('#error_message').html("Heslo musí být dlouhé minimálně 6 znaků");
                }
            }

            // Maximum input characters check (30)
            if (all_ok) {
                var _30_chars = ['login', 'name', 'surname', 'password'];
                if (!check_for_max_30_chars(_30_chars)){
                    all_ok = false;
                    $('#error_message').html("Překročen znakový limit (30)");
                }
            }

            // Email validation
            if (all_ok){
                if ($('#email').val().length > 50){
                    all_ok = false;
                    $('#email').css('background-color', '#f47070');
                    $('#error_message').html("Překročen znakový limit (50)");
                }
                if (!is_valid_email($('#email').val())){
                    all_ok = false;
                    $('#email').css('background-color', '#f47070');
                    $('#error_message').html("Neplatná emailová adresa");
                }
            }

            // phone validation
            if (all_ok){
                if ($('#phone').val() !== "") {
                    if (!is_valid_phone($('#phone').val())) {
                        all_ok = false;
                        $('#phone').css('background-color', '#f47070');
                        $('#error_message').html("Neplatné telefonní číslo");
                    }
                }
            }


            if (all_ok) {
                // Debug
                var form_data = $('#reg_form').serialize()+'&type=register';
                for (var key in form_data){
                    console.log(key+':'+form_data[key]);
                }
                $.ajax({
                    url: "edit_user.php",
                    method: "POST",
                    data: $('#reg_form').serialize()+'&type=register',
                    success: function (data) {
                        $('#succ_message').html(data);
                    },
                    error: function (data) {
                        $('#error_message').html(data);
                    }
                });
            }
        });
    });
</script>