<? header("Content-Type: text/html; charset=UTF-8");?>
<?php
require_once 'db_init.php';
include 'seller_session.php';
require_once 'users.php';
require_once 'db_protect.php';

/*
 * Stránka umožňující prodavačovi vyhledat rezervace podle loginu
 */

$error ="";
if (isset($_POST['submit'])){
    $user_id = get_user_id(protect_string($_POST['username']));
    if ($user_id == null){
        $error = "Uživatel neexistuje";
    } else{
        header("location: seller_user_reservations.php?user_id=$user_id");
    }




}

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
    <div id="center-box">


            <form name="search_form" id="search_form" method="post" action="">
                <div class="row">
                    <label class="control-label" for="username">Vyhledat rezervované vstupenky uživatele:</label>
                    <input class="form-control" id="username" name="username" type="text" value=<?php echo isset($_POST['username'])? $_POST['username'] : "" ?>>
                    <div class="col-md-4"><input class="btn btn-primary" id="submit" name="submit" type="submit" value=Vyhledat></div>

                    <div class="col-md-4"><span id="error_message"><?php echo $error ?></span></div>
                </div>
            </form>


    </div>


</div>
</body>

</html>

