<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <title>Journal de bord - DriveLBR</title>
</head>
<body>
<div id="header">
    <a href="home.php"> <img alt="logoLBR" id="logo-header" src="images/graphiqueLBR/logoLONGUEURClassic.png"></a>
</div>
<div id="main">
    <?php
    function filesize_formatted($size): string
    {
        $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $power = $size > 0 ? floor(log($size, 1024)) : 0;
        return number_format($size / pow(1024, $power), 2) . ' ' . $units[$power];
    }
    include './menu.php';
    echo getMenu();
    if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("./index.php");</script>';
    if($_SESSION["role"] != 'admin') echo '<script> alert("Vous n`êtes pas autorisé à accéder à cette page.");window.location.replace("./home.php");</script>';
    $link = mysqli_connect("127.0.0.1","root", "" , "drivelbr");
    $link->query('SET NAMES utf8');
    ?>
    <div id="pageContent">
        <h1 class="bigTitle">Journal de bord</h1>
        <?php
        $espace = 0;
        $requete0 = "SELECT `size` FROM `fichiers`";
        $result0 = mysqli_query($link, $requete0);
        while($row = mysqli_fetch_assoc($result0)){
            $espace += $row["size"];
        }
        echo "<h2 class='Espace'>Stockage total utilisé : ".filesize_formatted($espace)."</h2>";

        if(isset($_GET["page"])){
            $page = $_GET["page"]*20;
        }else{
            $page = 0;
        }
        $currentPage = "./journal.php?page=";
        $requete = "SELECT * FROM `tableau_de_bord` ORDER BY `id_modif` DESC LIMIT 20 OFFSET ".intval($page);;
        $result = mysqli_query($link, $requete);
        $data = mysqli_fetch_all($result);
        echo'<a href="';
        if($page <= 0){
            echo $currentPage.'0';
        }else{
            echo $currentPage.($page/20 -1);
        }
        echo'"> Page précédente</a>';
        echo '<a href="';
        if(!isset($data)){
            echo $currentPage.'0';
            $files = [];
        }else{
            if(count($data) < 20){
                echo $currentPage.($page/20);
            }else{
                echo $currentPage.($page/20 +1);
            }
        }
        echo'"> Page suivante </a>';
        ?>
        <table class="journalDeBord">
            <thead>
            <tr>
                <th>Date</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
                foreach($data as $infos){
                    echo "<tr><td id='colonne'>" . $infos[1] . "</td><td id='colonne'>" . $infos[2] . "</td></tr >";
                }

            ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
