<?php
session_start();
if(isset($_SESSION["mail"])) echo '<script> alert("Vous êtes déjà connecté.");window.location.replace("./home.php");</script>';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <title>Login - DriveLBR</title>
</head>

<body>

<div id="container">
        <img alt="loboLBR" id="logo" src="./images/graphiqueLBR/Plan%20de%20travail%2010LogoFullBlanc.png"/>
        <h1 id="loginPageTitle">DRIVE</h1>
<form action="login-action.php" id="login-form" method="post">
    <label class="textConnexion" for="email">Identifiant</label><br>
    <input class="loginInputs" type="text" name="email" id="email" required/>
    <br><br><br>
    <label class="textConnexion" for="password">Mot de passe</label><br>
    <input class="loginInputs" type="password" name="password" id="password" required/>
    <br>
    <br>
    <input type="submit" name="Connection" id="Connection" value="Connexion"/>
    <br>
    <input type="submit" name="Google" id="Google" value="Se connecter avec Google"/>
    <br>
</form>
</div>
</body>

