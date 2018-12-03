<?php
require_once 'db_init.php';

function get_projection_time_modifier($time){
    if ($time < 13){
        return 0.8;
    } else if ($time >= 13 && $time < 17){
        return 0.9;
    } else{
        return 1;
    }
}

function get_age_group_modifier($age_group){
    if ($age_group === 'child'){
        return 0.8;
    } else if ($age_group === 'senior'){
        return 0.9;
    } else{
        return 1;
    }
}

/*
 * Calculates price based on movie base price, age group and time when projection is held
 * All movies have their basic cost,but age group and projection time modify final price
 * by given expression: ticket_price = movie_price * projection_time_modifier * age_group_modifier
 * Adults pay full price while seniors 90% and children only 80%
 * Evening projections cost full price while afternoon projections 90% and morning projections 80%
 * */
function calculate_price($projection_id, $age_group){
    $db = get_db();
    $time_query = $db->query("SELECT projection.date AS date_time FROM projection WHERE projection.projection_id = '$projection_id'");
    if ($time_query == null){
        return null;

    }
    $row = $time_query->fetch_assoc();
    $dt = DateTime::createFromFormat("Y-m-d H:i:s", $row['date_time']);
    $hours = $dt->format('H');  // Getting hours from formatted date


    /* Getting base price of movie from db */
    $price_query = $db->query("SELECT m.price AS price FROM movie m INNER JOIN projection p WHERE p.projection_id = '$projection_id' AND p.fk_movie = m.movie_id");
    if ($price_query == null){
        return null;
    }
    $row = $price_query->fetch_assoc();
    $base_price = intval($row['price']);

    /* Calculating price */
    $modifier = number_format(get_projection_time_modifier($hours) * get_age_group_modifier($age_group),6);
    return number_format($base_price * $modifier, 0);
}