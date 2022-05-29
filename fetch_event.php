<?php
    require_once 'auth.php';
    if (!$userid = checkAuth()) exit;

    header('Content-Type: application/json');

    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

    $query = "SELECT * FROM eventi";

    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));

    $postArray = array();

    while($entry = mysqli_fetch_assoc($res)) {
        $date=strtotime($entry['data']);
        $diff= time() - $date;
        $postArray[] = array('eventid' => $entry['id'], 'tipo' => $entry['tipo'], 'descr' => $entry['descr'], 
                            'data' => $entry['data'], 'user' => $entry['user'], 'time' => $diff);
    }

    echo json_encode($postArray);
    exit;
?>