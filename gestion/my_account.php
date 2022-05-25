<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
</head>

<?php
session_start();
//include("connexion.php");
$mail = $_SESSION['mail'];
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;
$requete = "SELECT `role` FROM `utilisateurs` WHERE `mail` = '$mail'";; // Preparing the request to verify the password where the login entered is found on the database
$result = mysqli_query($link, $requete); // Saving the result
$role = "admin";
?>
<body>
    <div id="header">
        <img id="logo-header" src="../images/logoLONGUEURClassic.png">
    </div>


    <div id="main">
        <div id="leftmenu">
            <ul id="menurubrique">
                <li><a href="#">Mon compte</a></li>
                <li><a href="#">Mes fichiers</a></li>
                <ul id="menufichiers">
                    <li><a href="#">Fichier 1</a></li>
                    <li><a href="#">Fichier 2</a></li>
                </ul>
                <li><a href="#">Corbeille</a></li>
                <li><a href="#">Gestion</a></li>
                <li><a href="#">Gérer mon compte</a></li>
                <?php
                    if($role=="lecteur"){
                        echo "<li><a href='#' id='page'>Gérer les comptes</a></li>";
                    }
                    ?>
                <li><a href="#">Gérer les tags</a></li>
                <li><a href="#">Journal de bord</a></li>
            </ul>
        </div>
        <div id="pageContent">
            <h1>Gestion de compte</h1>
            <form>
                <label for="username">Nom : </label>
                <input type="text" id="nom"><br>
                <label for="username">Prénom : </label>
                <input type="text" id="prenom"><br>
                <label for="username">Adresse mail :</label>
                <input type="email" id="mail"><br>
                <label for="username">Rôle :</label>
                <input type="text" id="role"><br>
                <label for="username">Mot de passe :</label>
                <input type="password" id="motdepasse"><br>
                <input type="submit" value="Appliquer les modifications">
            </form>
        </div>
    </div>
</body>


