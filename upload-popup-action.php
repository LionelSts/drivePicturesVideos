<?php
session_start();
$str_arr = array();
foreach ($_POST as $key => $value){
    if($key != "submit" && $key != "newTag"){
        $chaine = explode ("-", $key);
        array_push($str_arr, $chaine);
    }
}
require_once('getID3-master/getid3/getid3.php');
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;
$link->query('SET NAMES utf8');
$countfiles = count($_FILES['file']['name']);
$requete = "SELECT `id` FROM `fichiers` ORDER BY `id` DESC LIMIT 1";
$result = mysqli_query($link, $requete);
$id = mysqli_fetch_array($result)['id'];
$mail = $_SESSION['mail'];
$date = date('Y-m-d');
$tags_file = "";
$requete = "SELECT `nom_tag` FROM `tags`";
$requestTags = mysqli_query($link, $requete)->fetch_all(MYSQLI_ASSOC);
$tagList = array();
foreach ($requestTags as $value){
    $tagList[] = $value["nom_tag"];
}

print_r($str_arr);
foreach ($str_arr as $tag){
    $isIn = array_search($tag[1], $tagList);
    if(!$isIn){
        $requete = "INSERT INTO tags (`nom_tag`, `nom_categorie`) VALUES ('$tag[1]', '$tag[0]')";
        $result = mysqli_query($link, $requete);
    }
    $tags_file .= $tag[1]."-";
}
for($i = 0 ; $i < $countfiles ; $i++){
    $ext = $_FILES['file']['type'][$i];
    $filename = $_FILES['file']['name'][$i];
    if(preg_match("/image|video/", $_FILES['file']['type'][$i])){
        $id++;
        $extension = str_replace("video/", "", $ext);
        $extension = str_replace("image/", "", $ext);
        $filename = str_replace('.'.$extension, "", $filename);
        move_uploaded_file($_FILES['file']['tmp_name'][$i],'fichiers/'.$id.'.'.$extension);
        $filePath = 'fichiers/'.$id.'.'.$extension;
        $getID3 = new getID3;
        $file = $getID3->analyze($filePath);
        $size = $_FILES['file']['size'][$i];
        if(preg_match("/video/", $_FILES['file']['type'][$i])){
            $duree = date('H:i:s', round($file['playtime_seconds']));
        }
        else{
            $duree = '00:00:00';
        };
        $requete = "INSERT INTO fichiers (`id`, `nom_fichier`, `extension`, `auteur`, `date`, `duree`, `size`) VALUES ('$id', '$filename', '$extension', '$mail', '$date', '$duree', '$size')";
        $result = mysqli_query($link, $requete);
        $requete = "INSERT INTO caracteriser (`id_fichier`, `nom_tag`) VALUES ('$id', '$tags_file')";
        $result = mysqli_query($link, $requete);
        unset($getID3);
    }
}
// echo '<script>window.location.replace("my_files.php")</script>';
?>
