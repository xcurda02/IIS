<?php
function generate_admin_edit_form(){
    echo "
            <div class=\"form-group required\" style='margin-bottom: 0'>
                <label class=\"control-label\" for=\"login\">Uživatelské jméno</label>
            </div>
            <div class=\"input-group mb-3\">
                <input id='login' name='login' type=\"text\" class=\"form-control\" placeholder=\"Uživatelské jméno\" aria-label=\"Uživatelské jméno\" aria-describedby=\"basic-addon2\">
                <div class=\"input-group-append\">
                    <button id='set_username' name='set_username' class=\"btn btn-outline-secondary\" type=\"button\">Vyplnit</input>

                </div>
            </div>

            <div class=\"form-group required\">
                <label class=\"control-label\" for=\"name\">Jméno</label>
                <input id=\"name\" class=\"form-control\" name=\"name\" type=\"text\" placeholder=\"Jméno\">
            </div>

            <div class=\"form-group required\">
                <label class=\"control-label\" for=\"surname\">Příjmení</label>
                <input id=\"surname\" class=\"form-control\" name=\"surname\" type=\"text\" placeholder=\"Příjmení\">
            </div>

            <div class=\"form-group required\">
                <label class=\"control-label\" for=\"email\">Emailová adresa</label>
                <input id=\"email\" class=\"form-control\" name=\"email\" type=\"text\" placeholder=\"Emailová adresa\">
            </div>

            <div class=\"form-group\">
                <label class=\"control-label\" for=\"phone\">Telefon</label>
                <input id=\"phone\" class=\"form-control\" name=\"phone\" type=\"text\" placeholder=\"Telefon\">
            </div>

            <div class=\"form-group required\">
                <label class=\"control-label\" for=\"password\">Heslo</label>
                <input id=\"password\" class=\"form-control\" name=\"password\" type=\"password\" placeholder=\"Heslo\">
            </div>

            <div class=\"form-group required\">
                <label class=\"control-label\" for=\"password_again\">Potvrdit heslo</label>
                <input id=\"password_again\" class=\"form-control\" name=\"password_again\" type=\"password\" placeholder=\"Heslo (znovu)\">
            </div>
            <div class=\"row\">";
        echo "<div class=\"col-md-2\">";
        echo "<label class=\"control-label\"> Typ účtu: </label></div><div class='col-md-6'>";
        echo "<div class='form-check'><label class='form-check-label'><input type='radio' class='form-check-input' name='usergroup' value='admin'>Administrátor</label></div>";
        echo "<div class='form-check'><label class='form-check-label'><input type='radio' class='form-check-input' name='usergroup' value='seller'>Prodavač</label></div>";
        echo "<div class='form-check'><label class='form-check-label'><input type='radio' class='form-check-input' name='usergroup' value='customer' checked>Zákazník</label></div>";
        echo "</div>";
        echo "<div class=\"col-md-4\">
                    <input id=\"submit_all\" class='btn btn-primary' name=\"submit_all\" type=\"button\" value=\"Změnit údaje\" style=\"float: right\">
                </div></div>";
}

function generate_non_admin_edit_form($user){
    echo"<div class=\"form-group required\">
                <label class=\"control-label\" for=\"login\">Uživatelské jméno</label>
                <input id=\"login\" class=\"form-control\" name=\"login\" type=\"text\" value='{$_SESSION['login']}' disabled>

            </div>

            <div class=\"form-group required\">
                <label class=\"control-label\" for=\"name\">Jméno</label>
                <input id=\"name\" class=\"form-control\" name=\"name\" type=\"text\" placeholder=\"Jméno\">
            </div>

            <div class=\"form-group required\">
                <label class=\"control-label\" for=\"surname\">Příjmení</label>
                <input id=\"surname\" class=\"form-control\" name=\"surname\" type=\"text\" placeholder=\"Příjmení\">
            </div>

            <div class=\"form-group required\">
                <label class=\"control-label\" for=\"email\">Emailová adresa</label>
                <input id=\"email\" class=\"form-control\" name=\"email\" type=\"text\" placeholder=\"Emailová adresa\">
            </div>

            <div class=\"form-group\">
                <label class=\"control-label\" for=\"phone\">Telefon</label>
                <input id=\"phone\" class=\"form-control\" name=\"phone\" type=\"text\" placeholder=\"Telefon\">
            </div>";
    echo "<div class='row'><div class='col-md-6'></div><div class=\"col-md-4\">
                    <input id=\"submit_info\" class='btn btn-primary' name=\"submit_info\" type=\"button\" value=\"Změnit údaje\" style=\"float: right\">
                </div></div>
                <hr>";


    echo "<div class=\"form-group required\">
                <label class=\"control-label\" for=\"old_password\">Staré heslo</label>
                <input id=\"old_password\" class=\"form-control\" name=\"old_password\" type=\"password\" placeholder=\"Staré heslo\">
            </div>";

    echo "<div class=\"form-group required\">
                <label class=\"control-label\" for=\"password\">Nové heslo</label>
                <input id=\"password\" class=\"form-control\" name=\"password\" type=\"password\" placeholder=\"Nové heslo\">
            </div>

            <div class=\"form-group required\">
                <label class=\"control-label\" for=\"password_again\">Potvrdit heslo</label>
                <input id=\"password_again\" class=\"form-control\" name=\"password_again\" type=\"password\" placeholder=\"Nové heslo (znovu)\">
            </div>";

    echo "<div class='row'><div class='col-md-6'></div><div class=\"col-md-4\">
                    <input id=\"submit_pass\" class='btn btn-primary' name=\"submit_pass\" type=\"button\" value=\"Změnit heslo\" style=\"float: right\">
                </div></div>";

}