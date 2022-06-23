<?php
session_start();    // démarage de la session
if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("./index.php");</script>';  // redirection vers le login si l'utilisateur n'est pas connecté
$name = $_SESSION["prenom"]; $lastname = $_SESSION["nom"]; $role2 = $_SESSION["role"];
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;    // connexion à la base de données
$link->query('SET NAMES utf8');
$chaine = urldecode(file_get_contents('php://input'));  // récupération du mail sélectionné en enlevant les données inutiles
$mail = str_replace("=Réactiver le compte",'', $chaine);
$requete = "UPDATE `utilisateurs` SET `etat` = 'actif' WHERE `mail` = ?"; // on met à jour l'état (on passe en 'actif') associé au mail
$stmt = $link->prepare($requete);
$stmt->bind_param("s", $mail);
$stmt->execute();
$requete = "SELECT `role` FROM `utilisateurs` WHERE `mail` = ?";
$stmt = $link->prepare($requete);
$stmt->bind_param("s", $mail);
$stmt->execute();
$result = $stmt->get_result();
$data = mysqli_fetch_array($result);
$adresse = $data["role"];
$requete2 = "INSERT INTO tableau_de_bord (modification) VALUES ('Compte ".$lastname." ".$name." (".$role2.") a réactivé le compte ".$mail." (".$adresse.")')";
header('location:../accounts.php'); // redirection vers la page "accounts.php"
