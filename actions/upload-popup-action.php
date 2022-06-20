<?php
session_start();// démarage de la session
require '../vendor/autoload.php';
include '../thumbnail.php';
if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("../index.php");</script>';
$name = $_SESSION["prenom"];
$lastname = $_SESSION["nom"];
$role2 = $_SESSION["role"];
$str_arr = array();
foreach ($_POST as $key => $value){
    if($key != "submit" && $key != "newTag"){
        $chaine = explode ("-", $key);
        $str_arr[] = $chaine;
    }
}
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;
$link->query('SET NAMES utf8');
$countfiles = count($_FILES['file']['name']);
$requete = "SELECT `id` FROM `fichiers` ORDER BY `id` DESC LIMIT 1";
$result = mysqli_query($link, $requete);
$data = mysqli_fetch_array($result);
if(!empty($data)){
    $id = $data['id'];
}else{
    $id = 0;
}

$mail = $_SESSION['mail'];
$date = date('Y-m-d H:i:s');
$tags_file = array();
$requete = "SELECT `nom_tag` FROM `tags`";
$requestTags = mysqli_query($link, $requete)->fetch_all(MYSQLI_ASSOC);
$tagList = array();
foreach ($requestTags as $value){
    $tagList[] = strval($value["nom_tag"]);
}
foreach ($str_arr as $tag){
    $isIn = array_search(strval($tag[1]), $tagList, -1);
    if($isIn == -1){
        $requete = "INSERT INTO tags (`nom_tag`, `nom_categorie`) VALUES ('$tag[1]', '$tag[0]')";
        $result = mysqli_query($link, $requete);
        $requete2 = "INSERT INTO `tableau_de_bord` (`modification`) VALUES ('Compte ".$lastname." ".$name." (".$role2.") a ajouté un tag ".$tag[1]." dans la catégorie ".$tag[0]."')";
        mysqli_query($link, $requete2);
    }
    $tags_file[]=str_replace("_", " ", $tag[1]);
}
for($i = 0 ; $i < $countfiles ; $i++){
    $requete = "SELECT `nom_stockage` FROM `fichiers`";
    $result = mysqli_query($link, $requete);
    $filesName = [];
    while($row = mysqli_fetch_row($result)) $filesName[] = $row;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $nomFichier = '';
    for ($y = 0; $y < 65; $y++) {
        $nomFichier .= $characters[rand(0, strlen($characters)-1)];
    }
    while(array_search($nomFichier, $filesName)){
        $nomFichier = '';
        for ($y = 0; $y < 65; $y++) {
            $nomFichier .= $characters[rand(0, strlen($characters)-1)];
        }
    }
    $ext = $_FILES['file']['type'][$i];
    $filename = $_FILES['file']['name'][$i];
    if(preg_match("/image|video/", $_FILES['file']['type'][$i])){
        $id++;
        $extension = str_replace("video/", "", $ext);
        $extension = str_replace("image/", "", $extension);
        if($extension){
            $filename = str_replace('.jpeg', "", $filename);
            $filename = str_replace('.jpg', "", $filename);
        }
        $filename = str_replace('.'.$extension, "", $filename);
        move_uploaded_file($_FILES['file']['tmp_name'][$i],'../fichiers/'.$nomFichier.'.'.$extension);
        $filePath = '../fichiers/'.$nomFichier.'.'.$extension;
        $size = $_FILES['file']['size'][$i];
        if(str_contains($_FILES['file']['type'][$i], "video")){
            // We create thumbnail
            copy('../images/thumbnail.png', '../miniatures/'.$nomFichier.'.png');
            // We get duration
            $getID3 = new getID3;
            $file = $getID3->analyze($filePath);
            $duree = date('H:i:s', round($file['playtime_seconds']));
        }
        else{
            $duree = '00:00:00';
            imagepng(imagecreatefromstring(file_get_contents($filePath)), '../miniatures/'.$nomFichier.'.png');
        }
        $path = '../miniatures/'.$nomFichier.'.png';
        createThumbnail($path, $path, 267, 197);

        if($tags_file == null) $tags_file[]="Sans tag";
        $requete = "INSERT INTO fichiers (`id`, `nom_fichier`, `extension`, `auteur`, `date`, `duree`, `size`, `nom_stockage`) VALUES ('$id', '$filename', '$extension', '$mail', '$date', '$duree', '$size','$nomFichier')";
        mysqli_query($link, $requete);
        unset($getID3);
        $chaine = "";
        foreach ($tags_file as $tag){
            $chaine .= $tag." ";
            $requete = "INSERT INTO caracteriser (`id_fichier`, `nom_tag`) VALUES ('$id', '$tag')";
            mysqli_query($link, $requete);
        }
        $requete2 = "INSERT INTO `tableau_de_bord` (`modification`) VALUES ('Compte ".$lastname." ".$name." (".$role2.") a téléversé le fichier ".$filename." avec le(s) tag(s) : ".$chaine."')";
        mysqli_query($link, $requete2);
    }
}

header('Location:../my_files.php');
