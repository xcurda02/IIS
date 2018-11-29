<? header("Content-Type: text/html; charset=UTF-8");?>
<?php
/**
 * Checks whether user is in given user group
 * @param $username
 * @param $group_name  string ('admin','seller','customer')
 * @return bool
 */
function check_usergroup($username, $group_name){
    $db = get_db();
    $query = $db->query("select * from user where login ='$username'");
    $row = mysqli_fetch_assoc($query);
    if (isset($row['usergroup']) && $row['usergroup'] === $group_name)
        return true;
    else
        return false;

}
