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
            include './menu.php';
            echo getMenu();
            if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("./index.php");</script>';
            $link = mysqli_connect("127.0.0.1","root", "" , "drivelbr");
            $link->query('SET NAMES utf8');
            ?>
            <div id="pageContent">
                <h1 class="bigTitle">Journal de bord</h1>
                <table class="journalDeBord">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $requete = "SELECT * FROM `tableau_de_bord` ORDER BY `id_modif` DESC";
                            $result = mysqli_query($link, $requete);
                            while($row = mysqli_fetch_assoc($result)){
                                echo "<tr><td id='colonne'>" . $row["date"] . "</td><td id='colonne'>" . $row["modification"] . "</td></tr >";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
