<? header("Content-Type: text/html; charset=UTF-8");?>
<?php
    require_once 'db_init.php';
    include 'admin_session.php';
    ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>

<head>
    <title> Multikina </title>
    <link rel="stylesheet" href="styles/style.css" type="text/css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

</head>


<body>
  <?php
    include 'menu.php';
  ?>

  <h2>Zrušení účtu</h2>

    <form id="delete_user_form">
          <label>Uživatelské jméno:</label>
          <input id="login" name="login" type="text">

          <input id="submit" name="submit" type="button" value="Zrušit účet">
          <span id="ErrorMessage"></span>
    </form>


</body>

</html>

<script>

    $(document).ready(function() {
        $('#submit').click(function () {
            $('#login').css('background-color', '#ffffff');
            $('#ErrorMessage').html("");
            var form_data = $('#delete_user_form').serialize();
            for (var key in form_data) {
                console.log(key + ':' + form_data[key]);
            }
            $.ajax({
                url: "delete_user_script.php",
                method: "POST",
                data: form_data,
                success: function (data) {
                    if (data === "OK") {
                        $('#ErrorMessage').html("Uživatel odstraněn");
                    }
                    else {
                        $('#login').css('background-color', '#f47070');
                        $('#ErrorMessage').html("Uživatel neexistuje");
                    }
                },
                error: function (data) {
                    $('#ErrorMessage').html(data);
                }
            });
        });
    });

</script>