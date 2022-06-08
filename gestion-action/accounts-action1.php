<?php
    session_start();
    if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("./index.html");</script>';
    $link = mysqli_connect("127.0.0.1", "root", "", "drivelbr");
    $mail = $_POST['selectedMail']; $prenom = $_POST['prenom']; $nom = $_POST['nom']; $mdp = $_POST['password']; $role = $_POST['role'];
    $password = password_hash($mdp, PASSWORD_BCRYPT);
    if (isset($_POST["supprimer"])){
        $requete = "UPDATE `utilisateurs` SET `etat` = 'inactif' WHERE `mail` = '$mail'";
        $result = mysqli_query($link,$requete);
        echo '<script> alert("Compte supprimé avec succés.");window.location.replace("../home.php");</script>';
    }
    else if(isset($_POST["modifier"])){
        if($_POST["password"] != ""){
            $regex = "/^(?=.*[\w])(?=.*[\W])[\w\W]{8,}$/";
            if(preg_match($regex, $mdp)) {
                $requete = "UPDATE `utilisateurs` SET `prenom` = '$prenom', `nom` = '$nom', `mot_de_passe` = '$password', `role` = '$role' WHERE `mail` = '$mail'";
                $result = mysqli_query($link, $requete);
                echo '<script> alert("Compte modifié avec succés.");window.location.replace("../home.php");</script>';
            }
            else echo '<script> alert("Veuillez saisir un mot de passe contenant au minimum 1 minuscule, 1 majuscule, 1 chiffre et 1 caractère spécial."); window.location.replace("../accounts.php");</script>';
        }else{
            $requete = "UPDATE `utilisateurs` SET `prenom` = '$prenom', `nom` = '$nom', `role` = '$role' WHERE `mail` = '$mail'";
            $result = mysqli_query($link, $requete);
            echo '<script> alert("Compte modifié avec succés.");window.location.replace("../home.php");</script>';
        }
    }
?>
