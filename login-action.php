<?php
session_start();    // démarage de la session
$mail = $_POST['email'];    // enregistrement du mail saisi dans la variable "$mail"
$mdp = $_POST['password'];  // enregistrement du mot de passe saisi dans la variable "$mdp"
//include("connexion.php");
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;  // connexion à la bdd
$link->query('SET NAMES utf8');
$requete = "SELECT `prenom`, `nom`, `mail`, `mot_de_passe`,`role` FROM `utilisateurs` WHERE `mail` = '$mail' ";
$result = mysqli_query($link, $requete);
$row = mysqli_fetch_array($result);
$hashedpsw = $row['mot_de_passe'];  // enregistrement du mot de passe saisi

if(password_verify($mdp, $hashedpsw) && $row['etat']!= "inactif")   // si le mot de passe saisi correspond à celui dans la bdd et le compte n'est pas supprimé
{
    $_SESSION['mail'] = $row['mail'];   // enregistrement du mail de l'utilisateur connecté
    $_SESSION['role'] = $row['role'];  // enregistrement du role de l'utilisateur connecté
    $_SESSION['nom'] = $row['nom'];    // enregistrement du nom de l'utilisateur connecté
    $_SESSION['prenom'] = $row['prenom'];   // enregistrement du prénom de l'utilisateur connecté
    header('Location:home.php');    // l'utilisateur est connecté et on le renvoie vers la page d'accueil
}
else    // dans le cas contraire...
{
    echo '<script> alert("Identifiant ou mot de passe incorrecte");window.location.replace("index.php");</script>'; // l'utilisateur n'a pas saisie les bonnes infos, il est redirigé
}
