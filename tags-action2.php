<?php
    session_start();
    if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("./index.html");</script>';
    $link = mysqli_connect("127.0.0.1", "root", "", "drivelbr");
    $link->query('SET NAMES utf8');
    $nomTag = $_POST["nomTag"];
    $categorie = $_POST["categorie"];
    if (isset($_POST["Supprimer"])) {
        $requete1 = "UPDATE `caracteriser` SET `nom_tag`='sans tag' WHERE id_fichier IN (SELECT * FROM (SELECT `id_fichier` FROM `caracteriser` WHERE `id_fichier` IN (SELECT `id_fichier` FROM `caracteriser` where `nom_tag`='$nomTag') GROUP BY `id_fichier` HAVING COUNT(`nom_tag`)=1) AS tmp)";
        $requete2 = "DELETE FROM `caracteriser` WHERE `nom_tag`='$nomTag'";
        $requete3 = "DELETE FROM `attribuer` WHERE `nom_tag`='$nomTag'";
        $requete4 = "DELETE FROM `tags` WHERE `nom_tag`='$nomTag'";
        mysqli_query($link, $requete1);
        mysqli_query($link, $requete2);
        mysqli_query($link, $requete3);
        mysqli_query($link, $requete4);
        echo '<script> alert("Tag supprimé avec succés.");window.location.replace("./tags.php");</script>';
    } else if (isset($_POST["Modifier"])) {
        $ancienTag = $_POST["ancienTag"];
        $ancienneCategorie = $_POST["ancienneCategorie"];
        if ($nomTag != $ancienTag && $categorie != $ancienneCategorie) $requete1 = "UPDATE `tags` SET `nom_tag`='$nomTag', `nom_categorie` = '$categorie' WHERE `nom_categorie` = '$ancienneCategorie' AND `nom_tag` =  '$ancienTag'";
        else if ($categorie != $ancienneCategorie) $requete1 = "UPDATE `tags` SET `nom_categorie`='$categorie' WHERE `nom_tag`='$nomTag'";
        else $requete1 = "UPDATE `tags` SET `nom_tag`='$nomTag' WHERE `nom_categorie`='$categorie'";
        mysqli_query($link, $requete1);
        echo '<script> alert("Tag modifié avec succés.");window.location.replace("./tags.php");</script>';
    } else if (isset($_POST["Créer"])) {
        $requete2 = "INSERT INTO `tags` VALUES ('$nomTag','$categorie') ";
        mysqli_query($link, $requete2);
        echo '<script> alert("Tag crée avec succés.");window.location.replace("./tags.php");</script>';
    }
?>
