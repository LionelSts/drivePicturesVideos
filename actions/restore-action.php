<?php
session_start();    // démarage de la session
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;  // connexion à la base de données
$link->query('SET NAMES utf8');
$requete = "SELECT `id` FROM `fichiers` ORDER BY `id` DESC LIMIT 1";
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
foreach ($files as $file){                                                                                              // Pour hcaque fichier à restaurer
    $id++;
    $fileName = substr($file,0,strpos($file, '.'));
    $requete = "SELECT * FROM `corbeille` WHERE `id` = $fileName";                                                      // On récupère ses infos
    $data = mysqli_query($link, $requete);
    $results = mysqli_fetch_array($data);
    $nom_fichier = $results['nom_fichier'];
    $extension = $results['extension'];
    $auteur = $results['auteur'];
    $date = $results['date'];
    $duree = $results['duree'];
    $size = $results['size'];
    $nom_stockage = $results['nom_stockage'];
    $delete_date = date('Y-m-d H:i:s');
    $delete_user = $_SESSION['mail'];
    rename( '../corbeille/'.$nom_stockage.'.'.$extension, '../fichiers/'.$nom_stockage.'.'.$extension);        // On déplace le fichier
    rename( '../corbeille/miniature-'.$nom_stockage.'.png', '../miniatures/'.$nom_stockage.'.png');            // On déplace la miniature
    $requete = "DELETE FROM `corbeille` WHERE `id` = $fileName";                                                        // on supprime la ligne de ce fichier dans la bdd de la corbeille
    mysqli_query($link, $requete);
    $requete = "INSERT INTO `fichiers` VALUES ('$id','$nom_fichier','$extension','$auteur','$date', '$duree', '$size', '$nom_stockage') ";
    mysqli_query($link, $requete);                                                                                      // On stock a nouveau les infos dans la table fichiers
    $requete = "INSERT INTO `caracteriser` VALUES ('$id','Sans tag')";
    mysqli_query($link, $requete);                                                                                      // On lui attribue un tag Sans tag
    $requete2 = "INSERT INTO `tableau_de_bord` (`modification`) VALUES ('Compte ".$lastname." ".$name." (".$role2.") a restauré le fichier : ".$nom_fichier." ')";
    mysqli_query($link, $requete2);
}
header('Location:../'.$page.'.php');
