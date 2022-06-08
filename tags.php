<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <title>Gestion des tags - DriveLBR</title>
</head>

<?php
use Ds\Map;
require 'vendor/autoload.php';
session_start();
if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("./index.html");</script>';
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr");
$link->query('SET NAMES utf8');
$requete = "SELECT `nom_categorie` FROM `categorie`";
$result = mysqli_query($link, $requete);
while($row = mysqli_fetch_array($result)){
    $categorie[] = $row['nom_categorie'];
}
?>
<body>
<div id="header">
    <a href="home.php"> <img alt="logoLBR" id="logo-header" src="images/graphiqueLBR/logoLONGUEURClassic.png"></a>
</div>


<div id="main">
    <?php
    include './menu.php';
    echo getMenu();
    ?>
    <div class="pageContent">
        <h1 class="bigTitle">Gestion des tags</h1>
        <div class="profile">
            <label for="Categories" >Catégories : </label>
            <div class="tagsContainer">

                <?php
                foreach ($categorie as $value1) {
                    echo('<form method="post" action="tags-action.php"><div class="tagsLine">
                            <label for="nom" >'.$value1.'</label>
                            <input class="profile" type="text" id="nomCategorie" name="nomCategorie" value='.$value1.'>
                            <input class="profile" type="submit" id='.$value1.' name="Modifier" value="Modifier">
                            <input class="profile" type="submit" id='.$value1.' name="Supprimer" value="Supprimer">
                            <input hidden type="text" name="categorie" value='.$value1.'>
                        </div></form>');
                }
                ?>
            </div>
            <form method="post" action="tags-action.php">
                <div class="tagsLine">
                    <label for='role'>Nouvelle catégorie :</label>
                    <input class="profile" type="text" id="nomCategorie" name="nomCategorie1" required>
                    <div class="tableContainer">
                        <ul>
                            <?php
                            $counter =0;
                            $requete = "SELECT `nom_tag` FROM `tags` WHERE `nom_categorie` = 'Autre'";
                            $result = mysqli_query($link, $requete);
                            while($row = mysqli_fetch_array($result))
                            {
                                if($counter%2) echo"<li style='list-style: none;' class='role-choices'><label class='redCheckboxContainer'><input type='checkbox' name='listeTag' value =".$row["nom_tag"]."><span class='redCheckbox'></span>".$row["nom_tag"]."</label></li>";
                                else echo"<li style='list-style: none;' class='role-choices-1'><label class='redCheckboxContainer'><input type='checkbox' name='listeTag' value =".$row["nom_tag"]."><span class='redCheckbox'></span>".$row["nom_tag"]."</label></li>";
                                $counter++;
                            }
                            ?>
                        </ul>
                    </div>
                    <input class="profile" type="submit" name="Créer" value="Créer">
                </div>
            </form>
        </div>
    </div>
</div>


<div class="pageContent">
    <h1 class="bigTitle">Tags</h1>
    <div class="profile">

        <?php
        $requete = "SELECT `nom_categorie`,`nom_tag` FROM `tags`";
        $result = mysqli_query($link, $requete);
        $data = mysqli_fetch_all($result);
        $map = new Map;
        foreach ($data as $tag){
            $tempArray = $map->get($tag[0], array());
            $tempArray[] = $tag[1];
            $map->put($tag[0],$tempArray);
        }
        foreach ($map as $key => $value){
            echo '<label for="Categories" > '.$key.' </label><div class="tagsContainer">';
            foreach ($value as $tag) {
                echo '<form method="post" action="tags-action2.php">
                                <div class="tagsLine">
                                    <label for="nom" id="nom">'.$tag.'</label>
                                    <input class="profile" type="text" id="nomCategorie" name="nomTag" value='.$tag.'>
                                    <select class="profile, role-select" id="account" name="categorie">';
                $counter =0;
                $requete = "SELECT `nom_categorie` FROM `tags` GROUP By `nom_categorie`";
                $result = mysqli_query($link, $requete);
                while($row = mysqli_fetch_array($result))
                {
                    if($row["nom_categorie"] == $key && $counter%2) echo '<option class="role-choices" selected>'.$row["nom_categorie"].'</option><br>';
                    else if($row["nom_categorie"] == $key) echo '<option class="role-choices-1" selected>'.$row["nom_categorie"].'</option><br>';
                    else if($counter%2) echo '<option class="role-choices">'.$row["nom_categorie"].'</option><br>';
                    else echo '<option class="role-choices-1">'.$row["nom_categorie"].'</option><br>';
                    $counter++;
                }
                echo '</select>
                <input hidden type="text" name="ancienTag" value='.$tag.'>
                <input hidden type="text" name="ancienneCategorie" value='.$key.'>
                <input class="profile" type="submit" id='.$tag.' name="Modifier" value="Modifier">
                <input class="profile" type="submit" id='.$tag.' name="Supprimer" value="Supprimer">
                </div>
                </form>';
            }
            echo '</div>';
        }
        ?>
        <form method="post" action="tags-action2.php">
            <div class="tagsLine">
                <label for='role'>Nouveau Tag :</label>
                <input class="profile" type="text" id="nomCategorie" name="nomTag">
                <div class="lbrSelect">
                    <select class="profile, role-select" id='account' name='categorie'>
                        <?php
                        $counter =0;
                        $requete = "SELECT `nom_categorie` FROM `tags` GROUP By `nom_categorie`";
                        $result = mysqli_query($link, $requete);
                        while($row = mysqli_fetch_array($result))
                        {
                            if($counter%2) echo '<option class="role-choices" value = '.$row["nom_categorie"].'>'.$row["nom_categorie"].'</option><br>';
                            else echo '<option class="role-choices-1" value= '.$row["nom_categorie"].'>'.$row["nom_categorie"].'</option><br>';
                            $counter++;
                        }
                        ?>
                    </select>
                </div>
                <input class="profile" type="submit" name ="Créer" value="Créer">
            </div>
        </form>
    </div>
</div>
</body>
