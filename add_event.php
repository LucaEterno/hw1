<?php
    require_once 'auth.php';
    if (!$userid = checkAuth()) {
        header("Location: login.php");
        exit;
    }
?>

<?php
    if (!empty($_POST["type"]) && !empty($_POST["descr"])  && !empty($_POST["data"]))
    {
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));

        #DATA
        $date=strtotime($_POST['data']);
        $diff= time() - $date;
        if ( $diff > 86399  ){
            $error = "Non puoi tornare indietro nel tempo, inserire data valida";
        } else if ( $diff < -31536000){
            $error = "Magari qualcosa di un po' più vicino, inserire data valida";
        } else {
            $error = 0;
        }


        # REGISTRAZIONE NEL DATABASE
        if ($error === 0) {
            $tipo = mysqli_real_escape_string($conn, $_POST['type']);
            $descr = mysqli_real_escape_string($conn, $_POST['descr']);
            $data = mysqli_real_escape_string($conn, $_POST['data']);

            $userid = mysqli_real_escape_string($conn, $userid);
            $query = "SELECT username FROM users WHERE id = $userid";
            $res = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($res);
            $username = $row['username'];

            $query = "INSERT INTO eventi (tipo, descr, data, user) VALUES('$tipo', '$descr', '$data', '$username')";
            
            if (mysqli_query($conn, $query)) {
                mysqli_close($conn);
                header("Location: home.php");
                exit;
            } else {
                $error = "Errore di connessione al Database";
            }
        }

        mysqli_close($conn);
    }
    else if ( (isset($_POST["descr"])) || (isset($_POST["date"]))  ) {
        $error = "Riempi tutti i campi";
    }

?>

<html>
    <?php 
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);
        $userid = mysqli_real_escape_string($conn, $userid);
        $query = "SELECT * FROM users WHERE id = $userid";
        $res = mysqli_query($conn, $query);
        $userinfo = mysqli_fetch_assoc($res); 
    ?>

    <head>
        <title>Ewind - add</title>
        
        <link rel='stylesheet' href='style/add_event.css'>
        <script src='scripts/add_event.js' defer></script>

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="assets/e.png">
        <meta charset="utf-8">
    </head>

    <body>
        <header>
            <nav>
                <div class="l_nav">
                    <div class="logo" style="background-image: url(assets/ee.png)"></div>
                    <a href="./" <?php if(!isset($_GET['user'])); ?> >Home</a>
                    <a href="add_event.php" class='here'>Aggiungi evento</a>
                    <a href="spotify.php">Spotify</a>
                    <a href="preferiti.php">Preferiti</a>
                    <a href="logout.php">Logout</a><br><br>
                </div>

                <div class="r_nav">
                        <div class="username">
                            Benvenuto <?php echo $userinfo['username'] ?>
                        </div>

                        <div class="profilo" style="background-image: url(<?php echo $userinfo['propic'] ?>)">
                        </div>
                </div>

                <div class="hide_nav">
                        <div></div>
                        <div></div>
                        <div></div>
                </div>
            </nav>
        </header>

        <main>
            <section>
                <h1>Aggiungi evento:</h1>
                <div>
                    <?php
                        if (isset($error)) {
                            echo "<span class='error'>$error</span>";
                        }
                    ?>

                    <form name='event' method='post'>
                        <div><label for='type'>Tipologia evento:</label></div>
                        <div>
                            <select name='type' id='type'>
                                <option value='Evento sociale' selected>Evento sociale</option>
                                <option value='Evento musicale'>Evento musicale</option>
                                <option value='Spettacolo'>Spettacolo</option>
                                <option value='Evento sportivo'>Evento sportivo</option>
                                <option value='Conferenza'>Conferenza</option>
                            </select>
                        </div>
                            
                        <div><label for='descr'>Descrizione dell'evento:</label></div>
                        <div><input type='text' name='descr' <?php if(isset($_POST["descr"])){echo "value=".$_POST["descr"];} ?>></div>

                        <div><label for='data'>Data: </label></div>
                        <div><input type='date' name='data'></div>

                        <div><input type='submit' value="Aggiungi evento" id="submit"> </div>

                    </form>
                </div>

            </section>
        </main>

        <section class="mobileMod">
            <div> DOVE VUOI ANDARE?</div>
            <div><a href="./" <?php if(!isset($_GET['user'])); ?> >Home</a></div>
            <div><a href="add_event.php" class='here'>Aggiungi evento</a></div>
            <div><a href="spotify.php">Spotify</a></div>
            <div><a href="preferiti.php">Preferiti</a></div>
            <div><a href="logout.php">Logout</a></div> 
        </section>

        <footer>
            <p> Eterno Luca - Matricola: O46002092 </p>
        </footer>

    </body>
</html>

<?php mysqli_close($conn); ?>