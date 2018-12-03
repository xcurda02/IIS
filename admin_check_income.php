<?php header("Content-Type: text/html; charset=UTF-8");
include 'admin_session.php';
/*
  * Stránka zobrazující informace o tržbě filmů a kin
  *
 * */
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
    <style type="text/css">
        div#left {
            margin-left: 2.5%;
            width: 45%;
            margin-right: 2.5%;
            height: auto;
            float: left;
        }
        div#right {
            margin-left: 52.5%;
            margin-right: 2.5%;
            height:auto;
        }
        #movies,#cinemas{
            padding: 20px;
        }
        p {
            font-size: x-large;
            margin: 12px;
        }
    </style>




</head>

<body>
<?php
include 'menu.php';
?>
<div id="wrapper">

    <div id="left">
        <p>Filmy podle příjmů:</p>
        <table class="table" id="movies">
            <?php
                require_once 'db_init.php';
                $db = get_db();
                $query = $db->query("SELECT m.name as name, SUM(p.income) as income
                                            FROM movie m
                                            INNER JOIN projection p ON p.fk_movie = m.movie_id
                                            GROUP BY m.name
                                            ORDER BY income DESC");
                while ($movie = $query->fetch_assoc()){
                    echo "<tr><td>{$movie['name']}</td>";
                    echo "<td>{$movie['income']} Kč</td></tr>";
                }

            ?>
        </table>

    </div>

    <div id="right">
        <p>Kina podle příjmů:</p>
        <table class="table" id="cinemas">
            <?php
            require_once 'db_init.php';
            $db = get_db();
            $query = $db->query("SELECT c.name as name, SUM(p.income) as income
                                FROM projection p
                                INNER JOIN auditorium a ON a.auditorium_id = p.fk_auditorium
                                INNER JOIN cinema c ON c.cinema_id = a.fk_cinema
                                GROUP BY c.name
                                ORDER BY income DESC");
            while ($movie = $query->fetch_assoc()){
                echo "<tr><td>{$movie['name']}</td>";
                echo "<td>{$movie['income']} Kč</td></tr>";
            }

            ?>

        </table>

    </div>

</div>


</body>

</html>
