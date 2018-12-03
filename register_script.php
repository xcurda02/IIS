<?php
require_once 'db_protect.php';
require_once 'users.php';
session_start();

$return ="";

$login = "'".$_POST['login']."'";

if(isset($_POST['name']) && !empty($_POST['name']))
    $name = "'".protect_string($_POST['name'])."'";
else
    $name = null;

if (isset($_POST['surname']) && !empty($_POST['surname']))
    $surname = "'".protect_string($_POST['surname'])."'";
else
    $surname = null;

if (isset($_POST['email']) && !empty($_POST['email']))
    $email = "'".protect_string($_POST['email'])."'";
else
    $email = null;

if (isset($_POST['password']) && !empty($_POST['password']))
    $password = "'".password_hash(protect_string($_POST['password']),PASSWORD_DEFAULT)."'";
else
    $password = null;


if (isset($_POST['phone']) && !empty($_POST['phone']))
    $phone = "'".protect_string($_POST['phone'])."'";
else
    $phone = 'NULL';


if (isset($_POST['usergroup']))
    if (isset($_SESSION['login'])){
        if (check_usergroup($_SESSION['login'],'admin')){
            $usergroup = "'".$_POST['usergroup']."'";
        } else{
            header("location: no_access_page.php");
        }
    }else{
        header("location: no_access_page.php");
    }
else
    $usergroup = '\'customer\'';



$db = get_db();

// Login check
$query = $db->query("select * from `user` where login = $login");
if ($query->num_rows == 1) {
    echo "Uživatel se stejným loginem již existuje.";
    exit(1);
}

// email uniqueness check
if ($email != null) {
    $query = $db->query("select * from `user` where email = $email");

    if ($query->num_rows == 1) {
        echo "Uživatel se stejným emailem již existuje.";
        exit(1);

    }
}

/* Adding new account into database */
$query = $db->query("INSERT INTO `user` (login, `name`, surname, email, phone, password, usergroup) 
                            VALUES ($login, $name, $surname, $email,$phone, $password, $usergroup)");
if ($query === TRUE) {
    echo "EOK";
    exit(0);
} else {
    echo "DB Query Error: " . $db->error;
    exit(1);
}