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
$role = $_SESSION['role'];
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
        <li><a id="title">Mon compte</a></li>
        <?php if($role != "lecture") echo('<li><a href="#" id="page">Mes fichiers</a></li>
        <ul id="menufichiers">
            <li><a href="#" id="page">Fichier 1</a></li>
            <li><a href="#" id="page">Fichier 2</a></li>
        </ul>');?>
        <?php if($role != "lecture") echo('<li><a href="#" id="page">Corbeille</a></li>');?>
        <?php if($role == "admin") echo('<li><a href="#">Gestion des rôles</a></li>');?>
        <li><a href="#" id="page">Gérer mon compte</a></li>
        <?php if($role == "admin") echo('<li><a href="#" id="page">Gérer les comptes</a></li>');?>
        <?php if($role=="ecriture" || $role == "admin") echo('<li><a href="#" id="page">Gérer les tags</a></li>');?>
        <?php if($role == "admin")echo('<li><a href="#" id="page">Journal de bord</a></li>');?>
    </ul>
</div>
</body>


