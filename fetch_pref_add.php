<?php
    require_once 'auth.php';
    if (!$userid = checkAuth()) exit;

    header('Content-Type: application/json');

    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

    $url = urlencode($_GET["q1"]);
    $img_url = urlencode($_GET["q2"]);
    $query = "INSERT INTO tracks(canzone, img, user) VALUES('$url', '$img_url', $userid)";

    if(mysqli_query($conn, $query) or die(mysqli_error($conn))) {
        echo json_encode(array('ok' => true));
        exit;
    }

    mysqli_close($conn);
    echo json_encode(array('ok' => false));
?>