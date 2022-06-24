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
    $requete = "SELECT * FROM `fichiers` WHERE `id` = ?";
    $stmt = $link->prepare($requete);
    $stmt->bind_param("i", $fileName);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = mysqli_fetch_array($result);
    $nom_fichier = $data['nom_fichier'];                                                                             // On récupère toutes les infos
    $extension = $data['extension'];
    $auteur = $data['auteur'];
    $date = $data['date'];
    $duree = $data['duree'];
    $size = $data['size'];
    $nom_stockage = $data['nom_stockage'];
    $delete_date = date('Y-m-d H:i:s');
    $delete_user = $_SESSION['mail'];
    rename('../fichiers/'.$nom_stockage.'.'.$extension, '../corbeille/'.$nom_stockage.'.'.$extension);          // On déplace le fichier dans la corbeille (serveur)
    rename('../miniatures/'.$nom_stockage.'.png', '../corbeille/miniature-'.$nom_stockage.'.png');              // On déplace la miniature dans la corbeille (serveur)
    $requete = "DELETE FROM `fichiers` WHERE `id` = ?";
    $stmt = $link->prepare($requete);
    $stmt->bind_param("i", $fileName);
    $stmt->execute();
    $requete = "DELETE FROM `caracteriser` WHERE `id_fichier` = ?";                                              // On supprime les tags pour ce fichier (bdd)
    $stmt = $link->prepare($requete);
    $stmt->bind_param("i", $fileName);
    $stmt->execute();
    $requete = "INSERT INTO `corbeille` VALUES (?,?,?,?,?,?,?,?,?,?) ";   // On déplace le fichier dans la corbeille (bdd)
    $stmt = $link->prepare($requete);
    $stmt->bind_param("isssssisss", $id,$nom_fichier,$extension,$auteur,$date,$duree,$size,$nom_stockage,$delete_date,$delete_user);
    $stmt->execute();
    $requete = "INSERT INTO `tableau_de_bord` (`modification`) VALUES (CONCAT('Compte ',?,' ',?,' (',?,') ','a supprimé le fichier : ',?))";           // On log les infos pour le journal de bord
    $stmt = $link->prepare($requete);
    $stmt->bind_param("ssss", $lastname,$name,$role2,$nom_fichier);
    $stmt->execute();
}
header('Location:../'.$page.'.php');
