<?php
    session_start();
    if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("../index.php");</script>';
    $link = mysqli_connect("127.0.0.1", "root", "", "drivelbr");
    $link->query('SET NAMES utf8');
    $chaine = urldecode(file_get_contents('php://input'));
    $chaine = str_replace("nomCategorie=", '', $chaine);
    $name = $_SESSION["prenom"]; $lastname = $_SESSION["nom"]; $role = $_SESSION["role"];
    if (isset($_POST["Supprimer"])) {
        $categorie = $_POST["categorie"];
        $requete1 = "UPDATE `tags` SET `nom_categorie`='Autre' WHERE `nom_categorie`='$categorie'";
        $requete2 = "DELETE FROM `categorie` WHERE `nom_categorie`= '$categorie'";
        $requete3 = "INSERT INTO `tableau_de_bord` (`modification`) VALUES ('Compte ".$lastname."  ".$name." (".$role.") a supprimé la catégorie ".$categorie."')";
        mysqli_query($link, $requete1); mysqli_query($link, $requete2); mysqli_query($link, $requete3);
        echo '<script> alert("Catégorie supprimée avec succès.");window.location.replace("../tags.php");</script>';
    } else if (isset($_POST["Modifier"])) {
        $categorie = $_POST["categorie"];
        $categorie_apres = $_POST["nomCategorie"];
        $requete1 = "UPDATE `tags` SET `nom_categorie`='$categorie_apres' WHERE `nom_categorie`='$categorie'";
        $requete2 = "UPDATE `categorie` SET `nom_categorie`='$categorie_apres' WHERE `nom_categorie`='$categorie'";
        $requete3 = "INSERT INTO `tableau_de_bord` (`modification`) VALUES ('Compte ".$lastname."  ".$name." (".$role.") a renommé la catégorie ".$categorie." en ".$categorie_apres."')";
        mysqli_query($link, $requete1); mysqli_query($link, $requete2); mysqli_query($link, $requete3);
        echo '<script> alert("Catégorie modifiée avec succès.");window.location.replace("../tags.php");</script>';
    } else if (isset($_POST["Créer"])) {
        $nouvelle_categorie = $_POST["nomCategorie1"];
        if (isset($_POST['listeTag'])) {
            $chaine = str_replace("nomCategorie1=" . $nouvelle_categorie, '', $chaine);
            $chaine = str_replace("&listeTag=", ' ', $chaine);
            $chaine = str_replace("&Créer=Créer", '', $chaine);
            $tab = explode(" ", $chaine);
            for ($i = 1; $i <= count($tab) - 1; $i++) {
                $requete1 = "UPDATE `tags` SET `nom_categorie`= '$nouvelle_categorie' WHERE `nom_tag`='$tab[$i]'";
                mysqli_query($link, $requete1);
            }
            $requete = "INSERT INTO `categorie` (`nom_categorie`) VALUES ('$nouvelle_categorie')";
            mysqli_query($link, $requete);
        } else {
            $requete = "INSERT INTO `categorie` (`nom_categorie`) VALUES ('$nouvelle_categorie') ";
            mysqli_query($link, $requete);
        }
        $requete2 = "INSERT INTO `tableau_de_bord` (`modification`) VALUES ('Compte ".$lastname."  ".$name." (".$role.") a ajouté la catégorie ".$nouvelle_categorie."')";
        mysqli_query($link, $requete2);
        echo '<script> alert("Catégorie créée avec succès.");window.location.replace("../tags.php");</script>';
    }
?>
