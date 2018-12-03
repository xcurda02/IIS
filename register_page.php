<? header("Content-Type: text/html; charset=UTF-8");?>
<?php
require_once 'db_init.php';
session_start();
if (isset($_SESSION['login']))
    $user_in_session = $_SESSION['login'];
else
    $user_in_session = NULL;
/*
 * Stránka registrace
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
        <form id="reg_form">

            <div class="form-group required">
                <label class="control-label" for="login">Uživatelské jméno</label>
                <input id="login" class="form-control" name="login" type="text" placeholder="Uživatelské jméno">

            </div>

            <div class="form-group required">
                <label class="control-label" for="name">Jméno</label>
                <input id="name" class="form-control" name="name" type="text" placeholder="Jméno">
            </div>

            <div class="form-group required">
                <label class="control-label" for="surname">Příjmení</label>
                <input id="surname" class="form-control" name="surname" type="text" placeholder="Příjmení">
            </div>

            <div class="form-group required">
                <label class="control-label" for="email">Emailová adresa</label>
                <input id="email" class="form-control" name="email" type="text" placeholder="Emailová adresa">
            </div>

            <div class="form-group">
                <label class="control-label" for="phone">Telefon</label>
                <input id="phone" class="form-control" name="phone" type="text" placeholder="Telefon">
            </div>

            <div class="form-group required">
                <label class="control-label" for="password">Heslo</label>
                <input id="password" class="form-control" name="password" type="password" placeholder="Heslo">
            </div>

            <div class="form-group required">
                <label class="control-label" for="password_again">Potvrdit heslo</label>
                <input id="password_again" class="form-control" name="password_again" type="password" placeholder="Heslo (znovu)">
            </div>
            <div class="row">
            <?php
            require_once 'users.php';
                if (check_usergroup($user_in_session,'admin')) {
                    echo "<div class=\"col-md-2\">";
                    echo "<label class=\"control-label\"> Typ účtu: </label></div><div class='col-md-6'>";
                    echo "<div class='form-check'><label class='form-check-label'><input type='radio' class='form-check-input' name='usergroup' value='admin'>Administrátor</label></div>";
                    echo "<div class='form-check'><label class='form-check-label'><input type='radio' class='form-check-input' name='usergroup' value='seller'>Prodavač</label></div>";
                    echo "<div class='form-check'><label class='form-check-label'><input type='radio' class='form-check-input' name='usergroup' value='customer' checked>Zákazník</label></div>";
                    echo "</div>";
                }else{
                    echo "<div class='col-md-6'></div>";
                }
            ?>
                <div class="col-md-4">
                    <input id="submit" class='btn btn-primary' name="submit" type="button" value="Registrovat" style="float: right">
                </div>
            </div>




        </form>
        <span id="error_message"></span>
    </div>




</div>
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
                if (!max_char_check(30,_30_chars)){
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
                var form_data = $('#reg_form').serialize();
                $.ajax({
                    url: "register_script.php",
                    method: "POST",
                    data: form_data,
                    success: function (data) {
                        if(data === "EOK") {
                            $("#reg_form").trigger('reset');
                            if (document.getElementsByName("usergroup").length === 0){
                                $('#error_message').html("Účet vytvořen, <a href=\"index.php\">přejít k přihlášení</a>");
                            } else {
                                $('#error_message').html("Účet vytvořen");
                            }
                        }else {
                            $('#error_message').html(data);
                        }

                    },
                    error: function (data) {
                        $('#error_message').html(data);
                    }
                });
            }
        });
    });
</script>