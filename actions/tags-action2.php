<?php
    session_start();
    if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("../index.php");</script>';
    $link = mysqli_connect("127.0.0.1", "root", "", "drivelbr");
    $name = $_SESSION["prenom"]; $lastname = $_SESSION["nom"]; $role = $_SESSION["role"];
    $link->query('SET NAMES utf8');
    $nomTag = $_POST["nomTag"];
    $categorie = $_POST["categorie"];
    if (isset($_POST["Supprimer"])) {
        $requete1 = "UPDATE `caracteriser` SET `nom_tag`='sans tag' WHERE id_fichier IN (SELECT * FROM (SELECT `id_fichier` FROM `caracteriser` WHERE `id_fichier` IN (SELECT `id_fichier` FROM `caracteriser` where `nom_tag`='$nomTag') GROUP BY `id_fichier` HAVING COUNT(`nom_tag`)=1) AS tmp)";
        $requete2 = "DELETE FROM `caracteriser` WHERE `nom_tag`='$nomTag'";
        $requete3 = "DELETE FROM `attribuer` WHERE `nom_tag`='$nomTag'";
        $requete4 = "DELETE FROM `tags` WHERE `nom_tag`='$nomTag'";
        $requete5 = "INSERT INTO tableau_de_bord (modification) VALUES ('Compte ".$lastname."  ".$name." (".$role.") a supprimé le tag ".$nomTag."')";
        mysqli_query($link, $requete1);
        mysqli_query($link, $requete2);
        mysqli_query($link, $requete3);
        mysqli_query($link, $requete4);
        mysqli_query($link, $requete5);
        echo '<script> alert("Tag supprimé avec succès.");window.location.replace("../tags.php");</script>';
    } else if (isset($_POST["Modifier"])) {
        $ancienTag = $_POST["ancienTag"];
        $ancienneCategorie = $_POST["ancienneCategorie"];
        if ($nomTag != $ancienTag && $categorie != $ancienneCategorie) {
            $requete1 = "UPDATE `tags` SET `nom_tag`='$nomTag', `nom_categorie` = '$categorie' WHERE `nom_categorie` = '$ancienneCategorie' AND `nom_tag` =  '$ancienTag'";
            $requete2 = "INSERT INTO `tableau_de_bord` (`modification`) VALUES ('Compte ".$lastname."  ".$name." (".$role.") a renommé le tag ".$ancienTag." en ".$nomTag." et a changé sa catégorie de ".$ancienneCategorie." à ".$categorie."')";
        }
        else if ($categorie != $ancienneCategorie) {
            $requete1 = "UPDATE `tags` SET `nom_categorie`='$categorie' WHERE `nom_tag`='$nomTag'";
            $requete2 = "INSERT INTO `tableau_de_bord` (`modification`) VALUES ('Compte ".$lastname."  ".$name." (".$role.") a changé de catégorie le tag ".$nomTag." de ".$ancienneCategorie." à ".$categorie."')";
        }
        else {
            $requete1 = "UPDATE `tags` SET `nom_tag`='$nomTag' WHERE `nom_categorie`='$categorie'";
            $requete2 = "INSERT INTO `tableau_de_bord` (`modification`) VALUES ('Compte ".$lastname."  ".$name." (".$role.") a renommé le tag ".$ancienTag." en ".$nomTag."')";
        }
        mysqli_query($link, $requete1); mysqli_query($link, $requete2);
        echo '<script> alert("Tag modifié avec succès.");window.location.replace("../tags.php");</script>';
    } else if (isset($_POST["Créer"])) {
        $requete2 = "INSERT INTO `tags` VALUES ('$nomTag','$categorie') ";
        $requete1 = "INSERT INTO tableau_de_bord (modification) VALUES ('Compte ".$lastname."  ".$name." (".$role.") a ajouté un tag ".$nomTag." dans la catégorie ".$categorie."')";
        mysqli_query($link, $requete2); mysqli_query($link, $requete1);
        echo '<script> alert("Tag crée avec succès.");window.location.replace("../tags.php");</script>';
    }
