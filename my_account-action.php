<?php
    session_start();
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $mdp = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $mail = $_POST['email'];
    //echo $prenom ," ", $nom, " ", $mail, " ", $mdp;
    $link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;
    if ($_SESSION['role'] == "admin"){
        $role = $_POST['role'];
        $requete = "UPDATE `utilisateurs` SET `prenom` = '$prenom', `nom` = '$nom', `mot_de_passe` = '$mdp', `role` = '$role' WHERE `mail` = '$mail'";
    }
    else $requete = "UPDATE `utilisateurs` SET `prenom` = '$prenom', `nom` = '$nom', `mot_de_passe` = '$mdp' WHERE `mail` = '$mail'";
    $result = mysqli_query($link, $requete); // Saving the result
    if ($_SESSION['mail'] == $mail) echo '<script language="JavaScript"> alert("Vos changements ont bien été appliqués");window.location.replace("home.php");</script>';
    else echo '<script language="JavaScript"> alert("Informations invalides, veuillez réessayer.");window.location.replace("my_account.php");</script>'
?>