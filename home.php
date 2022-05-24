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
//$role = serialize($re);
//echo $role;
?>
<body>
<div id="header">
<img id="logo-header" src="./images/logoLONGUEURClassic.png">
<input type="text" id="searchbar" placeholder="Barre de recherche"/>
<img src="./images/search-logo.png" id="search-logo">
</div>


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
        <li><a href="#">Gérer les comptes</a></li>
        <li><a href="#">Gérer les tags</a></li>
        <li><a href="#">Journal de bord</a></li>
    </ul>

</div>
</body>


