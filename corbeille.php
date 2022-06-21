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
</div>
<div id="main">
    <?php
    include 'menu.php';
    echo getMenu();
    ?>
    <div id="pageContent">
        <h1 class="bigTitle">Corbeille</h1>
        <?php
        include 'filesDisplay.php';
        loadFiles('corbeille');
        ?>
    </div>
</div>
</body>



