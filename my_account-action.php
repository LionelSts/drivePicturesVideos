<?php
    session_start();
    if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("./index.php");</script>';
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $mail = $_SESSION['mail'];
    $link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;
    $requete = "SELECT `nom`, `prenom`, `mail`, `role`, `mot_de_passe` FROM `utilisateurs` WHERE `mail` = '$mail'";
    $result = mysqli_query($link,$requete);
    $data = mysqli_fetch_array($result);
    if($_POST['password'] != "") $mdp = password_hash($_POST['password'], PASSWORD_BCRYPT);
    else $mdp = $data['mot_de_passe'];
    $requete = "SELECT COUNT(*) FROM `utilisateurs` WHERE `role` = 'admin'";
    $result = mysqli_query($link,$requete);
    $countAdmin = mysqli_fetch_array($result);
    if ($_SESSION['role'] == "admin"){
        if($countAdmin[0] > 1) $role = $_POST['role'];
        else $role = $_SESSION['role'];
        $requete = "UPDATE `utilisateurs` SET `prenom` = '$prenom', `nom` = '$nom', `mot_de_passe` = '$mdp', `role` = '$role' WHERE `mail` = '$mail'";
        $_SESSION['role'] = $role;
    }
    else $requete = "UPDATE `utilisateurs` SET `prenom` = '$prenom', `nom` = '$nom', `mot_de_passe` = '$mdp' WHERE `mail` = '$mail'";
    $_SESSION['mail'] = $mail; // Saving the user ID needed later
    $_SESSION['nom'] = $nom;
    $_SESSION['prenom'] = $prenom;
    $result = mysqli_query($link, $requete); // Saving the result
    if($role != $_POST['role']) echo '<script> alert("Votre rôle n\'a pas été modifié (vous êtes le seul admin)")</script>';
    else echo '<script> alert("Vos changements ont bien été appliqués")</script>';
    echo '<script>window.location.replace("my_account.php")</script>';
