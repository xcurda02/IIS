<?php
    require_once 'db_init.php';

    session_start();

    $error = "";
    if (isset($_POST['submit'])) {
        if (empty($_POST['username']) || empty($_POST['password'])) {
            $error = "Invalid username or password";
        } else {

            /* Getting credentials */
            $username = $_POST['username'];
            $password = $_POST['password'];

            /* SQL injection protection */
            $username = stripslashes($username);
            $password = stripslashes($password);
            $username = mysql_real_escape_string($username);
            $password = mysql_real_escape_string($password);

            /* Compare credentials with DB */
            $query = $db->query("select * from uzivatel where login='$username' AND heslo='$password'");
            if ($query->num_rows == 1){
                $_SESSION['login'] = $username;
                header("location: schedules.php");
            } else{
                $error =  "Invalid username or password";
            }

        }
    }
    ?>
