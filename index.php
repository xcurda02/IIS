<?php
    include "login.php";
    if(isset($_SESSION['login'])){
        header("location: schedules.php");
    }
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>

<head>
    <title> Multikina </title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
    <link rel="stylesheet" href="styles/style.css" type="text/css">

</head>

<body>
  <?php
    include 'menu.php';
    require_once 'db_init.php';

  ?>

  <h2>Login Form</h2>
  <form action="" method="post">
      <label>UserName :</label>
      <input id="name" name="username" placeholder="username" type="text">
      <label>Password :</label>
      <input id="password" name="password" placeholder="**********" type="password">
      <input name="submit" type="submit" value=" Login ">
      <span><?php echo $error; ?></span>
  </form>



</body>

</html>