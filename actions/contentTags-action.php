<?php
session_start();
$name = $_SESSION["prenom"]; $lastname = $_SESSION["nom"]; $role2 = $_SESSION["role"];
$tagsList = $_POST['listeTag']; // récupération des tags
$chaine = urldecode(file_get_contents('php://input'));  // récupération de la liste des tags sélectionné en enlevant les données inutiles
$chaine = substr($chaine,strrpos($chaine, '&'));
$chaine = str_replace(",=Oui", ' ', $chaine);
$chaine = str_replace("&", '', $chaine);
$files = explode(',', $chaine);
$filesData = [];
foreach($files as $file){                       // On stock toutes les informations convenablement en séparant le nom d fichier (qui est aussi son ID) de l'extension
    $filesData[] = explode(".", $file);
}
$link = mysqli_connect("127.0.0.1", "root", "", "drivelbr");    // connexion à la base de données
$link->query('SET NAMES utf8');

foreach ($filesData as $file){                                              // On supprime toutes les lignes tags éxistantes pour ces fichiers
    $requete = "DELETE FROM `caracteriser` WHERE `id_fichier` = $file[0]";
    mysqli_query($link, $requete);
}
$liste = "";
foreach ($filesData as $file){                                              // pour chaque fichier on lui attribue ses tags
    foreach($tagsList as $tag){
        $liste .= $tag." ";
        $requete = "INSERT INTO `caracteriser` (`id_fichier`,`nom_tag`) VALUES ('$file[0]', '$tag')";
        mysqli_query($link, $requete);
        $requete0 = "SELECT `nom_fichier`,`id` FROM `fichiers` WHERE `id` ='$file[0]'";
        $result0 = mysqli_query($link, $requete0);
        $row = mysqli_fetch_array($result0);
        $nomFichier = $row['nom_fichier'];
        $requete2 = "INSERT INTO `tableau_de_bord` (`modification`) VALUES ('Compte ".$lastname." ".$name." (".$role2.") a mis le(s) tag(s) ".$liste." sur le fichier ".$nomFichier."')";
        mysqli_query($link, $requete2);

    }
}
header('location:../my_files.php'); // on renvoie l'utilisateur vers la page "my_files.php"
