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

?>
<body>
<div id="header">
<img id="logo-header" src="images/graphiqueLBR/logoLONGUEURClassic.png">
<input type="text" id="searchbar" placeholder="Barre de recherche"/>
<img src="images/icons/search-logo.png" id="search-logo">
</div>
<?php
    include 'menu.php';
    echo getMenu();
?>


</body>


