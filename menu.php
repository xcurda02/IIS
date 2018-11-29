<? header("Content-Type: text/html; charset=UTF-8");?>
<?php

echo '<table class="noprint" width="100%" border=0 cellpadding=0 cellspacing=0 style="background-color: #555555">';
echo '<tr>';
echo '<th style="width: 15%; border-left: 1px solid #fff"><a class="stab" href="schedules.php">Program kin</a></th>';
echo '<th style="width: 15%; border-left: 1px solid #fff"><a class="stab" href="search.php">Vyhledat projekci</a></th>';
echo '<th style="width: 15%; border-left: 1px solid #fff"><a class="stab" href="reservation.php">Rezervace</a></th>';
echo '<th style="width: 15%; border-left: 1px solid #fff"><a class="stab" href="tickets.php">Moje vstupenky</a></th>';
echo '<th style="width: 15%; border-left: 1px solid #fff"><a class="stab" href="edit_user_page.php">Editace údajů</a></th>';
echo '<th style="width: 15%; border-left: 1px solid #fff"><a class="stab" href="delete_user_page.php">Odstranit uzivatele</a></th>';
if(isset($_SESSION['login'])){
    echo '<th style="width: 10%; border-left: 1px solid #fff"><a class="stab" href="php/logout.php">logout</a></th>';
}else {
    echo '<th style="width: 10%; border-left: 1px solid #fff"><a class="stab" href="index.php">login</a></th>';
}
echo '</tr>';
echo '</table>';

?>