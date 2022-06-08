<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <title>Home - DriveLBR</title>
</head>

<?php
session_start();
if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("./index.html");</script>';
//include("./connexion.php");
$mail = $_SESSION['mail'];
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;
$requete = "SELECT `role` FROM `utilisateurs` WHERE `mail` = '$mail'"; // Preparing the request to verify the password where the login entered is found on the database
$result = mysqli_query($link, $requete); // Saving the result

?>
<body>
<div id="header">
<a class="logoTop" href="home.php"><img alt="logoLBR" id="logo-header-home" src="images/graphiqueLBR/logoLONGUEURClassic.png"></a>
<div id="searchbar">
    <input type="text" id="searchInput" placeholder="Barre de recherche"/>
    <img alt="loupe" src="images/icons/search-logo.png" id="search-logo">
</div>

</div>
<?php
    include 'menu.php';
    echo getMenu();
?>


</body>


