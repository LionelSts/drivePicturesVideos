<?php
    session_start();
    if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("./index.html");</script>';
    $link = mysqli_connect("127.0.0.1", "root", "", "drivelbr");
    $link->query('SET NAMES utf8');
    $chaine = urldecode(file_get_contents('php://input'));
    $chaine = str_replace("nomCategorie=", '', $chaine);
    if (isset($_POST["Supprimer"])) {
        $categorie = str_replace("&Supprimer=Supprimer", '', $chaine);
        $requete1 = "UPDATE `tags` SET `nom_categorie`='Autre' WHERE `nom_categorie`='$categorie'";
        $requete2 = "DELETE FROM `categorie` WHERE `nom_categorie`= '$categorie'";
        mysqli_query($link, $requete1);
        mysqli_query($link, $requete2);
        echo '<script> alert("Catégorie supprimée avec succés.");window.location.replace("./tags.php");</script>';
    } else if (isset($_POST["Modifier"])) {
        $categorie = str_replace("&Modifier=Modifier", '', $chaine);
        $categorie_apres = $_POST["nomCategorie"];
        $requete1 = "UPDATE `tags` SET `nom_categorie`='$categorie_apres' WHERE `nom_categorie`='$categorie'";
        $requete2 = "UPDATE `categorie` SET `nom_categorie`='$categorie_apres' WHERE `nom_categorie`='$categorie'";
        mysqli_query($link, $requete1);
        mysqli_query($link, $requete2);
        echo '<script> alert("Catégorie modifiée avec succés.");window.location.replace("./tags.php");</script>';
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
        echo '<script> alert("Catégorie créée avec succés.");window.location.replace("./tags.php");</script>';
    }
?>
