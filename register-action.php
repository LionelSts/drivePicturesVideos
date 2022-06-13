<?php
    session_start();    // lancement de la session
    $regex = "/^(?=.*[\w])(?=.*[\W])[\w\W]{8,}$/";  // expression régulière des régles du mot de passe
    $tmpPsw = $_GET['tmpPsw'];  // récupération du mot de passe par la méthode GET
    $psw = $_POST['password'];
    $mail = $_POST["email"];    // récupération du mail saisi
    $link = mysqli_connect("127.0.0.1", "root", "", "drivelbr");    // connexion à la bdd
    $link->query('SET NAMES utf8');
    $requete = "SELECT `mail`,`mot_de_passe` FROM `utilisateurs` WHERE `mail` = '$mail' "; // recherche du mot de passe correspondant au mail saisi
    $result = mysqli_query($link,$requete);
    $data = mysqli_fetch_array($result);
    $mail =$data['mail'];
    $oldPsw = $data['mot_de_passe'];

    if(password_verify($tmpPsw, $oldPsw)){  // vérification de la similitude du mot de passe récupéré et celui enregistré dans la bdd
        if(preg_match($regex, $psw)){   // si le mot de passe respecte les règles...
            $psw = password_hash($psw, PASSWORD_BCRYPT);    // hashage du mot de passe saisi
            $requete = "UPDATE `utilisateurs` SET `mot_de_passe` = '$psw', `etat` = 'actif' WHERE `mail` = '$mail' AND `etat` = 'en attente'";   // insertion du nouveau mot de passe dans la bdd
            $result = mysqli_query($link, $requete);
            echo '<script> alert("Nouveau mot de passe enregistré."); window.location.replace("index.php");</script>';  // redirection vers le login avec un message de confirmation
        } else {    // mot de passe ne respectant pas les régles => redirection + message d'erreur
            echo '<script> alert("Veuillez saisir un mot de passe contenant au minimum 1 minuscule, 1 majuscule, 1 chiffre et 1 caractère spécial.")</script>;';
            echo '<script> window.location.replace("register.php?tmpPsw='. $tmpPsw .'");</script>';
        }
    }
    else{
        echo '<script> alert("Lien erroné.");/*window.location.replace("index.php");*/</script>';   // message d'erreur et redirection sur  la page de login
    }
?>
