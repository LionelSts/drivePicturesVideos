<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <title>Login - DriveLBR</title>
    <link data-n-head="1" rel="icon" type="image/x-icon" href="./images/icons/favicon.ico">
</head>

<body>
<div id="container">
    <img alt="loboLBR" id="logo" src="./images/graphiqueLBR/Plan%20de%20travail%2010LogoFullBlanc.png"/>
    <h1 id="loginPageTitle">DRIVE</h1>
    <form action="./actions/mdpOublie-action.php" id="login-form" method="post">
        <label class="textConnexion" for="email">Email</label><br>
        <input class="loginInputs" type="text" name="email" id="email" required/>
        <br><br><br>
        <input type="submit" name="reset" id="Connection" value="Réinitialiser"/>
        <br><br>
    </form>
</div>
</body>
