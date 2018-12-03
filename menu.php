<?php
require_once 'users.php';

echo '<nav  class="navbar navbar-expand-sm bg-dark navbar-dark" style="border-radius: 5px;">';

if (isset($_SESSION['login'])) {


    echo '<ul class="navbar-nav mr-auto">';

    echo '<li class="nav-item"><a class="nav-link" href="projections.php">Projekce</a></li>';

    if (check_usergroup($_SESSION['login'], 'customer')) {      // Customer menu items
        echo '<li class="nav-item"><a class="nav-link" href="tickets.php">Moje Vstupenky</a></li>';

    } else if (check_usergroup($_SESSION['login'], 'seller')){  // Seller menu items
        echo '<li class="nav-item"><a class="nav-link" href="seller_search_reservations.php">Zákaznické rezervace</a></li>';

    }else{                                                                  // Admin menu items
        echo '<li class="nav-item"><a class="nav-link" href="tickets.php">Moje Vstupenky</a></li>';
        echo '<li class="nav-item"><a class="nav-link" href="admin_check_income.php">Tržba</a></li>';
        echo '<li class="nav-item"><a class="nav-link" href="register_page.php">Nový účet</a></li>';
        echo '<li class="nav-item"><a class="nav-link" href="admin_new_movie.php">Nový film</a></li>';
        echo '<li class="nav-item"><a class="nav-link" href="admin_new_projection.php">Nová projekce</a></li>';
        echo '<li class="nav-item"><a class="nav-link" href="delete_user_page.php">Zrušení účtu</a></li>';


    }
    echo '<li class="nav-item"><a class="nav-link" href="edit_user_page.php">Editace účtu</a></li>';

    echo '</ul>';

}
    echo '<ul class="navbar-nav ml-auto">';
        if (!isset($_SESSION['login'])){
            echo'<li class="nav-item"><a class="nav-link" href="register_page.php"><i class="fa fa-user-plus" aria-hidden="true"></i>
 Registrace </a></li>';
            echo'<li class="nav-item"><a class="nav-link" href="index.php"><i class="fa fa-sign-in" aria-hidden="true"></i> Přihlásit </a></li>';
        } else{
            $user_in_session = $_SESSION['login'];
            if (check_usergroup($user_in_session,'admin')){
                $user_type = "Administrátor";
            } else if (check_usergroup($user_in_session,'seller')){
                $user_type = "Prodavač";
            } else{
                $user_type = "Zákazník";
            }
            echo"<span class='navbar-text'><span class='fa fa-user'></span> $user_in_session ($user_type)</span>";
            echo'<li class="nav-item"><a class="nav-link" href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Odhlásit </a></li>';
        }

    echo'</ul>';

  echo '</nav>';

?>