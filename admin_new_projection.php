<? header("Content-Type: text/html; charset=UTF-8");?>
<?php
require_once 'db_init.php';
include 'admin_session.php';
/*
 * Stránka umožňující administrátorovi vytvořit novou projekci
 */
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

        <h2>Vytvoření nové projekce</h2>
        <form id="proj_form" onkeypress="return event.keyCode !== 13;">

            <div class="form-group required">
                <label  class="control-label" for="cinema">Kino</label>
                <select class="form-control" name="cinema" id="cinema">
                    <?php
                        $db = get_db();
                        $query = $db->query("SELECT * FROM cinema");
                        while ($cinema = $query->fetch_assoc()){
                            echo "<option value=\"{$cinema['cinema_id']}\">{$cinema['name']}</option>";
                        }
                    ?>
                </select>
            </div>

            <div class="form-group required">
                <label class="control-label" for="auditorium">Číslo sálu</label>
                <input class="form-control" id="auditorium" name="auditorium" type="text">
            </div>

            <div class="form-group required">
                <label class="control-label" for="movie">Film</label>
                <select class="form-control" name="movie" id="movie">
                    <?php
                    $db = get_db();
                    $query = $db->query("SELECT * FROM movie");
                    while ($cinema = $query->fetch_assoc()){
                        echo "<option value=\"{$cinema['movie_id']}\">{$cinema['name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group required">

                <label class="control-label" for="time">Čas</label>
                <select class="form-control" name="time" id="time">
                    <option value="09:00:00">9:00</option>
                    <option value="10:00:00">10:00</option>
                    <option value="11:00:00">11:00</option>
                    <option value="12:00:00">12:00</option>
                    <option value="13:00:00">13:00</option>
                    <option value="14:00:00">14:00</option>
                    <option value="15:00:00">15:00</option>
                    <option value="16:00:00">16:00</option>
                    <option value="17:00:00">17:00</option>
                    <option value="18:00:00">18:00</option>
                    <option value="19:00:00">19:00</option>
                    <option value="20:00:00">20:00</option>
                    <option value="21:00:00">21:00</option>
                    <option value="22:00:00">22:00</option>
                    <option value="23:00:00">23:00</option>
                </select>

            </div>

            <div class="form-group required">
                <label class="control-label" for="date">Datum</label>
                <input class="form-control" type="date" id="date" name="date" value="">
            </div>


            <div class="row">

                <div class="col-md-6">
                    <input  class='btn btn-primary' type="button" name="submit" id="submit" value="Vytvořit projekci">
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

    /* Setting correct timezone */
    Date.prototype.toDateInputValue = (function() {
        var local = new Date(this);
        local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
        return local.toJSON().slice(0,10);
    });

    $(document).ready(function(){
        $('#date').val(new Date().toDateInputValue());                 // setting default value (today) for date input

        $('#submit').click(function(){

            $('#error_message').html("");
            set_white(['auditorium']);

            /* If auditorium value is OK, sending form data to create_new_projection script*/
            if (!isNaN($('#auditorium').val()) && $('#auditorium').val() !== "") {
                var form_data = $('#proj_form').serialize();
                $.ajax({
                    url: "create_new_projection.php",
                    method: "POST",
                    data: form_data,
                    success: function (data) {
                        if (data !== "E_OK"){
                            $('#error_message').html(data);
                        } else {
                            $('#error_message').html("Projekce vytvořena");
                            $('#proj_form').trigger("reset");
                        }

                    },
                    error: function (data) {
                        $('#error_message').html("ERRR");
                    }
                });
            }else{
                $('#error_message').html("Zadejte validní číslo sálu");
                $('#auditorium').css('background-color', '#f47070');
            }
        });
    });
</script>