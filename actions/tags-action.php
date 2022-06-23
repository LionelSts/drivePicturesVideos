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
        $requete = "UPDATE `tags` SET `nom_categorie`='Autre' WHERE `nom_categorie`=?";
        $stmt = $link->prepare($requete);
        $stmt->bind_param("s", $categorie);
        $stmt->execute();
        $requete = "DELETE FROM `categorie` WHERE `nom_categorie`= ?";
        $stmt = $link->prepare($requete);
        $stmt->bind_param("s", $categorie);
        $stmt->execute();
        $requete = "INSERT INTO `tableau_de_bord` (`modification`) VALUES (CONCAT('Compte ',?,'  ',?,' (',?,') a supprimé la catégorie ',?))";
        $stmt = $link->prepare($requete);
        $stmt->bind_param("ssss", $lastname,$name,$role,$categorie);
        $stmt->execute();
        echo '<script> alert("Catégorie supprimée avec succès.");window.location.replace("../tags.php");</script>';
    } else if (isset($_POST["Modifier"])) {
        $categorie = $_POST["categorie"];
        $categorie_apres = $_POST["nomCategorie"];
        $requete = "UPDATE `tags` SET `nom_categorie`='$categorie_apres' WHERE `nom_categorie`=?";
        $stmt = $link->prepare($requete);
        $stmt->bind_param("s", $categorie);
        $stmt->execute();
        $requete = "UPDATE `categorie` SET `nom_categorie`='$categorie_apres' WHERE `nom_categorie`=?";
        $stmt = $link->prepare($requete);
        $stmt->bind_param("s", $categorie);
        $stmt->execute();
        $requete = "INSERT INTO `tableau_de_bord` (`modification`) VALUES (CONCAT('Compte ',?,'  ',?,' (',?,') a renommé la catégorie ',?,' en ', ?))";
        $stmt = $link->prepare($requete);
        $stmt->bind_param("sssss", $lastname,$name,$role,$categorie,$categorie_apres);
        $stmt->execute();
        echo '<script> alert("Catégorie modifiée avec succès.");window.location.replace("../tags.php");</script>';
    } else if (isset($_POST["Créer"])) {
        $nouvelle_categorie = $_POST["nomCategorie1"];
        if (isset($_POST['listeTag'])) {
            $chaine = str_replace("nomCategorie1=" . $nouvelle_categorie, '', $chaine);
            $chaine = str_replace("&listeTag=", ' ', $chaine);
            $chaine = str_replace("&Créer=Créer", '', $chaine);
            $tab = explode(" ", $chaine);
            for ($i = 1; $i <= count($tab) - 1; $i++) {
                $requete = "UPDATE `tags` SET `nom_categorie`= '$nouvelle_categorie' WHERE `nom_tag`=?";
                $stmt = $link->prepare($requete);
                $stmt->bind_param("s", $tab[$i]);
                $stmt->execute();
            }
            $requete = "INSERT INTO `categorie` (`nom_categorie`) VALUES (?)";
            $stmt = $link->prepare($requete);
            $stmt->bind_param("s", $nouvelle_categorie);
            $stmt->execute();
        } else {
            $requete = "INSERT INTO `categorie` (`nom_categorie`) VALUES (?) ";
            $stmt = $link->prepare($requete);
            $stmt->bind_param("s", $nouvelle_categorie);
            $stmt->execute();
        }
        $requete = "INSERT INTO `tableau_de_bord` (`modification`) VALUES (CONCAT('Compte ',?,'  ',?,' (',?,') a supprimé la catégorie ',?))";
        $stmt = $link->prepare($requete);
        $stmt->bind_param("ssss", $lastname,$name,$role,$nouvelle_categorie);
        $stmt->execute();
        echo '<script> alert("Catégorie créée avec succès.");window.location.replace("../tags.php");</script>';
    }
