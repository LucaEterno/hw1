<?php
    require_once 'auth.php';
    if (!$userid = checkAuth()) exit;

    header('Content-Type: application/json');

    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

    $canzone = urlencode($_GET["q"]);
    $query = "SELECT * FROM tracks WHERE user = $userid AND canzone = '$canzone'";

    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));

    $tracksArray = array();

    while($entry = mysqli_fetch_assoc($res)) {
        $tracksArray[] = array('trackid' => $entry['id'], 'canzone' => $entry['canzone'], 'img' => $entry['img'], 'user' => $entry['user']);
    }

    echo json_encode($tracksArray);
    exit;
?>