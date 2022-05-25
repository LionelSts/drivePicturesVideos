<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
</head>

<?php
session_start();
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;
?>
<body>
    <div id="header">
        <a href="home.php"> <img  id="logo-header" src="images/graphiqueLBR/logoLONGUEURClassic.png"></a>
    </div>


    <div id="main">
        <?php
        include './menu.php';
        echo getMenu();
        ?>
        </div>
        <div id="pageContent">
            <h1 class="bigTitle">Gestion de compte</h1>
            <form class="profile">
                <label class="profile" for="nom">Nom : </label>
                <input class="profile" type="text" id="nom"><br>
                <label class="profile" for="prenom">Prénom : </label>
                <input class="profile" type="text" id="prenom"><br>
                <label class="profile" for="mail">Adresse mail :</label>
                <input class="profile" type="email" id="mail"><br>
                <label class="profile" for="role">Rôle :</label>
                <select name="role" id="role-select">
                    <option value="dog">invité</option>
                    <option value="cat">ecriture</option>
                    <option value="hamster">lecture</option>
                    <option selected value="parrot">admin</option>
                </select><br>
                <label for="username">Mot de passe :</label>
                <input class="profile" type="password" id="motdepasse"><br>
                <input class="profile" type="submit" value="Appliquer les modifications">
            </form>
        </div>
    </div>
</body>


