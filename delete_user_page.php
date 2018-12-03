<? header("Content-Type: text/html; charset=UTF-8");?>
<?php
require_once 'db_init.php';
include 'admin_session.php';
/*
 * Stránka umožňující administrátorovi smazat účet
 */
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>

<head>
    <title> Multikina </title>
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <script type="application/javascript" src="js/jquery-3.3.1.min.js"></script>
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

            <h2>Zrušení účtu</h2>
            <form id="delete_user_form" onkeypress="return event.keyCode !== 13;">
                <div class="form-group required">

                    <label class="control-label" for="login">Uživatelské jméno:</label>
                    <input class="form-control" id="login" name="login" type="text">
                </div>

                <div class="row">

                    <div class="col-md-6">
                        <input  class='btn btn-primary' type="button" name="submit" id="submit" value="Zrušit účet">
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

    $(document).ready(function() {
        $('#submit').click(function () {
            $('#login').css('background-color', '#ffffff');
            $('#error_message').html("");
            var form_data = $('#delete_user_form').serialize();
            $.ajax({
                url: "delete_user_script.php",
                method: "POST",
                data: form_data,
                success: function (data) {
                    if (data === "OK") {
                        $('#error_message').html("Uživatel odstraněn");
                        $('#delete_user_form').trigger("reset");
                    }
                    else {
                        $('#login').css('background-color', '#f47070');
                        $('#error_message').html(data);
                    }
                },
                error: function (data) {
                    $('#error_message').html(data);
                }
            });
        });
    });

</script>