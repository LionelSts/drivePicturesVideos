<?php
session_start();

$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;
$link->query('SET NAMES utf8');

$requete = "SELECT `id` FROM `corbeille` ORDER BY `id` DESC LIMIT 1";
$result = mysqli_query($link, $requete);
$data = mysqli_fetch_array($result);
if(!empty($data)){
    $id = $data['id'];
}else{
    $id = 0;
}
$id++;

$files = explode(",",$_POST["fichiers"]);
foreach ($files as $file){
    $fileName = substr($file,0,strpos($file, '.'));
    $requete = "SELECT * FROM `fichiers` WHERE `id` = $fileName";
    $data = mysqli_query($link, $requete);
    $results = mysqli_fetch_array($data);
    $nom_fichier = $results['nom_fichier'];
    $extension = $results['extension'];
    $auteur = $results['auteur'];
    $date = $results['date'];
    $duree = $results['duree'];
    $size = $results['size'];
    $delete_date = date('Y-m-d H:i:s');
    $delete_user = $_SESSION['mail'];
    rename('./fichiers/'.$id.'.'.$extension, './corbeille/'.$id.'.'.$extension);
    rename('./mignatures/'.$fileName.'.png', './corbeille/mignature-'.$id.'.png');
    $requete = "DELETE FROM `fichiers` WHERE `id` = $fileName";
    mysqli_query($link, $requete);
    $requete = "DELETE FROM `caracteriser` WHERE `id_fichier` = $fileName";
    mysqli_query($link, $requete);
    $requete = "INSERT INTO `corbeille` VALUES ('$id','$nom_fichier','$extension','$auteur','$date', '$duree', '$size','$delete_date','$delete_user') ";
    mysqli_query($link, $requete);
}
header('Location:my_files.php');
