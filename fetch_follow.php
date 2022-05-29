<?php
    require_once 'auth.php';
    if (!$userid = checkAuth()) exit;

    header('Content-Type: application/json');

    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

    $eventid = urlencode($_GET["q"]);
    $query = "SELECT * FROM follows WHERE userid = $userid AND eventid = '$eventid'";

    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));

    $tracksArray = array();

    while($entry = mysqli_fetch_assoc($res)) {
        $tracksArray[] = array('userid' => $entry['userid'], 'eventid' => $entry['eventid']);
    }

    echo json_encode($tracksArray);
    exit;
?>