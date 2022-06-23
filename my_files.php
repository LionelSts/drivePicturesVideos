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
$mail = $_SESSION['mail'];
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
        <h1 class="bigTitle">Mes fichiers</h1>
        <div id="uploadButtonOpen" onclick="openPopup()">
            <img alt="téléverser" src="./images/icons/cloud-computing.png">
            <p>Ouvrir le pop-up</p>
        </div>
        <?php
            include 'filesDisplay.php';
            loadFiles('my_files');
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


