<?php
session_start();    // démarage de la session
$mail = $_POST['email'];    // enregistrement du mail saisi dans la variable "$mail"
$mdp = $_POST['password'];  // enregistrement du mot de passe saisi dans la variable "$mdp"
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;  // connexion à la bdd
$link->query('SET NAMES utf8');
$requete = "SELECT `prenom`, `nom`, `mail`, `mot_de_passe`,`role`, `etat` FROM `utilisateurs` WHERE `mail` = ? ";
$stmt = $link->prepare($requete);
$stmt->bind_param("s", $mail);
$stmt->execute();
$result = $stmt->get_result();
$row = mysqli_fetch_array($result);
$hashedpsw = $row['mot_de_passe'];  // enregistrement du mot de passe saisi
if(password_verify($mdp, $hashedpsw) && $row['etat']!= "inactif")   // si le mot de passe saisi correspond à celui dans la bdd et le compte n'est pas supprimé
{
    if($row['etat'] == 'en attente'){
        $requete = "UPDATE `utilisateurs` SET `etat` = 'actif' WHERE `mail` = ? ";
        $stmt = $link->prepare($requete);
        $stmt->bind_param("s", $mail);
        $stmt->execute();
    }
    $_SESSION['mail'] = $row['mail'];   // enregistrement du mail de l'utilisateur connecté
    $_SESSION['role'] = $row['role'];  // enregistrement du role de l'utilisateur connecté
    $_SESSION['nom'] = $row['nom'];    // enregistrement du nom de l'utilisateur connecté
    $_SESSION['prenom'] = $row['prenom'];   // enregistrement du prénom de l'utilisateur connecté
    header('Location:../home.php');    // l'utilisateur est connecté et on le renvoie vers la page d'accueil
}
else    // dans le cas contraire...
{
    echo '<script> alert("Identifiant ou mot de passe incorrect");window.location.replace("../index.php");</script>'; // l'utilisateur n'a pas saisie les bonnes infos, il est redirigé
}
