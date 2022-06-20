<?php
    session_start();    // démarage de la session
    if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("./index.php");</script>';  // redirection vers le login si l'utilisateur n'est pas connecté
    $link = mysqli_connect("127.0.0.1", "root", "", "drivelbr");    // connexion à la base de données
    $link->query('SET NAMES utf8');
    $name = $_SESSION["prenom"]; $lastname = $_SESSION["nom"]; $role2 = $_SESSION["role"];
    $mail = $_POST['selectedMail']; $prenom = $_POST['prenom']; $nom = $_POST['nom']; $mdp = $_POST['password']; $role = $_POST['role']; $descriptif = $_POST['descriptif2'];   // récupération des données du formulaire de Gestion des comptes
    $password = password_hash($mdp, PASSWORD_BCRYPT);   // hashage du mot de passe avec la méthode 'PASSWORD_BCRYPT'
    $chaine = urldecode(file_get_contents('php://input'));  // récupération de la liste des tags sélectionné en enlevant les données inutiles
    $chaine = str_replace("selectedMail=".$mail."&nom=".$nom."&prenom=".$prenom."&role=".$role, '', $chaine);
    $chaine = str_replace("&listeTag=", ' ', $chaine);
    $chaine = str_replace("&modifier=Appliquer les modifications", '', $chaine);
    if($_POST['password'] != '') $chaine = str_replace("&password=".$mdp, ' ', $chaine);    // si un mot de passe est renseigné, on le supprime de la chaine d'info précédente
    else $chaine = str_replace("&password=", ' ', $chaine); // sinon, on retire le caractère ' ' de la chaine
    if (isset($_POST["supprimer"])){    // si l'utilisateur clique sur le bouton "supprimer"
        $requete = "UPDATE `utilisateurs` SET `etat` = 'inactif' WHERE `mail` = '$mail'";   // on place en "inactif" l'utilisateur dans la bdd
        $requete2 = "INSERT INTO `tableau_de_bord` (`modification`) VALUES ('Compte ".$lastname." ".$name." (".$role2.") a désactivé le compte ".$mail." (".$role.")')";
        $result = mysqli_query($link,$requete);mysqli_query($link,$requete2);
        echo '<script> alert("Compte supprimé avec succès.");window.location.replace("../home.php");</script>'; // redirection vers la page d'accueil
    }
    else if(isset($_POST["modifier"])){ // si l'utilisateur clique sur le bouton "modifier"
        $tab = explode(" ",$chaine);    // on récupère la chaine d'informations contenant les tags et on la convertit en tableau
        $requete1 = "DELETE FROM `attribuer` WHERE `email` ='$mail'";   // on supprime les tags associés à cet email dans la bdd
        $result1 = mysqli_query($link, $requete1);
        for ($i = 1; $i < count($tab)-1; $i++){ // puis on insère la liste des tags (modifiée) dans la bdd en parcourant le tableau obtenu précédement
            $requete2 = "INSERT INTO `attribuer` (`email`,`nom_tag`) VALUES ('$mail', '$tab[$i]')";
            $result2 = mysqli_query($link, $requete2);
        }
        if($_POST["password"] != ""){   // si l'utisateur souhaite modifier son mot de passe
            $regex = "/^(?=.*\w)(?=.*\W)[\w\W]{8,}$/";  // on vérifie le respect des régles du mot de passe (1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial et 8 caractères au minimum)
            if(preg_match($regex, $mdp)) {  // si le mot de passe respecte les règles...
                $requete = "UPDATE `utilisateurs` SET `prenom` = '$prenom', `nom` = '$nom', `mot_de_passe` = '$password', `role` = '$role', `descriptif` = '$descriptif' WHERE `mail` = '$mail'"; // on met à jour les informations du compte dans la bdd
                $requete2 = "INSERT INTO `tableau_de_bord` (`modification`) VALUES ('Compte ".$lastname." ".$name." (".$role2.") a modifié les informations du compte ".$mail." (".$role.")')";
                $result = mysqli_query($link, $requete); $result2 = mysqli_query($link, $requete2);
                echo '<script> alert("Compte modifié avec succès.");window.location.replace("../home.php");</script>';  // redirection vers la page d'accueil
            }
            else echo '<script> alert("Veuillez saisir un mot de passe contenant au minimum 1 minuscule, 1 majuscule, 1 chiffre et 1 caractère spécial."); window.location.replace("../accounts.php");</script>';   //si le mot de passe ne respecte pas les régles, on affiche un message d'erreur et on réactualise la page
        }else{
            $requete = "UPDATE `utilisateurs` SET `prenom` = '$prenom', `nom` = '$nom', `role` = '$role', `descriptif` = '$descriptif' WHERE `mail` = '$mail'";   // on met à jour les informations (sauf le mot de passe) du compte dans la bdd
            $requete2 = "INSERT INTO `tableau_de_bord` (`modification`) VALUES ('Compte ".$lastname." ".$name." (".$role2.") a modifié les informations du compte ".$mail." (".$role.")')";
            $result = mysqli_query($link, $requete); $result2 = mysqli_query($link, $requete2);
            echo '<script> alert("Compte modifié avec succès.");window.location.replace("../home.php");</script>';  // redirection vers la page d'accueil
        }
    }
