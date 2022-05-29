<?php
    require_once 'auth.php';

    if (checkAuth()) {
        header("Location: home.php");
        exit;
    }
?>

<?php
    if (!empty($_POST["username"]) && !empty($_POST["email"])  && !empty($_POST["password"]) && !empty($_POST["confirm_password"]))
    {
        $error = array();
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));

        # USERNAME
        if(!preg_match('/^[a-zA-Z0-9_]{1,15}$/', $_POST['username'])) {
            $error[] = "Username non valido";
        } else {
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $query = "SELECT username FROM users WHERE username = '$username'";
            $res = mysqli_query($conn, $query);
            if (mysqli_num_rows($res) > 0) {
                $error[] = "Username già utilizzato";
            }
        }

        # PASSWORD
        if (strlen($_POST["password"]) < 8) {
            $error[] = "Caratteri password insufficienti";
        } else if ((preg_match('/^[A-Z]$/', $_POST['password'])) && (preg_match('/^[a-z]$/', $_POST['password'])) && (preg_match('/^[0-9]$/', $_POST['password']))){
            $error[] = "Mancanza di 1 carattere minuscolo, maiuscolo o un numero";
        }

        # CONFERMA PASSWORD
        if (strcmp($_POST["password"], $_POST["confirm_password"]) != 0) {
            $error[] = "Le password non coincidono";
        }

        # EMAIL
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $error[] = "Email non valida";
        } else {
            $email = mysqli_real_escape_string($conn, strtolower($_POST['email']));
            $res = mysqli_query($conn, "SELECT email FROM users WHERE email = '$email'");
            if (mysqli_num_rows($res) > 0) {
                $error[] = "Email già utilizzata";
            }
        }

        # REGISTRAZIONE NEL DATABASE
        if (count($error) == 0) {
            $atype = mysqli_real_escape_string($conn, $_POST['type']);
            $propic = mysqli_real_escape_string($conn, $_POST['rpicture']);

            $password = mysqli_real_escape_string($conn, $_POST['password']);
            $password = password_hash($password, PASSWORD_BCRYPT);

            $query = "INSERT INTO users(username, atype, password, email, propic) VALUES('$username', '$atype', '$password', '$email', '$propic')";
            
            if (mysqli_query($conn, $query)) {
                $_SESSION["_ewind_username"] = $_POST["username"];
                $_SESSION["_ewind_user_id"] = mysqli_insert_id($conn);
                mysqli_close($conn);
                header("Location: home.php");
                exit;
            } else {
                $error[] = "Errore di connessione al Database";
            }
        }

        mysqli_close($conn);
    }
    /*
    else if (isset($_POST["username"])) {
        $error = array("Riempi tutti i campi");
    }
    */
?>

<html>
    <head>
        <title>Ewind - Signup</title>
        
        <link rel='stylesheet' href='style/login_signup.css'>
        <script src='scripts/signup.js' defer></script>

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="assets/e.png">
        <meta charset="utf-8">
    </head>

    <body>
        <main>

        <div class="logo_esterno">
            <div class="logo_interno">
                <img src="assets/ee.png"/>
            </div>
        </div>

        <section>
            <h1>REGISTRATI</h1>

            <form name='signup' method='post'>
            
                <div class="username">
                    <div><label for='username'>Nome utente</label></div>
                    <div><input type='text' name='username' <?php if(isset($_POST["username"])){echo "value=".$_POST["username"];} ?>></div>
                    <span>Nome utente non disponibile</span>
                </div>

                <div class="type">
                    <div><label for='type'>Tipologia account</label></div>
                    <select name='type' id='type'>
                        <option value='privato' selected>Utente privato</option>
                        <option value='gruppo'>Gruppo</option>
                        <option value='azienda'>Azienda</option>
                        <option value='istruzione'>Ente scolastico</option>
                    </select>
                </div>

                <div class="email">
                    <div><label for='email'>Email</label></div>
                    <div><input type='text' name='email' <?php if(isset($_POST["email"])){echo "value=".$_POST["email"];} ?>></div>
                    <span>Indirizzo email non valido</span>
                </div>

                <div class="password">
                    <div><label for='password'>Password</label></div>
                    <div><input type='password' name='password' <?php if(isset($_POST["password"])){echo "value=".$_POST["password"];} ?>></div>
                    <span>Inserisci almeno 8 caratteri</span>
                </div>

                <div class="confirm_password">
                    <div><label for='confirm_password'>Conferma Password</label></div>
                    <div><input type='password' name='confirm_password' <?php if(isset($_POST["confirm_password"])){echo "value=".$_POST["confirm_password"];} ?>></div>
                    <span>Le password non coincidono</span>
                </div>

                <div class="profilepicture">
                    <div><label for='rpicture'>Scegli un'immagine profilo</label></div>
                    <div class="choice-grid">
                        <div>
                            <img src="images/image1.jpg"/>
                            <input type='radio' name='rpicture' value='images/image1.jpg' checked>
                        </div>
                        <div>  
                            <img src="images/image2.jpg"/>
                            <input type='radio' name='rpicture' value='images/image2.jpg'>
                        </div>
                        <div>
                            <img src="images/image3.jpg"/>
                            <input type='radio' name='rpicture' value='images/image3.jpg'>
                        </div>
                        <div>  
                            <img src="images/image4.jpg"/>
                            <input type='radio' name='rpicture' value='images/image4.jpg'>
                        </div>
                    </div>
                </div>

                <div class="submit">
                    <input type='submit' value="Registrati" id="submit" disabled>
                </div>

            </form>

            <div class="signup">Hai già un account? <a href="login.php">Accedi</a>

        </section>
        </main>
    </body>
</html>