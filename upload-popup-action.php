<?php
session_start();// démarage de la session
require 'vendor/autoload.php';
include 'thumbnail.php';
if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("./index.php");</script>';
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
    }
    $tags_file[]=str_replace("_", " ", $tag[1]);
}
for($i = 0 ; $i < $countfiles ; $i++){
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
        move_uploaded_file($_FILES['file']['tmp_name'][$i],'fichiers/'.$id.'.'.$extension);
        $filePath = 'fichiers/'.$id.'.'.$extension;
        $size = $_FILES['file']['size'][$i];
        if(str_contains($_FILES['file']['type'][$i], "video")){
            // We create thumbnail
            $ffmpeg = FFMpeg\FFMpeg::create();
            $video = $ffmpeg->open('./fichiers/'.$id.'.'.$extension);
            $video
                ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(5))
                ->save('./mignatures/'.$id.'.png');
            $ffprobe = FFMpeg\FFProbe::create();
            $duree = $ffprobe
                ->format('./fichiers/'.$id.'.'.$extension) // extracts file informations
                ->get('duration');             // returns the duration property
        }
        else{
            $duree = '00:00:00';
            imagepng(imagecreatefromstring(file_get_contents('./fichiers/'.$id.'.'.$extension)), './mignatures/'.$id.'.png');
        }
        $path = './mignatures/'.$id.'.png';
        createThumbnail($path, $path, 267, 197);

        if($tags_file == null) $tags_file[]="Sans tag";
        $requete = "INSERT INTO fichiers (`id`, `nom_fichier`, `extension`, `auteur`, `date`, `duree`, `size`) VALUES ('$id', '$filename', '$extension', '$mail', '$date', '$duree', '$size')";
        mysqli_query($link, $requete);
        foreach ($tags_file as $tag){
            $requete = "INSERT INTO caracteriser (`id_fichier`, `nom_tag`) VALUES ('$id', '$tag')";
            mysqli_query($link, $requete);
        }
    }
}

header('Location:my_files.php');
