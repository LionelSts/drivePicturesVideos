<?php
session_start();    // démarrage de la session
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
foreach ($files as $file){                                                                                              // Pour chaque fichier à restaurer
    $id++;
    $fileName = substr($file,0,strpos($file, '.'));
    $requete = "SELECT * FROM `corbeille` WHERE `id` = ?";                                                      // On récupère ses infos
    $stmt = $link->prepare($requete);
    $stmt->bind_param("i", $fileName);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = mysqli_fetch_array($result);
    $nom_fichier = $data['nom_fichier'];
    $extension = $data['extension'];
    $auteur = $data['auteur'];
    $date = $data['date'];
    $duree = $data['duree'];
    $size = $data['size'];
    $nom_stockage = $data['nom_stockage'];
    $delete_date = date('Y-m-d H:i:s');
    $delete_user = $_SESSION['mail'];
    rename( '../corbeille/'.$nom_stockage.'.'.$extension, '../fichiers/'.$nom_stockage.'.'.$extension);        // On déplace le fichier
    rename( '../corbeille/miniature-'.$nom_stockage.'.png', '../miniatures/'.$nom_stockage.'.png');            // On déplace la miniature
    $requete = "DELETE FROM `corbeille` WHERE `id` = ?";                                                        // on supprime la ligne de ce fichier dans la bdd de la corbeille
    $stmt = $link->prepare($requete);
    $stmt->bind_param("i", $fileName);
    $stmt->execute();
    $requete = "INSERT INTO `fichiers` VALUES (?,?,?,?,?,?,?,?) ";
    $stmt = $link->prepare($requete);
    $stmt->bind_param("isssssis", $id,$nom_fichier,$extension,$auteur,$date,$duree,$size,$nom_stockage);
    $stmt->execute();                                                                                   // On stocke a nouveau les infos dans la table fichiers
    $requete = "INSERT INTO `caracteriser` VALUES (?,'Sans tag')";
    $stmt = $link->prepare($requete);
    $stmt->bind_param("i", $id);
    $stmt->execute();                                                                                     // On lui attribue un tag Sans tag
    $requete = "INSERT INTO `tableau_de_bord` (`modification`) VALUES (CONCAT('Compte ',?,' ',?,' (',?,') a restauré le fichier : ',?))";
    $stmt = $link->prepare($requete);
    $stmt->bind_param("ssss", $lastname,$name,$role2,$nom_fichier);
    $stmt->execute();
}
header('Location:../'.$page.'.php');
