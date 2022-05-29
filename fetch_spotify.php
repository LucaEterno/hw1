<?php
    require_once 'auth.php';
    
    if (!checkAuth()) exit;
    
    header('Content-Type: application/json');

    $client_id = '5eb0ba2dddba4f8daf421dc122c55ddd';
    $client_secret = '88c8cbee55a847eb8de21d438a0c13cc';

    // ACCESS TOKEN
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token' );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_POST, 1);

    curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials'); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic '.base64_encode($client_id.':'.$client_secret))); 
    $token=json_decode(curl_exec($ch), true);
    curl_close($ch);
    
    switch($_GET['type']) {
        case 'cerca': 
            $query = urlencode($_GET["q"]);
            $url = 'https://api.spotify.com/v1/search?type=track&q='.$query;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            # TOKEN
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token['access_token'])); 
            $res=curl_exec($ch);
            curl_close($ch);

            echo $res;

            break;

        case 'playlist': 
            $url = 'https://api.spotify.com/v1/playlists/3umpWOkoOPRBgf30lxVbmd';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            # TOKEN
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token['access_token'])); 
            $res=curl_exec($ch);
            curl_close($ch);

            echo $res;
            
            break;

        default: break;
    }
?>