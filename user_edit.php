<?php
require_once 'db_protect.php';
require_once 'users.php';
include 'session.php';

    if($user_in_session !== $_POST['login'] && !check_usergroup($user_in_session,'admin')){
        header("location: no_access_page.php");
    }


    // Protecting input strings
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

    if (isset($_POST['old_password']) && !empty($_POST['old_password']))
        $old_password = "'".password_hash(protect_string($_POST['old_password']), PASSWORD_DEFAULT)."'";
    else
        $old_password = null;

    if (isset($_POST['password']) && !empty($_POST['password']))
        $password = "'".password_hash(protect_string($_POST['password']),PASSWORD_DEFAULT)."'";
    else
        $password = null;


    if (isset($_POST['phone']) && !empty($_POST['phone']))
        $phone = "'".protect_string($_POST['phone'])."'";
    else
        $phone = 'NULL';


    if (isset($_POST['usergroup']))

        if (check_usergroup($user_in_session,'admin')){
            $usergroup = "'".$_POST['usergroup']."'";
        } else{
            header("location: no_access_page.php");
        }

    else
        $usergroup = '\'customer\'';


    $db = get_db();

    // Login check
    $query = $db->query("select * from `user` where login = $login");
    if ($query->num_rows == 0){
        echo "Uživatel neexistuje";
        exit(1);
    }

    // email uniqueness check
    if ($email != null) {

        $query = $db->query("select * from `user` where email = $email");

        if ($query->num_rows == 1) {
            $row = $query->fetch_assoc();
            if ($row['login'] != $_POST['login']) {
                echo "Uživatel se stejným emailem již existuje";
                exit(1);
            }
        }
    }


    // associative array containing possible-to-edit values (name,surname,email,password,phone,usergroup)
    $edit_arr = array('name' => $name, 'surname' => $surname, 'email' => $email,'password' => $password,'phone' => $phone,'usergroup' => $usergroup );
    foreach($edit_arr as $key => $value) {
        if ($value != null) {
            $query = $db->query("UPDATE `user` 
                                        SET $key = $value 
                                        WHERE login = $login ");

            if ($query !== TRUE) {
                echo "DB Query Error: " . $db->error;
                exit(1);
            }
        }
    }
    echo "E_OK";
    exit(0);
