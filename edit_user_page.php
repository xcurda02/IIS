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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>

<head>
    <title> Multikina </title>
    <link rel="stylesheet" href="styles/style.css" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script type="application/javascript" src="js/valid_input.js"></script>


</head>

<body>
<?php
include 'menu.php';
?>


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
<span id="succ_message"></span>



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
                var form_data = $('#edit_form').serialize()+'&type=edit';
                $("#login").prop('disabled', false);
                for (var key in form_data){
                    console.log(key+':'+form_data[key]);
                }
                $.ajax({
                    url: "user_edit.php",
                    method: "POST",
                    data: $('#edit_form').serialize()+'&type=edit',
                    success: function (data) {
                        //$("form").trigger("reset");
                        $('#succ_message').html(data);
                        $("#login").prop('disabled', true);
                    },
                    error: function (data) {
                        $('#error_message').html(data);
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
                // Debug
                var form_data = 'login='+login+'&old_password='+old_password+'&password='+password+'&type=edit';
                for (var key in form_data){
                    console.log(key+':'+form_data[key]);
                }
                $.ajax({
                    url: "user_edit.php",
                    method: "POST",
                    data: form_data,
                    success: function (data) {
                        $('#succ_message').html(data);
                    },
                    error: function (data) {
                        $('#error_message').html(data);
                    }
                });
            }
        });

    })
        
</script>