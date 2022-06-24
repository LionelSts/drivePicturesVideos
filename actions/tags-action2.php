<?php
    session_start();
    if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("../index.php");</script>';
    $link = mysqli_connect("127.0.0.1", "root", "", "drivelbr");
    $name = $_SESSION["prenom"]; $lastname = $_SESSION["nom"]; $role = $_SESSION["role"];
    $link->query('SET NAMES utf8');
    $nomTag = $_POST["nomTag"];
    $categorie = $_POST["categorie"];
    if (isset($_POST["Supprimer"])) {
        $requete = "UPDATE `caracteriser` SET `nom_tag`='sans tag' WHERE id_fichier IN (SELECT * FROM (SELECT `id_fichier` FROM `caracteriser` WHERE `id_fichier` IN (SELECT `id_fichier` FROM `caracteriser` where `nom_tag`=?) GROUP BY `id_fichier` HAVING COUNT(`nom_tag`)=1) AS tmp)";
        $stmt = $link->prepare($requete);
        $stmt->bind_param("s", $nomTag);
        $stmt->execute();
        $requete = "DELETE FROM `caracteriser` WHERE `nom_tag`=?";
        $stmt = $link->prepare($requete);
        $stmt->bind_param("s", $nomTag);
        $stmt->execute();
        $requete = "DELETE FROM `attribuer` WHERE `nom_tag`=?";
        $stmt = $link->prepare($requete);
        $stmt->bind_param("s", $nomTag);
        $stmt->execute();
        $requete = "DELETE FROM `tags` WHERE `nom_tag`=?";
        $stmt = $link->prepare($requete);
        $stmt->bind_param("s", $nomTag);
        $stmt->execute();
        $requete = "INSERT INTO tableau_de_bord (modification) VALUES (CONCAT('Compte ',?,'  ',?,' (',?,') a supprimé le tag ',?))";
        $stmt = $link->prepare($requete);
        $stmt->bind_param("ssss", $lastname,$name,$role,$nomTag);
        $stmt->execute();
        echo '<script> alert("Tag supprimé avec succès.");window.location.replace("../tags.php");</script>';
    } else if (isset($_POST["Modifier"])) {
        $ancienTag = $_POST["ancienTag"];
        $ancienneCategorie = $_POST["ancienneCategorie"];
        if ($nomTag != $ancienTag && $categorie != $ancienneCategorie) {
            $requete = "UPDATE `tags` SET `nom_tag`=?, `nom_categorie` = ? WHERE `nom_categorie` = ? AND `nom_tag` =  ?";
            $stmt = $link->prepare($requete);
            $stmt->bind_param("ssss", $nomTag,$categorie,$ancienneCategorie,$ancienTag);
            $stmt->execute();
            $requete = "INSERT INTO `tableau_de_bord` (`modification`) VALUES (CONCAT('Compte ',?,'  ',?,' (',?,') a renommé le tag ',?,' en ',?,' et a changé sa catégorie de ',?,' à ',?))";
            $stmt = $link->prepare($requete);
            $stmt->bind_param("sssssss", $lastname,$name,$role,$ancienTag,$nomTag,$ancienneCategorie,$categorie);
            $stmt->execute();
        }
        else if ($categorie != $ancienneCategorie) {
            $requete = "UPDATE `tags` SET `nom_categorie`=? WHERE `nom_tag`=?";
            $stmt = $link->prepare($requete);
            $stmt->bind_param("ss", $categorie,$nomTag);
            $stmt->execute();
            $requete = "INSERT INTO `tableau_de_bord` (`modification`) VALUES (CONCAT('Compte ',?,'  ',?,' (',?,') a changé de catégorie le tag ',?,' de ',?,' à ',?))";
            $stmt = $link->prepare($requete);
            $stmt->bind_param("ssssss", $lastname,$name,$role,$nomTag,$ancienneCategorie,$categorie);
            $stmt->execute();
        }
        else {
            $requete = "UPDATE `tags` SET `nom_tag`=? WHERE `nom_categorie`=? AND `nom_tag`=?";
            $stmt = $link->prepare($requete);
            $stmt->bind_param("sss", $nomTag, $categorie, $ancienTag);
            $stmt->execute();
            $requete = "INSERT INTO `tableau_de_bord` (`modification`) VALUES (CONCAT('Compte ',?,'  ',?,' (',?,') a renommé le tag ',?,' en ',?))";
            $stmt = $link->prepare($requete);
            $stmt->bind_param("sssss", $lastname,$name,$role,$ancienTag,$nomTag);
            $stmt->execute();
        }
        echo '<script> alert("Tag modifié avec succès.");window.location.replace("../tags.php");</script>';
    } else if (isset($_POST["Créer"])) {
        $requete = "INSERT INTO `tags` VALUES (?,?) ";
        $stmt = $link->prepare($requete);
        $stmt->bind_param("ss", $nomTag, $categorie);
        $stmt->execute();
        $requete = "INSERT INTO tableau_de_bord (modification) VALUES (CONCAT('Compte ',?,'  ',?,' (',?,') a ajouté un tag ',?,'dans la catégorie ',?))";
        $stmt = $link->prepare($requete);
        $stmt->bind_param("sssss", $lastname,$name,$role,$nomTag,$categorie);
        $stmt->execute();
        echo '<script> alert("Tag crée avec succès.");window.location.replace("../tags.php");</script>';
    }
