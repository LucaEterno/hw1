<?php
    require_once 'auth.php';
    if (!$userid = checkAuth()) {
        header("Location: login.php");
        exit;
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
        <title>Ewind - preferiti</title>

        <link rel='stylesheet' href='style/preferiti.css'>
        <script src='scripts/preferiti.js' defer></script>

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
                    <a href="add_event.php">Aggiungi evento</a>
                    <a href="spotify.php">Spotify</a>
                    <a href="preferiti.php" class='here'>Preferiti</a>
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
            <div class='column'>
                <h1>I tuoi brani preferiti:</h1>
                <div class='preferiti'>

                </div>
            </div>
        </main>

        <section class="mobileMod">
            <div> DOVE VUOI ANDARE?</div>
            <div><a href="./" <?php if(!isset($_GET['user'])); ?> >Home</a></div>
            <div><a href="add_event.php">Aggiungi evento</a></div>
            <div><a href="spotify.php">Spotify</a></div>
            <div><a href="preferiti.php" class='here'>Preferiti</a></div>
            <div><a href="logout.php">Logout</a></div> 
        </section>

        <footer>
            <p> Eterno Luca - Matricola: O46002092 </p>
        </footer>

    </body>
</html>

<?php mysqli_close($conn); ?>