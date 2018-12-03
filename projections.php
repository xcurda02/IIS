<? header("Content-Type: text/html; charset=UTF-8");?>
<?php
require_once 'db_init.php';
include 'session.php';
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

</head>

<body>
<?php

include 'menu.php';
?>
<div id="wrapper">


        <form id="search_form" onkeypress="return event.keyCode !== 13;">
            <div class="form-row">
                <div class="form-group col-md-2">
                    <p style="font-size: 18px;margin-top: 5px;">Vyhledat projekci podle:</p>
                </div>
                <div class="form-group col-md-2">
                    <select class="form-control" name="search_type">
                        <option value="cinema">Kino</option>
                        <option value="movie">Film</option>
                        <option value="genre">Žánr</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <input id="keyword" class="form-control" name="keyword" type="text">
                </div>
                <div class="form-group col-md-2">
                    <input id="submit" name="submit" type="button"  class="btn btn-primary" value=Vyhledat>
                </div>
            </div>
        </form>
    <div id="center-box">

        <div id='results'>
        </div>
        <div id="error-message"></div>
        </div>
</div>
</body>

</html>

<script>
    $("#keyword").keyup(function(event) {
        if (event.keyCode === 13) {
            $("#submit").click();
        }
    });

    /* Function shows result of searching for a projection */
    function view_result(data){
        var date = data['timedate'].split(" ");

        var result_div = document.createElement("div");
        result_div.className = "result";
        result_div.innerHTML = "("+date[0]+")<h3> " + data['movie']+"</h3><br>";
        result_div.innerHTML += "Kino: " + data['cinema']+"<br>";
        result_div.innerHTML += "Čas: " + date[1]+"<br>";
        result_div.innerHTML += "Číslo sálu: " + data['auditorium']+"<br>";

        result_div.onclick = function() {           // Click redirects to a projection page
            window.location.href = "reservation.php?id="+data['id'];
        };
        result_div.style = "cursor : pointer;";
        $('#results').append(result_div);

    }

    $(document).ready(function() {
        document.getElementById('submit').click();      // Simulate click to show all projections on entering page
    });

    $('#submit').click(function () {                    // Submit was clicked - requesting projections
        $('#error-message').html("");
        $( ".result" ).remove();
        var form_data = $('#search_form').serialize();
        $.ajax({
            url: "get_projections.php",
            method: "POST",
            data: form_data,
            dataType: "json",
            success: function (data) {
                console.log(data);
                for (var i = 0; i < data.length; i++){
                    view_result(data[i]);
                }
            },
            error: function (data) {
                $('#error-message').html("<h3>Žádné výsledky</h3>");
            }
        });
    });

</script>