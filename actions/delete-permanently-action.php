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
    $requete = "SELECT `nom_stockage`, `extension`, `nom_fichier` FROM `corbeille` WHERE `id` = $fileName";
    $data = mysqli_query($link, $requete);
    $results = mysqli_fetch_array($data);
    $nom_stockage = $results['nom_stockage'];
    $extension = $results['extension'];
    $nom_fichier = $results['nom_fichier'];
    unlink('../corbeille/'.$nom_stockage.'.'.$extension);
    unlink('../corbeille/miniature-'.$nom_stockage.'.png');                                                     // On supprime définitivement les fichiers (serveur)
    $requete = "DELETE FROM `corbeille` WHERE `id` = $fileName";                                                        // On supprime définitivement les fichiers (bdd)
    mysqli_query($link, $requete);
    $requete2 = "INSERT INTO `tableau_de_bord` (`modification`) VALUES ('Compte ".$lastname." ".$name." (".$role2.") a supprimé définitivement le fichier : ".$nom_fichier." ')";   // On logs les infos
    mysqli_query($link, $requete2);
    $requete2 = "INSERT INTO `tableau_de_bord` (`modification`) VALUES ('Un fichier a été supprimé automatiquement de la corbeille : ".$nom_fichier." (30 jours dépassés) ')";   // On logs les infos
    mysqli_query($link, $requete2);
}
header('Location:../'.$page.'.php');
