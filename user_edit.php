<? header("Content-Type: text/html; charset=UTF-8");?>
<?php
require_once 'db_protect.php';

    $return ="";

    // Protecting input strings
    //$login = "'".protect_string($_POST['login'])."'";
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
        $old_password = "'".protect_string($_POST['password'])."'";
    else
        $old_password = null;

    if (isset($_POST['password']) && !empty($_POST['password']))
        $password = "'".protect_string($_POST['password'])."'";
    else
        $password = null;

    if (isset($_POST['password_again']) && !empty($_POST['password_again']))
        $password_again = "'".protect_string($_POST['password_again'])."'";
    else
        $password_again = null;


    if (isset($_POST['phone']) && !empty($_POST['phone']))
        $phone = "'".protect_string($_POST['phone'])."'";
    else
        $phone = 'NULL';


    if (isset($_POST['usergroup']))
        $usergroup = "'".$_POST['usergroup']."'";
    else
        $usergroup = '\'customer\'';


    /*
     * register == true => register mode
     * register == false => editing mode
     * */
    $register = false;
    if ($_POST['type'] === 'register')
        $register = true;



    $db = get_db();

    // Login check
    $query = $db->query("select * from `user` where login = $login");
    if ($query->num_rows == 1 && $register) {
        echo "NON_UNIQUE_LOGIN";
        exit(1);

    } else if ($query->num_rows == 0 && !$register){
        echo "NON_EXISTING_USER";
        exit(1);
    }

    // email uniqueness check
    if ($email != null) {

        $query = $db->query("select * from `user` where email = $email");

        if ($query->num_rows == 1) {
            $row = $query->fetch_assoc();
            if ($register || (!$register && ($row['login'] != $_POST['login']))) {
                echo "NON_UNIQUE_EMAIL";
                exit(1);
            }
        }
    }

    /* Adding new account into database */
    if ($register) {
        $query = $db->query("INSERT INTO `user` (login, `name`, surname, email, phone, password, usergroup) 
                                VALUES ($login, $name, $surname, $email,$phone, $password, $usergroup)");
        if ($query === TRUE) {
            echo "EOK";
            exit(0);
        } else {
            echo "DB Query Error: " . $db->error;
            exit(1);
        }

    /* Editing existing account */
    }else{
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
        echo "Uživatel upraven";
    }











