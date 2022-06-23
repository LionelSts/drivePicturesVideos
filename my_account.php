<?php
session_start();// démarage de la session
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <title>Mon compte - DriveLBR</title>
</head>

<?php
if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("./index.php");</script>';  // redirection vers le login si l'utilisateur n'est pas connecté
?>
<body>
    <div id="header">
        <a href="home.php"> <img alt="logoLBR" id="logo-header" src="images/graphiqueLBR/logoLONGUEURClassic.png"></a>
    </div>
    <div id="main">
        <?php   // insertion du menu
        include './menu.php';
        echo getMenu();
        ?>
        <div class="pageContent">
            <h1 class="bigTitle">Gestion de compte</h1>
            <form class="profile" method="post" action="actions/my_account-action.php">
                <div class="formLine">
                    <label for="nom" >Nom : </label>
                    <input class="profile" type="text" id="nom" name="nom" value='<?php echo $_SESSION["nom"]?>' <?php if($_SESSION['role'] != "admin") echo "disabled" ?> >
                </div>
                <div class="formLine">
                    <label for="prenom">Prénom : </label>
                    <input class="profile" type="text" id="prenom" name="prenom" value='<?php echo $_SESSION["prenom"]?>' <?php if($_SESSION['role'] != "admin") echo "disabled" ?> >
                </div>
                <div class="formLine">
                    <label for="mail">Adresse mail :</label>
                    <input class="profile" type="email" id="mail" name="email" value='<?php echo $_SESSION["mail"]?>' disabled >
                </div>
                <div class="formLine">
                    <label for="motdepasse">Mot de passe :</label>
                    <input class="profile" type="password" id="motdepasse" name="password">
                </div>
                <div class="formLine">
                    <label for='role'>Rôle :</label>
                    <div class="lbrSelect">
                        <select class="profile, role-select" name='role'
                            <?php if($_SESSION['role'] != "admin") echo "disabled" ?>
                        >
                            <option class="role-choices" <?php if($_SESSION['role'] == "invite") echo "selected" ?> value='invite'>invité</option>
                            <option class="role-choices-1" <?php if($_SESSION['role'] == "ecriture") echo "selected" ?> value='ecriture'>ecriture</option>
                            <option class="role-choices" <?php if($_SESSION['role'] == "lecture") echo "selected" ?> value='lecture'>lecture</option>
                            <option class="role-choices-1" <?php if($_SESSION['role'] == "admin") echo "selected" ?> value='admin'>admin</option>
                        </select>
                    </div>
                </div>
                    <input class="profile" type="submit" value="Appliquer les modifications">
            </form>
        </div>
    </div>
</body>


