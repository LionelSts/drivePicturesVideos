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
$page = $_POST['page'];
foreach($files as $file){                       // On stock toutes les informations convenablement en séparant le nom d fichier (qui est aussi son ID) de l'extension
    $filesData[] = explode(".", $file);
}
$link = mysqli_connect("127.0.0.1", "root", "", "drivelbr");    // connexion à la base de données
$link->query('SET NAMES utf8');
foreach ($filesData as $file){                                              // On supprime toutes les lignes tags éxistantes pour ces fichiers
    $requete = "DELETE FROM `caracteriser` WHERE `id_fichier` = ?";
    $stmt = $link->prepare($requete);
    $stmt->bind_param("i", $file[0]);
    $stmt->execute();
}
$liste = "";
foreach ($filesData as $file){                                              // pour chaque fichier on lui attribue ses tags
    if(empty($tagsList)) $tagsList[] = "Sans tag";
    foreach($tagsList as $tag){
        $liste .= $tag." ";
        $requete = "INSERT INTO `caracteriser` (`id_fichier`,`nom_tag`) VALUES (?,?)";
        $stmt = $link->prepare($requete);
        $stmt->bind_param("is", $file[0],$tag);
        $stmt->execute();
        $requete0 = "SELECT `nom_fichier`,`id` FROM `fichiers` WHERE `id` =?";
        $stmt = $link->prepare($requete0);
        $stmt->bind_param("i", $file[0]);
        $stmt->execute();
        $result0 = $stmt->get_result();
        $row = mysqli_fetch_array($result0);
        $nomFichier = $row['nom_fichier'];
        $requete2 = "INSERT INTO `tableau_de_bord` (`modification`) VALUES (CONCAT('Compte ',?,' ',?,' ','(',?,')',' a mis le(s) tag(s) ',?,' sur le fichier ',?))";
        $stmt = $link->prepare($requete2);
        $stmt->bind_param("sssss", $lastname, $name, $role2, $liste, $nomFichier);
        $stmt->execute();
    }
}
header('location:../'.$page.'.php'); // on renvoie l'utilisateur vers la page "my_files.php"
