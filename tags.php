<?php
session_start();// démarrage de la session
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <title>Gestion des tags - DriveLBR</title>
    <link data-n-head="1" rel="icon" type="image/x-icon" href="./images/icons/favicon.ico">
</head>

<?php
use Ds\Map;
require 'vendor/autoload.php';
if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("./index.php");</script>';
if($_SESSION["role"] != 'admin' && $_SESSION["role"] != 'ecriture') echo '<script> alert("Vous n`êtes pas autorisé à accéder à cette page.");window.location.replace("./home.php");</script>';
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr");
$link->query('SET NAMES utf8');
$requete = "SELECT `nom_categorie` FROM `categorie`";
$result = mysqli_query($link, $requete);
$categorie = [];
while($row = mysqli_fetch_array($result)){
    $categorie[] = $row['nom_categorie'];
}                                                                                                                       // on récupère tous les noms de catégories
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
        <div id="pageContent">
            <h1 class="bigTitle">Gestion des tags</h1>
                <h2 class="mediumTitle">Catégories : </h2>
                <div class="tagsContainer">
                    <?php
                    foreach ($categorie as $value1) {                                                                   // On affiche les catégories sauf Autre
                        if($value1 != "Autre"){
                            $value1 = htmlspecialchars($value1, ENT_QUOTES, 'UTF-8');
                        echo('<form class="profile" method="post" action="actions/tags-action.php">
                                <div class="tagsLine">
                                    <label for="nom" >' .$value1.'</label>
                                    <input type="text" id="nomCategorie" name="nomCategorie" value="'.$value1.'">
                                    <input type="submit" id="'.$value1.'" name="Modifier" value="Modifier">
                                    <input type="submit" id="'.$value1.'" name="Supprimer" value="Supprimer">
                                    <input hidden type="text" name="categorie" value="'.$value1.'">
                                </div>
                            </form>');
                        }
                    }
                    ?>
                </div>
                <form class="profile" method="post" action="actions/tags-action.php">
                    <div class="tagsLine">
                        <label for='role'>Nouvelle catégorie :</label>
                        <label for="nomCategorie"></label><input type="text" id="nomCategorie" name="nomCategorie1" required>
                        <div class="autreTagsContainer">
                            <?php
                            $counter =0;
                            $requete = "SELECT `nom_tag` FROM `tags` WHERE `nom_categorie` = 'Autre'";                  // On récupère tous les tags de la catégorie Autre
                            $result = mysqli_query($link, $requete);
                            while($row = mysqli_fetch_array($result))                                                   // Et on les affiche
                            {
                                if($row["nom_tag"] != "Sans tag") {
                                    $tag = htmlspecialchars($row["nom_tag"], ENT_QUOTES, 'UTF-8');
                                    if ($counter % 2) {
                                        echo "<div class='tag-choices'>
                                                <label class='redCheckboxContainer'>" . $tag . "
                                                    <input type='checkbox' name='listeTag' value ='" . $tag . "'>
                                                    <span class='tagCheckbox redCheckbox'></span>
                                                </label>
                                             </div>";
                                    } else {
                                        echo "<div class='tag-choices-1'>
                                                <label class='redCheckboxContainer'>" . $tag . "
                                                    <input type='checkbox' name='listeTag' value ='" . $tag . "'>
                                                    <span class='tagCheckbox redCheckbox'></span>
                                                </label>
                                             </div>";
                                    }
                                    $counter++;
                                }
                            }
                            ?>
                        </div>
                        <input type="submit" name="Créer" value="Créer">
                    </div>
                </form>
            <h1 class="bigTitle">Tags</h1>
            <div>
                <?php
                $requete = "SELECT `nom_categorie`,`nom_tag` FROM `tags`";                                              // On récupère tous les tags associés à leur catégorie
                $result = mysqli_query($link, $requete);
                $data = mysqli_fetch_all($result);
                $map = new Map;
                foreach ($data as $tag){
                    $tempArray = $map->get($tag[0], array());
                    $tempArray[] = $tag[1];
                    $map->put($tag[0],$tempArray);
                }
                foreach ($map as $key => $value){
                    echo '<h2 class="mediumTitle"> '.$key.' </h2><div class="tagsContainer">';                          // Nom de la catégorie
                    foreach ($value as $tag) {                                                                          // Pour chaque tags de cette catégorie (sauf sans tag)
                        $tag = htmlspecialchars($tag, ENT_QUOTES, 'UTF-8');
                        if ($tag != "Sans tag") {
                            echo '<form  class="profile" method="post" action="actions/tags-action2.php">
                                <div class="tagsLine">
                                    <label for="nom" id="nom">' . $tag . '</label>
                                    <input type="text" id="nomCategorie" name="nomTag" value="' . $tag . '">
                                    <select class="tag-select role-select" id="account" name="categorie">';
                            $counter = 0;                                                                               // On charge les catégories pour pouvoir les déplacer
                            foreach ($categorie as $categ) {
                                $categ = htmlspecialchars($categ, ENT_QUOTES, 'UTF-8');
                                if ($categ == $key && $counter % 2) echo '<option class="role-choices" selected>' .$categ. '</option>';
                                else if ($categ == $key) echo '<option class="role-choices-1" selected>' .$categ. '</option>';
                                else if ($counter % 2) echo '<option class="role-choices">' .$categ. '</option>';
                                else echo '<option class="role-choices-1">' .$categ. '</option>';
                                $counter++;
                            }
                            echo '      </select>
                                    <input hidden type="text" name="ancienTag" value="' . $tag . '">
                                    <input hidden type="text" name="ancienneCategorie" value="' . $key . '">
                                    <input type="submit" id="' . $tag . '" name="Modifier" value="Modifier">
                                    <input type="submit" id="' . $tag . '" name="Supprimer" value="Supprimer">
                                </div>
                            </form>';
                        }
                    }
                    echo '</div>';
                }
                ?>
                <form class="profile" method="post" action="actions/tags-action2.php">
                    <div class="tagsLine">
                        <label for='role'>Nouveau Tag :</label>
                        <input type="text" id="nomCategorie" name="nomTag">
                        <label for='account'></label>
                        <select class="tag-select role-select" id='account' name='categorie'>
                                <?php
                                $counter =0;                                                                            // On affiche toutes les catégories pour choisir dans laquelle on met notre tag créé
                                foreach ($categorie as $categ){
                                    $categ = htmlspecialchars($categ, ENT_QUOTES, 'UTF-8');
                                    if($counter%2) echo '<option class="role-choices" value ="'.$categ.'">'.$categ.'</option>';
                                    else echo '<option class="role-choices-1" value="'.$categ.'">'.$categ.'</option>';
                                    $counter++;
                                }
                                ?>
                            </select>
                        <input type="submit" name ="Créer" value="Créer">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
