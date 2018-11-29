<? header("Content-Type: text/html; charset=UTF-8");?>
<?php
function generate_admin_edit_form(){
    echo "<label>Uživatelské jméno:</label>";
    echo "<input id=\"login\" name=\"login\" type=\"text\" >*";
    echo "<input id=\"set_username\" name=\"set_username\" type=\"button\" value=\"Set\">";

    echo "<br>";

    echo "<label>Jméno:</label>";
    echo "<input id=\"name\" name=\"name\" type=\"text\">*<br>";

    echo "<label>Příjmení:</label>";
    echo "<input id=\"surname\" name=\"surname\" type=\"text\">*<br>";

    echo "<label>Emailová adresa:</label>";
    echo "<input id=\"email\" name=\"email\" type=\"text\">*<br>";

    echo "<label>Telefon:</label>";
    echo "<input id=\"phone\" name=\"phone\" type=\"text\"><br>";

    echo "<label>Heslo:</label>";
    echo "<input id=\"password\" name=\"password\" type=\"password\"><br>";

    echo "<label>Potvrdit heslo:</label>";
    echo "<input id=\"password_again\" name=\"password_again\" type=\"password\"><br>";


    echo "<label> Typ účtu: </label><br>";
    echo "<input id='user_admin' type='radio' name='usergroup' value='admin'>Administrátor<br>";
    echo "<input id='user_seller' type='radio' name='usergroup' value='seller'>Obchodník<br>";
    echo "<input id='user_customer' type='radio' name='usergroup' value='customer' checked>Zákazník<br>";


    echo "*Povinné <input id=\"submit_all\" name=\"submit_all\" type=\"button\" value=\"Editovat\">";
}

function generate_non_admin_edit_form($user){
    echo "<label>Uživatelské jméno:</label>";
    echo "<input id=\"login\" name=\"login\" type=\"text\" value=\"$user\" disabled>*";

    echo "<br>";

    echo "<label>Jméno:</label>";
    echo "<input id=\"name\" name=\"name\" type=\"text\">*<br>";

    echo "<label>Příjmení:</label>";
    echo "<input id=\"surname\" name=\"surname\" type=\"text\">*<br>";

    echo "<label>Emailová adresa:</label>";
    echo "<input id=\"email\" name=\"email\" type=\"text\">*<br>";

    echo "<label>Telefon:</label>";
    echo "<input id=\"phone\" name=\"phone\" type=\"text\"><br>";

    echo "*Povinné <input id=\"submit_info\" name=\"submit_info\" type=\"button\" value=\"Editovat\">";
    echo "<hr>";

    echo "<label>Staré heslo:</label>";
    echo "<input id=\"old_password\" name=\"old_password\" type=\"password\"><br>";

    echo "<label>Nové heslo:</label>";
    echo "<input id=\"password\" name=\"password\" type=\"password\"><br>";

    echo "<label>Potvrdit nové heslo:</label>";
    echo "<input id=\"password_again\" name=\"password_again\" type=\"password\"><br>";

    echo "*Povinné <input id=\"submit_pass\" name=\"submit_pass\" type=\"button\" value=\"Změnit heslo\">";

}