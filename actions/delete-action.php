<?php
session_start();    // démarage de la session
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;  // connexion à la base de données
$link->query('SET NAMES utf8');
$requete = "SELECT `id` FROM `corbeille` ORDER BY `id` DESC LIMIT 1"; // On récupère le plus grand id de corbeille
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
$files = explode(",",$_POST["fichiers"]); // On récupère tous les fichiers à supprimer
$page = $_POST['page'];
foreach ($files as $file){       // On supprime tous les fichiers
    $id++;
    $fileName = substr($file,0,strpos($file, '.'));
    $requete = "SELECT * FROM `fichiers` WHERE `id` = $fileName";
    $data = mysqli_query($link, $requete);
    $results = mysqli_fetch_array($data);
    $nom_fichier = $results['nom_fichier'];                                                                             // On récupère toutes les infos
    $extension = $results['extension'];
    $auteur = $results['auteur'];
    $date = $results['date'];
    $duree = $results['duree'];
    $size = $results['size'];
    $nom_stockage = $results['nom_stockage'];
    $delete_date = date('Y-m-d H:i:s');
    $delete_user = $_SESSION['mail'];
    rename('../fichiers/'.$nom_stockage.'.'.$extension, '../corbeille/'.$nom_stockage.'.'.$extension);          // On déplace le fichier dans la corbeille (serveur)
    rename('../miniatures/'.$nom_stockage.'.png', '../corbeille/miniature-'.$nom_stockage.'.png');              // On déplace la mignature dans la corbeille (serveur)
    $requete = "DELETE FROM `fichiers` WHERE `id` = $fileName";
    mysqli_query($link, $requete);
    $requete = "DELETE FROM `caracteriser` WHERE `id_fichier` = $fileName";                                              // On supprime les tags pour ce fichier (bdd)
    mysqli_query($link, $requete);
    $requete = "INSERT INTO `corbeille` VALUES ('$id','$nom_fichier','$extension','$auteur','$date', '$duree', '$size', '$nom_stockage','$delete_date','$delete_user') ";   // On déplace le fichier dans la corbeille (bdd)
    mysqli_query($link, $requete);
    $requete2 = "INSERT INTO `tableau_de_bord` (`modification`) VALUES ('Compte ".$lastname." ".$name." (".$role2.") a supprimé le fichier : ".$nom_fichier." ')";           // On logs les infos
    mysqli_query($link, $requete2);
}
header('Location:../'.$page.'.php');
