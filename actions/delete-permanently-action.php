<?php
session_start();    // démarage de la session
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;  // connexion à la base de données
$link->query('SET NAMES utf8');
$requete = "SELECT `id` FROM `corbeille` ORDER BY `id` DESC LIMIT 1";
$result = mysqli_query($link, $requete);
$data = mysqli_fetch_array($result);
$name = $_SESSION["prenom"];
$lastname = $_SESSION["nom"];
$role2 = $_SESSION["role"];
if(!empty($data)){
    $id = $data['id'];
}else{
    $id = 0;
}
$files = explode(",",$_POST["fichiers"]);
$page = $_POST['page'];
foreach ($files as $file){
    $id++;
    $fileName = substr($file,0,strpos($file, '.'));
    $requete = "SELECT `nom_stockage`, `extension`, `nom_fichier` FROM `corbeille` WHERE `id` = $fileName";
    $data = mysqli_query($link, $requete);
    $results = mysqli_fetch_array($data);
    $nom_stockage = $results['nom_stockage'];
    $extension = $results['extension'];
    $nom_fichier = $results['nom_fichier'];
    unlink('../corbeille/'.$nom_stockage.'.'.$extension);
    unlink('../corbeille/miniature-'.$nom_stockage.'.png');
    $requete = "DELETE FROM `corbeille` WHERE `id` = $fileName";
    mysqli_query($link, $requete);
    $requete2 = "INSERT INTO `tableau_de_bord` (`modification`) VALUES ('Compte ".$lastname." ".$name." (".$role2.") a supprimé définitivement le fichier : ".$nom_fichier." ')";
    mysqli_query($link, $requete2);
}
header('Location:../'.$page.'.php');
