<? header("Content-Type: text/html; charset=UTF-8");?>
<?php
require_once 'db_init.php';
require_once 'users.php';
include 'session.php';
if (check_usergroup($user_in_session,'admin')){
    $user_to_edit = null;
} else{
    $user_to_edit = $user_in_session;
}
/*
 * Stránka umožňující editace účtu
 */
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>

<head>
    <title> Multikina </title>
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
        <h2>Editace uživatelských údajů</h2>
        <form id="edit_form">

            <?php
                require_once 'users.php';
                require_once 'edit_forms.php';
                if (check_usergroup($user_in_session,'admin'))
                    generate_admin_edit_form();
                else
                    generate_non_admin_edit_form($user_in_session);
            ?>

        </form>
        <span id="error_message"></span>
    </div>
</div>
</body>

</html>

<script>
    function clear_input_fields(){
        $('#edit_form')[0].reset();

    }

    /**
     * Fills inputs with user data based on value stored in login id
     */
    function fill_inputs(){
        var login = $('#login').val();
        $.ajax({
            url: "get_user_info.php",
            method: "POST",
            dataType: "json",
            data: 'login=' + login,
            success: function (data) {
                clear_input_fields();
                if(data !== null){
                    $('#login').val(data['login']);
                    $('#name').val(data['name']);
                    $('#surname').val(data['surname']);
                    $('#email').val(data['email']);
                    $('#phone').val(data['phone']);

                    if (data['usergroup'] === "admin"){
                        $('#user_admin').prop('checked',true);

                    } else if (data['usergroup'] === "seller"){
                        $('#user_seller').prop('checked',true);

                    } else {
                        $('#user_customer').prop('checked',true);
                    }
                }else {
                    $('#login').css('background-color', '#f47070');
                    $('#error_message').html("Zadaný uživatel neexistuje");
                    clear_input_fields();
                }
            },
            error: function (data) {
                $('#error_message').html(data);
            }
        });
    }

    $(document).ready(function() {
        if ($('#login').val() !== ""){                        // Filling inputs automatically for user
            fill_inputs();
        }

        $('#set_username').click(function(){                  // Admin setting username => filling inputs with user data
            set_white(['login']);
            $('#error_message').html("");

            if ($('#login').val() !== ''){
                fill_inputs()
            } else {
                $('#login').css('background-color', '#f47070');
                $('#error_message').html("Zadejte login");
            }

        });

        $('#submit_info').click(function () {                 // User submitting their edited info
            var required = ['login', 'name', 'surname', 'email'];
            $('#error_message').html("");
            set_white(required);

            var all_ok = true;

            // Required fields check
            if (!check_required(required)){
                $('#error_message').html("Vyplňte povinné pole");
                all_ok = false
            }


            // Maximum input characters check (30)
            if (all_ok) {
                var _30_chars = ['login', 'name', 'surname'];
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

            // Phone validation
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
                $("#login").prop('disabled', false);
                var form_data = $('#edit_form').serialize();
                $.ajax({
                    url: "user_edit.php",
                    method: "POST",
                    data: form_data,
                    success: function (data) {
                        if(data !== "E_OK"){
                            $('#error_message').html(data);
                        } else {
                            $('#error_message').html("Uživatelské údaje upraveny");
                            $('#edit_form').trigger("reset");
                        }
                        $("#login").prop('disabled', true);
                    },
                    error: function (data) {
                        $("#login").prop('disabled', true);
                    }
                });
            }

        });

        $('#submit_pass').click(function () {                 // User submitting their edited password
            $('#error_message').html("");
            set_white(['old_password','password','password_again']);

            var login = $('#login').val();
            var old_password = $('#old_password').val();
            var password = $('#password').val();
            var password_again = $('#password_again').val();

            var all_ok = true;

            // Password uniformity check
            if (all_ok) {
                if (password !== password_again) {
                    all_ok = false;
                    $('#password').css('background-color', '#f47070');
                    $('#password_again').css('background-color', '#f47070');
                    $('#error_message').html("Zadaná hesla se neshodují");
                }
            }

            // Password length check
            if (all_ok){
                if (!is_valid_password(password)){
                    all_ok = false;
                    $('#password').css('background-color', '#f47070');
                    $('#password_again').css('background-color', '#f47070');
                    $('#error_message').html("Heslo musí být dlouhé minimálně 6 a maximálně 30 znaků. Povolené znaky: a-z,A-Z,0-9,-,_ ");
                }
            }

            if (all_ok) {

                var form_data = 'login='+login+'&old_password='+old_password+'&password='+password;
                $.ajax({
                    url: "user_edit.php",
                    method: "POST",
                    data: form_data,
                    success: function (data) {
                        if (data === "E_OK"){
                            $('#error_message').html("Heslo změněno");
                            $('#edit_form').trigger("reset");
                        } else {
                            $('#error_message').html(data);
                        }

                    },
                    error: function (data) {
                        $('#error_message').html(data);
                    }
                });
            }
        });


        $('#submit_all').click(function () {                 // Admin submitting edited user account
            $('#error_message').html("");
            set_white(['password','password_again','login', 'name', 'surname', 'email']);

            var login = $('#login').val();
            var password = $('#password').val();
            var password_again = $('#password_again').val();
            var email = $('#email').val();
            var phone = $('#phone').val();

            var all_ok = true;

            // Maximum input characters check (30)
            if (all_ok) {
                var _30_chars = ['login', 'name', 'surname'];
                if (!max_char_check(30,_30_chars)){
                    all_ok = false;
                    $('#error_message').html("Překročen znakový limit (30)");
                }
            }

            // Email validation
            if (all_ok && email !== ""){

                if (email.length > 50) {
                    all_ok = false;
                    $('#email').css('background-color', '#f47070');
                    $('#error_message').html("Překročen znakový limit (50)");
                }
                if (!is_valid_email($('#email').val())) {
                    all_ok = false;
                    $('#email').css('background-color', '#f47070');
                    $('#error_message').html("Neplatná emailová adresa");
                }

            }

            // Phone validation
            if (all_ok && phone !== ""){
                if (!is_valid_phone(phone)) {
                    all_ok = false;
                    $('#phone').css('background-color', '#f47070');
                    $('#error_message').html("Neplatné telefonní číslo");
                }
            }

            // Password uniformity check
            if (all_ok && password !== "") {
                if (password !== password_again) {
                    all_ok = false;
                    $('#password').css('background-color', '#f47070');
                    $('#password_again').css('background-color', '#f47070');
                    $('#error_message').html("Zadaná hesla se neshodují");
                }
            }

            // Password length check
            if (all_ok && password !== ""){
                if (!is_valid_password(password)){
                    all_ok = false;
                    $('#password').css('background-color', '#f47070');
                    $('#password_again').css('background-color', '#f47070');
                    $('#error_message').html("Heslo musí být dlouhé minimálně 6 a maximálně 30 znaků. Povolené znaky: a-z,A-Z,0-9,-,_ ");
                }
            }

            if (all_ok) {
                var form_data = $('#edit_form').serialize();
                $.ajax({
                    url: "user_edit.php",
                    method: "POST",
                    data: form_data,
                    success: function (data) {
                        $('#error_message').html(data);
                        $('#edit_form').trigger("reset");
                    },
                    error: function (data) {
                        $('#error_message').html(data);
                    }
                });
            }


        });
    })
        
</script>