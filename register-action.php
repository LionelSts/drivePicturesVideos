<?php
    session_start();
    $regex = "/^(?=.*[\w])(?=.*[\W])[\w\W]{8,}$/";
    $tmpPsw = $_GET['tmpPsw'];
    $psw = $_POST['password'];
    $mail = $_POST['email'];
    $link = mysqli_connect("127.0.0.1", "root", "", "drivelbr");
    $link->query('SET NAMES utf8');
    $requete = "SELECT `mail`,`mot_de_passe` FROM `utilisateurs` WHERE `mail` = '$mail' ";
    $result = mysqli_query($link,$requete);
    $data = mysqli_fetch_array($result);
    $mail =$data['mail'];
    $oldPsw = $data['mot_de_passe'];
    if(password_verify($tmpPsw, $oldPsw)){
        if(preg_match($regex, $psw)){
            $psw = password_hash($psw, PASSWORD_BCRYPT);
            $requete = "UPDATE `utilisateurs` SET `mot_de_passe` = '$psw', `etat` = 'actif' WHERE `mail` = '$mail' AND `etat` = 'en attente'";
            $result = mysqli_query($link, $requete);
            echo '<script> alert("Nouveau mot de passe enregistré."); window.location.replace("index.php");</script>';
        } else {
            echo '<script> alert("Veuillez saisir un mot de passe contenant au minimum 1 minuscule, 1 majuscule, 1 chiffre et 1 caractère spécial.")</script>;';
            echo '<script> window.location.replace("register.php?tmpPsw='. $tmpPsw .'");</script>';
        }
    }
    else{
        echo '<script> alert("Lien erroné.");/*window.location.replace("index.php");*/</script>';
    }
?>
