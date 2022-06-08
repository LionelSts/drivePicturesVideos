<?php

require 'vendor/autoload.php';

session_start();
$str_arr = array();
foreach ($_POST as $key => $value){
    if($key != "submit" && $key != "newTag"){
        $chaine = explode ("-", $key);
        $str_arr[] = $chaine;
    }
}
require_once('getID3-master/getid3/getid3.php');
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
$tags_file = "";
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
    $tags_file .= $tag[1]." ";
}
for($i = 0 ; $i < $countfiles ; $i++){
    $ext = $_FILES['file']['type'][$i];
    $filename = $_FILES['file']['name'][$i];
    if(preg_match("/image|video/", $_FILES['file']['type'][$i])){
        $id++;
        $extension = str_replace("video/", "", $ext);
        $extension = str_replace("image/", "", $extension);
        $filename = str_replace('.'.$extension, "", $filename);
        move_uploaded_file($_FILES['file']['tmp_name'][$i],'fichiers/'.$id.'.'.$extension);
        $filePath = 'fichiers/'.$id.'.'.$extension;
        $getID3 = new getID3;
        $file = $getID3->analyze($filePath);
        $size = $_FILES['file']['size'][$i];
        if(str_contains($_FILES['file']['type'][$i], "video")){

            $duree = date('H:i:s', round($file['playtime_seconds']));
            // We create thumbnail
            $ffmpeg = FFMpeg\FFMpeg::create();
            $video = $ffmpeg->open('./fichiers/'.$id.'.'.$extension);
            $video
                ->filters()
                ->resize(new FFMpeg\Coordinate\Dimension(320, 240))
                ->synchronize();
            $video
                ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(5))
                ->save('./mignatures/'.$id.'.png');
        }
        else{
            $duree = '00:00:00';
            imagepng(imagecreatefromstring(file_get_contents('./fichiers/'.$id.'.'.$extension)), './mignatures/'.$id.'.png');
        }

        // Chargement
        $thunmnailName = './mignatures/'.$id.'.png';
        list($width, $height) = getimagesize($thunmnailName);
        $thumb = imagecreatetruecolor(267, 197);
        $source = imagecreatefrompng($thunmnailName);
        $height = round($width * 0.74);
        $src_y = 0;
        if($width < $height){
            $src_y = round($height/2);
        }
        if($tags_file == null) $tags_file="Sans tag";
// Redimensionnement
        imagecopyresized($thumb, $source, 0, 0, 0, $src_y, 267, 197, $width, $height);
        imagepng($thumb, $thunmnailName);
        $requete = "INSERT INTO fichiers (`id`, `nom_fichier`, `extension`, `auteur`, `date`, `duree`, `size`) VALUES ('$id', '$filename', '$extension', '$mail', '$date', '$duree', '$size')";
        $result = mysqli_query($link, $requete);
        $requete = "INSERT INTO caracteriser (`id_fichier`, `nom_tag`) VALUES ('$id', '$tags_file')";
        $result = mysqli_query($link, $requete);
        unset($getID3);
    }
}
header('Location:my_files.php');
