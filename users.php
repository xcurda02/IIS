<?php

/**
 * Checks whether user is in given user group
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

/**
 * Returns user id based on username
 */
function get_user_id($username){
    $db = get_db();
    $query = $db->query("select user.user_id AS id from `user` where login ='$username'");

    if ($query->num_rows == 1){
        $row = mysqli_fetch_assoc($query);
        return $row['id'];
    }else {
        return null;
    }
}
