<?php
    session_start();
    $mail = $_POST["email"];
    $regex = "/^(?=.*[\w])(?=.*[\W])[\w\W]{8,}$/";
    if(preg_match($regex, $_POST['password'])){
        $mdp = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $link = mysqli_connect("127.0.0.1", "root", "", "drivelbr");
        $requete = "UPDATE `utilisateurs` SET `mot_de_passe` = '$mdp', `etat` = 'actif' WHERE `mail` = '$mail' AND `etat` = 'en attente'";
        $result = mysqli_query($link, $requete); // Saving the result
        echo '<script> alert("Nouveau mot de passe enregistré."); window.location.replace("login.html");</script>';
    }
    else {
        echo '<script> alert("Veuillez saisir un mot de passe contenant au minimum 1 minuscule, 1 majuscule, 1 chiffre et 1 caractère spécial."); window.location.replace("register.html");</script>';
    }
?>
