<?php
session_start();// démarrage de la session
require '../vendor/autoload.php';                                                                                       // on include les modules composer (gestionnaire de librairies en PHP)
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
}                                                                                                                       // On récupère tous les tags attribués
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;
$link->query('SET NAMES utf8');
$countfiles = count($_FILES['file']['name']);
$requete = "SELECT `id` FROM `fichiers` ORDER BY `id` DESC LIMIT 1";                                                    // On voit où nous en sommes dans la bdd
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
$requete = "SELECT `nom_tag` FROM `tags`";                                                                              // On récupère tous les noms de tags
$requestTags = mysqli_query($link, $requete)->fetch_all(MYSQLI_ASSOC);
$tagList = array();
foreach ($requestTags as $value){                                                                                       // On stocke tous les noms dans un tableau
    $tagList[] = strval($value["nom_tag"]);
}
foreach ($str_arr as $tag){                                                                                             // Pour chaque tag on vérifie qu'il soit déjà existant, sinon, on le créé
    $isIn = array_search(strval($tag[1]), $tagList, -1);
    if($isIn == -1){
        $requete = "INSERT INTO tags (`nom_tag`, `nom_categorie`) VALUES (?, ?)";
        $stmt = $link->prepare($requete);
        $stmt->bind_param("ss", $tag[1], $tag[0]);
        $stmt->execute();
        $requete = "INSERT INTO `tableau_de_bord` (`modification`) VALUES (CONCAT('Compte ',?,' ',?,' (',?,') a ajouté un tag ',?,' dans la catégorie ',?))";
        $stmt = $link->prepare($requete);
        $stmt->bind_param("sssss", $lastname, $name,$role2,$tag[1],$tag[0]);
        $stmt->execute();
    }
    $tags_file[]=str_replace("_", " ", $tag[1]);                                                           // On remplace les _ par des espaces (encodage lors du POST)
}
for($i = 0 ; $i < $countfiles ; $i++){                                                                                  // Pour chaque fichier uploadé
    $requete = "SELECT `nom_stockage` FROM `fichiers`";                                                                 // On récupère tous les noms de stockage dans fichier
    $result = mysqli_query($link, $requete);
    $filesName = [];
    while($row = mysqli_fetch_row($result)) $filesName[] = $row;                                                        // On les mets dans un tableau
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $nomFichier = '';
    for ($y = 0; $y < 65; $y++) {
        $nomFichier .= $characters[rand(0, strlen($characters)-1)];                                                     // On génère un nom aléatoirement de caractères
    }
    while(array_search($nomFichier, $filesName)){                                                                       // Si il existe déjà, on recommence avec un autre jusqu'à ce qu'il soit nouveau
        $nomFichier = '';
        for ($y = 0; $y < 65; $y++) {
            $nomFichier .= $characters[rand(0, strlen($characters)-1)];
        }
    }
    $ext = $_FILES['file']['type'][$i];
    $filename = $_FILES['file']['name'][$i];
    if(preg_match("/image|video/", $_FILES['file']['type'][$i])){                                                // On vérifie que le fichier soit une image ou une video
        $id++;
        $extension = str_replace("video/", "", $ext);
        $extension = str_replace("image/", "", $extension);                                                // On recupère l'extension
        if($extension){
            $filename = str_replace('.jpeg', "", $filename);
            $filename = str_replace('.jpg', "", $filename);                                                // Si c'est un jpeg ou jpg on enlève l'extension (un format jpg peut avoir une extension jpeg)
        }
        if($extension == "quicktime"){
            $filename = str_replace('.mov', "", $filename);
            $extension = "mov";                                                // Si c'est un mov on enlève l'extension (le format est quicktime )
        }
        $filename = str_replace('.'.$extension, "", $filename);                                            // On enlève l'extension (pour avoir le nom du fichier)
        move_uploaded_file($_FILES['file']['tmp_name'][$i],'../fichiers/'.$nomFichier.'.'.$extension);                  // On met le fichier téléversé dans le dossier de stockage serveur
        $filePath = '../fichiers/'.$nomFichier.'.'.$extension;
        $size = $_FILES['file']['size'][$i];
        if(str_contains($_FILES['file']['type'][$i], "video")){                                                   // Si c'est une vidéo on prend la miniature type et récupère la durée
            // We create thumbnail
            copy('../images/thumbnail.png', '../miniatures/'.$nomFichier.'.png');
            // We get duration
            $getID3 = new getID3;
            $file = $getID3->analyze($filePath);
            $duree = date('H:i:s', round($file['playtime_seconds']));
        }
        else{
            $duree = '00:00:00';
            imagepng(imagecreatefromstring(file_get_contents($filePath)), '../miniatures/'.$nomFichier.'.png');     // Si c'est une image on créé une miniature et met la durée à 0
        }
        $path = '../miniatures/'.$nomFichier.'.png';
        createThumbnail($path, $path, 267, 197);                                                           // On redimensionne la miniature au bon format

        if($tags_file == null) $tags_file[]="Sans tag";                                                                 // Si il n'y a pas de tag on le défini sans tag
        $requete = "INSERT INTO fichiers (`id`, `nom_fichier`, `extension`, `auteur`, `date`, `duree`, `size`, `nom_stockage`) VALUES (?, ?, ?, ?, ?, ?, ?,?)";
        $stmt = $link->prepare($requete);
        $stmt->bind_param("isssssis", $id, $filename,$extension,$mail,$date,$duree,$size,$nomFichier);
        $stmt->execute();                                                                                               // on ajoute le fichier à la bdd
        unset($getID3);
        $chaine = "";
        foreach ($tags_file as $tag){
            $chaine .= $tag." ";
            $requete = "INSERT INTO caracteriser (`id_fichier`, `nom_tag`) VALUES (?,?)";
            $stmt = $link->prepare($requete);
            $stmt->bind_param("is", $id, $tag);
            $stmt->execute();
        }                                                                                                               // On stocke les tags par fichier
        $requete = "INSERT INTO `tableau_de_bord` (`modification`) VALUES (CONCAT('Compte ',?,' ',?,' (',?,') a téléversé le fichier ',?,' avec le(s) tag(s) : ',?))";
        $stmt = $link->prepare($requete);
        $stmt->bind_param("sssss", $lastname, $name,$role2,$filename,$chaine);
        $stmt->execute();
    }
}

header('Location:../my_files.php');
