<?php
    include 'auth.php';
    if (checkAuth()) {
        header('Location: home.php');
        exit;
    }
?>

<?php
    if (!empty($_POST["username"]) && !empty($_POST["password"]) ) {
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));

        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        $query = "SELECT id, username, password FROM users WHERE username = '$username'";

        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));;
        if (mysqli_num_rows($res) > 0) {
            $entry = mysqli_fetch_assoc($res);
            if (password_verify($_POST['password'], $entry['password'])) {
                $_SESSION["_ewind_username"] = $entry['username'];
                $_SESSION["_ewind_user_id"] = $entry['id'];
                header("Location: home.php");
                mysqli_free_result($res);
                mysqli_close($conn);
                exit;
            }
        }
        $error = "Username e/o password errati.";

    } else if (isset($_POST["username"]) || isset($_POST["password"])) {
        $error = "Inserisci username e password.";
    }
?>

<html>
    <head>
        <title>Ewind - Login</title>

        <link rel='stylesheet' href='style/login_signup.css'>
        <link rel="icon" type="image/png" href="assets/e.png">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
    </head>

    <body class="login">
        <main class="login">

        <div class="logo_esterno">
            <div class="logo_interno">
                <img src="assets/ee.png"/>
            </div>
        </div>

        <section>
            <h1>ACCEDI</h1>

            <?php
                if (isset($error)) {
                    echo "<span class='error'>$error</span>";
                }
            ?>

            <form name='login' method='post'>
                <div class="username">
                    <div><label for='username'>Nome utente</label></div>
                    <div><input type='text' name='username' <?php if(isset($_POST["username"])){echo "value=".$_POST["username"];} ?>></div>
                </div>

                <div class="password">
                    <div><label for='password'>Password</label></div>
                    <div><input type='password' name='password' <?php if(isset($_POST["password"])){echo "value=".$_POST["password"];} ?>></div>
                </div>

                <div>
                    <input type='submit' value="Accedi">
                </div>
            </form>
            
            <div class="signup">Non hai ancora un account? <a href="signup.php">Registrati</a>

        </section>
        </main>
    </body>
</html>