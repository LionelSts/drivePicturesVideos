<?php
    session_start();
    $link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr");
    $link->query('SET NAMES utf8');
    $chaine = urldecode(file_get_contents('php://input'));
    $chaine = str_replace("nomCategorie=",'', $chaine);
    $categorie = str_replace("&Modifier=Modifier",'', $chaine);
    if (isset($_POST["Supprimer"])){
        $requete1 = "UPDATE `tags` SET `nom_categorie`='Autre' WHERE `nom_categorie`='{$categorie}'";
        $requete2 = "DELETE FROM `categorie` WHERE `nom_categorie`= '{$categorie}'";
        mysqli_query($link, $requete1);
        mysqli_query($link, $requete2);
    }
    else if(isset($_POST["Modifier"])){
        $categorie_apres = $_POST["nomCategorie"];
        $requete1 = "UPDATE `tags` SET `nom_categorie`='{$categorie_apres}' WHERE `nom_categorie`='{$categorie}'";
        $requete2 = "UPDATE `categorie` SET `nom_categorie`='{$categorie_apres}' WHERE `nom_categorie`='{$categorie}'";
        mysqli_query($link, $requete1);
        mysqli_query($link, $requete2);
    }
    else if(isset($_POST["Créer"])){
        $nouvelle_categorie = $_POST["nomCategorie1"];
        $requete = "INSERT INTO `categorie` VALUES ('{$nouvelle_categorie}') ";
        mysqli_query($link, $requete);
    }

?>
