<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <title>Création du mot de passe - DriveLBR</title>
</head>

<body>

<?php
    $tmpPsw = $_GET['tmpPsw'];
?>

<div id="container">
    <img id="logo" src="./images/graphiqueLBR/Plan%20de%20travail%2010LogoFullBlanc.png" alt="logoLBR"/>
    <h1 id="loginPageTitle">DRIVE</h1>
    <form action="register-action.php<?php echo '?tmpPsw='.$tmpPsw ?>" id="login-form" method="post">
        <label class="textConnexion" for="email">Email :</label><br>
        <input class="loginInputs" type="text" name="email" id="email" required/>
        <br><br><br>
        <label class="textConnexion" for="password">Créez votre mot de passe :</label><br>
        <input class="loginInputs" type="password" name="password" id="password" required/>
        <br>
        <br>
        <input type="submit" name="Connection" id="Connection" value="Confirmer"/>
        <br>
    </form>
</div>
</body>
