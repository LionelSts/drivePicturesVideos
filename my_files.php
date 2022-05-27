<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
</head>

<?php
session_start();
//include("./connexion.php");
$mail = $_SESSION['mail'];
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;
$requete = "SELECT `role` FROM `utilisateurs` WHERE `mail` = '$mail'";; // Preparing the request to verify the password where the login entered is found on the database
$result = mysqli_query($link, $requete); // Saving the result

?>

<div id="header">
    <a class="logoTop" href="home.php"><img id="logo-header-home" src="images/graphiqueLBR/logoLONGUEURClassic.png"></a>
    <div id="searchbar">
        <input type="text" id="searchInput" placeholder="Barre de recherche"/>
        <img src="images/icons/search-logo.png" id="search-logo">
    </div>

</div>
<div id="main">
    <?php
    include 'menu.php';
    echo getMenu();
    ?>
    <div id="pageContent">
        <h1 class="bigTitle">Récents</h1>
        <?php
        include 'upload-popup.php';
        ?>
    </div>
</div>


