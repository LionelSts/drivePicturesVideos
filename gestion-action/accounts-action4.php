<?php
session_start();
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;
$chaine = urldecode(file_get_contents('php://input'));
$mail = str_replace("=Réactiver le compte",'', $chaine);
$requete = "UPDATE `utilisateurs` SET `etat` = 'actif' WHERE `mail` = '$mail'";
$result = mysqli_query($link,$requete);
header('location:../accounts.php');
?>