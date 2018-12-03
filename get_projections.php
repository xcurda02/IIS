<?php
include 'session.php';
require_once 'db_protect.php';
require_once 'db_init.php';

if (!empty($_POST['keyword']))
    $keyword = protect_string($_POST['keyword']);
else
    $keyword = null;

$search_type = protect_string($_POST['search_type']);



$db = get_db();
$results = array();


if ($keyword != null) {                             // Searching by keyword
    $col="";
    if ($search_type === 'cinema') {                // Searching by cinema
        $col = "c.name";
    } else if ($search_type === 'movie') {          // Searching by movie
        $col = "m.name";
    } else {                                        // Searching by genre
        $col = "m.genre";
    }
    $query = $db->query("SELECT p.projection_id AS id, p.date AS timedate, m.name AS movie, c.name AS cinema, a.number AS auditorium
                                FROM cinema c
                                INNER JOIN auditorium a
                                INNER JOIN projection p
                                INNER JOIN movie m
                                WHERE c.cinema_id = a.fk_cinema
                                AND a.auditorium_id = p.fk_auditorium
                                AND p.fk_movie = m.movie_id
                                AND $col = '$keyword'
                                AND p.date > NOW()
                                ORDER BY p.date ASC");
}else{                                                      // Getting all upcomig projections
    $query = $db->query("SELECT p.projection_id AS id,p.date AS timedate, m.name AS movie, c.name AS cinema, a.number AS auditorium
                                FROM cinema c
                                INNER JOIN auditorium a
                                INNER JOIN projection p
                                INNER JOIN movie m
                                WHERE c.cinema_id = a.fk_cinema
                                AND a.auditorium_id = p.fk_auditorium
                                AND p.fk_movie = m.movie_id
                                AND p.date > NOW()
                                ORDER BY p.date ASC");
}

if ($query->num_rows > 0){
    while ($row = $query->fetch_assoc()){
        $results[]=$row;
    }
    echo json_encode($results);
    exit(0);

}else{
    echo null;
    exit(1);
}



