<?php
session_start();    // démarage de la session
//Include Google Configuration File
include('./gconfig.php');
if(isset($_SESSION["mail"])) echo '<script> alert("Vous êtes déjà connecté.");window.location.replace("./home.php");</script>'; // redirection vers le login si l'utilisateur n'est pas connecté
if(!isset($_SESSION['access_token'])) {
    //Create a URL to obtain user authorization
    $google_login_btn = '<a href="'.$google_client->createAuthUrl().'"><button type="button" id="Google">Se connecter avec Google</button></a>';
} else {

    header("Location: home.php");
}
?>
<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <title>Login - DriveLBR</title>
</head>

<body id="container">
    <img alt="loboLBR" id="logo" src="./images/graphiqueLBR/Plan%20de%20travail%2010LogoFullBlanc.png"/>
    <h1 id="loginPageTitle">DRIVE</h1>
    <form action="actions/login-action.php" id="login-form" method="post">
        <label class="textConnexion" for="email">Identifiant</label><br>
        <input class="loginInputs" type="text" name="email" id="email"/>
        <br><br>
        <label class="textConnexion" for="password">Mot de passe</label><br>
        <input class="loginInputs" type="password" name="password" id="password"/>
        <br>
        <p id="mdpOublie"> Mot de passe oublié ? <a class="mdpOublie" href="./mdpOublie.php">Cliquez ici</a></p>
        <input type="submit" name="Connection" id="Connection" value="Connexion"/>
        <br>
        <?php
            echo $google_login_btn;
        ?>
        <br>
    </form>
</body>

