<?php
session_start();
$link = mysqli_connect("127.0.0.1", "root", "", "drivelbr");
$mail = $_POST['mail']; $prenom = $_POST['prenom']; $nom = $_POST['nom']; $mdp = $_POST['password']; $role = $_POST['role'];
if (isset($_POST["supprimer"])){
    $requete= "DELETE FROM `utilisateurs` WHERE `mail` = '$mail'";
    $result = mysqli_query($link,$requete);
    echo '<script> alert("Compte supprimé avec succés.");window.location.replace("../home.php");</script>';
}
else if(isset($_POST["modifier"])){
    $requete= "UPDATE `utilisateurs` SET `prenom` = '$prenom', `nom` = '$nom', `mot_de_passe` = '$mdp', `role` = '$role' WHERE `mail` = '$mail'";
    $result = mysqli_query($link,$requete);
    echo '<script> alert("Compte modifié avec succés.");window.location.replace("../home.php");</script>';
}
?>
