<? header("Content-Type: text/html; charset=UTF-8");?>
<?php
    require_once 'db_init.php';
    session_start();
    if(isset($_SESSION['login'])){
        header("location: projections.php");
    }
    /*
     * Přihlašovací stránka
     * */
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>

<head>
    <title> Multikina </title>
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
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

          <h2>Přihlášení</h2>

          <form id="login_form">
              <div class="form-group">
                  <label class="control-label" for="name">Uživatelské jméno</label>
                  <input class="form-control" id="name" name="login" type="text" placeholder="Uživatelské jméno">
              </div>

              <div class="form-group">
                  <label class="control-label" for="password">Heslo</label>
                  <input class="form-control" id="password" name="password" type="password" placeholder="Heslo">
              </div>

              <div class="row">
                  <div class="col-md-4">
                      <input class="btn btn-primary" id="submit" name="submit" type="button" value="Login" >
                  </div>

                  <div class="col-md-8">
                      <span id="error_message"></span>
                  </div>


              </div>

          </form><br>
          <a href="register_page.php">Zaregistrovat</a>

      </div>
</div>
</body>

</html>

<script>
    $("#password").keyup(function(event) {
        if (event.keyCode === 13) {
            $("#submit").click();
        }
    });

    $(document).ready(function() {
        $('#submit').click(function () {
            var form_data = $('#login_form').serialize();

            $.ajax({
                url: "login.php",
                method: "POST",
                data: form_data,
                success: function (data) {
                    if (data === "E_OK")
                        window.location.replace('projections.php');
                    else {
                        $('#error_message').html("Neplatné uživatelské jméno nebo heslo");
                    }
                },
                error: function (data) {
                    $('#error_message').html(data);
                }
            });
        });
    });

</script>