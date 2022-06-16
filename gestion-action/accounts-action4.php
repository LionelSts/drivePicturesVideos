<?php
session_start();    // démarage de la session
if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("./index.php");</script>';  // redirection vers le login si l'utilisateur n'est pas connecté
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;    // connexion à la base de données
$link->query('SET NAMES utf8');
$chaine = urldecode(file_get_contents('php://input'));  // récupération du mail sélectionné en enlevant les données inutiles
$mail = str_replace("=Réactiver le compte",'', $chaine);
$requete = "UPDATE `utilisateurs` SET `etat` = 'actif' WHERE `mail` = '$mail'"; // on met à jour l'état (on passe en 'actif') associé au mail
$result = mysqli_query($link,$requete);
header('location:../accounts.php'); // redirection vers la page "accounts.php"
