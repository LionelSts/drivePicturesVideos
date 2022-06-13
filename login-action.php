<?php
session_start();
$mail = $_POST['email'];
$mdp = $_POST['password'];
//include("connexion.php");
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;
$requete = "SELECT `prenom`, `nom`, `mail`, `mot_de_passe`,`role` FROM `utilisateurs` WHERE `mail` = '$mail' "; // Preparing the request to verify the password where the login entered is found on the database
$result = mysqli_query($link, $requete); // Saving the result
$row = mysqli_fetch_array($result);
$hashedPsw= $row['mot_de_passe'];

if(password_verify($mdp, $hashedPsw) && $row['etat']!= "inactif") // If the password entered and the hashed version stored in the database are equal when password entered is hashed
{
    $_SESSION['mail'] = $row['mail']; // Saving the user ID needed later
    $_SESSION['role'] = $row['role'];
    $_SESSION['nom'] = $row['nom'];
    $_SESSION['prenom'] = $row['prenom'];
    header('Location:home.php'); // Then you are logged in and can go further
}
else
{
    echo '<script> alert("Identifiant ou mot de passe incorrecte");window.location.replace("index.php");</script>';
}
