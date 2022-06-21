<?php
session_start();// démarage de la session
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <title>Mes fichiers - DriveLBR</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<?php
if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("./index.php");</script>';
if($_SESSION["role"] == 'lecture') echo '<script> alert("Vous n`êtes pas autorisé à accéder à cette page.");window.location.replace("./home.php");</script>';
//include("./connexion.php");
$mail = $_SESSION['mail'];
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr");
$link->query('SET NAMES utf8');
$requete = "SELECT `role` FROM `utilisateurs` WHERE `mail` = '$mail'"; // Preparing the request to verify the password where the login entered is found on the database
$result = mysqli_query($link, $requete); // Saving the result
?>
<body>
<div id="header">
    <a class="logoTop" href="home.php"><img alt="logoLBR" id="logo-header-home" src="images/graphiqueLBR/logoLONGUEURClassic.png"></a>
    <div id="searchbar">
        <label for="searchInput"></label><input type="text" id="searchInput" placeholder="Barre de recherche"/>
        <img alt="Loupe" src="images/icons/search-logo.png" id="search-logo">
    </div>
</div>
<div id="main">
    <?php
        include 'menu.php';
        echo getMenu();
    ?>
    <div id="pageContent">
        <h1 class="bigTitle">Mes fichiers</h1>
        <div id="uploadButtonOpen" onclick="openPopup()">
            <img alt="téléverser" src="./images/icons/cloud-computing.png">
            <p>Ouvrir le pop-up</p>
        </div>
        <?php
            include 'filesDisplay.php';
            loadFiles(1);
        ?>
        <?php
            include 'upload-popup.php';
        ?>
    </div>
</div>
</body>
<script>
    function openPopup(){
        document.getElementById("uploadPopUp").style.display = "table-row";
    }
</script>


