<? header("Content-Type: text/html; charset=UTF-8");?>
<?php
require_once 'users.php';

$user_in_session = $_SESSION['login'];

$query = $db->query("select * from user where login ='$user_in_session'");
$user_info = mysqli_fetch_assoc($query);

$user_type = "";
if (check_usergroup($user_in_session,'admin')){
    $user_type = "Administrátor";
} else if (check_usergroup($user_in_session,'seller')){
    $user_type = "Prodavač";
} else{
    $user_type = "Zákazník";
}
echo "<p style='float: right; margin: 0px'>";
echo "Přihlášen jako " .$user_type.", \"".$user_in_session."\"<b> ".$user_info['name']." ".$user_info['surname']."</b>";
echo "</p>";