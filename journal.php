<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="style.css">
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
            $link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr");
            ?>
            <div id="pageContent">
                <h1 class="bigTitle">Journal de bord</h1>
            </div>
            <div id="pageContent">
                <table id="journal" border='1' align='center' cellpadding='5'>
                    <tr>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    <?php
                        $requete = "SELECT * FROM `tableau_de_bord` ORDER BY `id_modif`";
                        $result = mysqli_query($link, $requete);
                        while($row = mysqli_fetch_assoc($result)){
                            echo "<tr><td id='colonne'>" . $row["date"] . "</td><td id='colonne'>" . $row["modification"] . "</td></tr >";
                        }
                    ?>
                </table>
            </div>
        </div>
    </body>
</html>
