<?php
session_start();// démarage de la session
?>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <title>Home - DriveLBR</title>
</head>

<?php
if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("./index.php");</script>';  // redirection vers le login si l'utilisateur n'est pas connecté
//include("./connexion.php");
$mail = $_SESSION['mail'];  // récupération de l'email de l'utilisateur connecté
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;  // connexion à la base de données
$link->query('SET NAMES utf8');
$requete = "SELECT `role` FROM `utilisateurs` WHERE `mail` = '$mail'";  // recherche dans la bdd, du rôle associé à l'email de l'utilisateur
$result = mysqli_query($link, $requete);

?>
<body>
<div id="header">
<a class="logoTop" href="home.php"><img alt="logoLBR" id="logo-header-home" src="images/graphiqueLBR/logoLONGUEURClassic.png"></a>
<div id="searchbar">
    <input type="text" id="searchInput" placeholder="Barre de recherche"/>
    <img alt="loupe" src="images/icons/search-logo.png" id="search-logo">
</div>

</div>
<?php   // affichage du menu
    include 'menu.php';
    echo getMenu();
?>


</body>


