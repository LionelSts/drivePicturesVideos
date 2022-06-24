<?php
session_start();    // démarage de la session
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;  // connexion à la base de données
$link->query('SET NAMES utf8');
$name = $_SESSION["prenom"];
$lastname = $_SESSION["nom"];
$role2 = $_SESSION["role"];
$files = explode(",",$_POST["fichiers"]);                                                                       // On récupère tous les fichiers à supprimmer définitivement
$page = $_POST['page'];
foreach ($files as $file){                                                                                              // Pour chaque fichier
    $fileName = substr($file,0,strpos($file, '.'));
    $requete = "SELECT `nom_stockage`, `extension`, `nom_fichier` FROM `corbeille` WHERE `id` = ?";
    $stmt = $link->prepare($requete);
    $stmt->bind_param("i", $fileName);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = mysqli_fetch_array($result);
    $nom_stockage = $data['nom_stockage'];
    $extension = $data['extension'];
    $nom_fichier = $data['nom_fichier'];
    unlink('../corbeille/'.$nom_stockage.'.'.$extension);
    unlink('../corbeille/miniature-'.$nom_stockage.'.png');                                                     // On supprime définitivement les fichiers (serveur)
    $requete = "DELETE FROM `corbeille` WHERE `id` = ?";                                                        // On supprime définitivement les fichiers (bdd)
    $stmt = $link->prepare($requete);
    $stmt->bind_param("i", $fileName);
    $stmt->execute();
    $requete = "INSERT INTO `tableau_de_bord` (`modification`) VALUES (CONCAT('Compte ',?,' ',?,' (',?,') a supprimé définitivement le fichier : ',?))";   // On logs les infos
    $stmt = $link->prepare($requete);
    $stmt->bind_param("ssss", $lastname,$name,$role2,$nom_fichier);
    $stmt->execute();
}
header('Location:../'.$page.'.php');
