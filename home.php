<?php
session_start();// démarrage de la session
?>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <title>Home - DriveLBR</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<?php
if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("./index.php");</script>';  // redirection vers le login si l'utilisateur n'est pas connecté
$mail = $_SESSION['mail'];  // récupération de l'email de l'utilisateur connecté
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;  // connexion à la base de données
$link->query('SET NAMES utf8');
$searchArray = [];
if(isset($_GET)){
    foreach ($_GET as $key => $parameter){
        if($key != 'page' && $key != 'submit'){
            if(str_contains($key,"extension")){
                $searchArray['extensions'][] = substr(urldecode($key), strpos($key, '-')+1);
            }else{
                $searchArray['tags'][] = substr(str_replace('_', ' ', $key), strpos($key, '-')+1);
            }
        }
    }
}
?>
<body>
    <div id="header">
        <a class="logoTop" href="home.php"><img alt="logoLBR" id="logo-header-home" src="images/graphiqueLBR/logoLONGUEURClassic.png"></a>
        <?php if($_SESSION['role'] != 'invite'){
        echo '<div id="searchbar" onclick="searchPopUp()">
            <label for="searchInput"></label><input type="text" id="searchInput" placeholder="Barre de recherche"/>';
             include 'searchPopUp.php';
            echo '</div>';
        }?>
    </div>
    <div id="main">
        <?php   // affichage du menu
            include 'menu.php';
            echo getMenu();
        ?>
        <div id="pageContent">
        <?php
        include 'filesDisplay.php';
        loadFiles('home', $searchArray);
        ?>
        </div>
    </div>
    <script>
        let searchSelector =  document.getElementById('searchPopUpContainer');
        let searchPopUp = () => {
            searchSelector.style.display = 'block';
        }
        let closeSearchPopUp = () => {
            searchSelector.style.display = 'none';
        }
    </script>
</body>


