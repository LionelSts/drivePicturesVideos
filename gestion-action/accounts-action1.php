<?php
session_start();
$link = mysqli_connect("127.0.0.1", "root", "", "drivelbr");
$mail = $_POST['mail']; $prenom = $_POST['prenom']; $nom = $_POST['nom']; $mdp = $_POST['password']; $role = $_POST['role'];
$password = password_hash($mdp, PASSWORD_BCRYPT);
if (isset($_POST["supprimer"])){
    $requete = "UPDATE `utilisateurs` SET `etat` = 'inactif' WHERE `mail` = '$mail'";
    $result = mysqli_query($link,$requete);
    echo '<script> alert("Compte supprimé avec succés.");window.location.replace("../home.php");</script>';
}
else if(isset($_POST["modifier"])){
    $requete= "UPDATE `utilisateurs` SET `prenom` = '$prenom', `nom` = '$nom', `mot_de_passe` = '$password', `role` = '$role' WHERE `mail` = '$mail'";
    $result = mysqli_query($link,$requete);
    echo '<script> alert("Compte modifié avec succés.");window.location.replace("../home.php");</script>';
}
?>
