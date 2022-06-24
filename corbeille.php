<?php
session_start();// démarrage de la session
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <title>Mes fichiers - DriveLBR</title>
    <link data-n-head="1" rel="icon" type="image/x-icon" href="./images/icons/favicon.ico">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<?php
if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("./index.php");</script>';

if($_SESSION["role"] == 'lecture') echo '<script> alert("Vous n`êtes pas autorisé à accéder à cette page.");window.location.replace("./home.php");</script>';

// fichier qui vérifie que les fichiers ne sont pas trop vieux (30jours)
include './actions/checkCorbeille-action.php';
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



