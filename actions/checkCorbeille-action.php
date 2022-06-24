<?php
// Page de suppression des anciens éléments de la corbeille
$current_date = new DateTime('now'); // On récupère la date
date_sub($current_date,date_interval_create_from_date_string("30 days")); // On soustrait 30 jours à la date
$limit_date = date_format($current_date,"Y-m-d H:i:s"); // on formate la date
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;  // connexion à la base de données
$link->query('SET NAMES utf8');
$requete = "SELECT `nom_stockage`, `extension`, `nom_fichier` FROM `corbeille` WHERE `supprime_date` <= ?"; // On vérifie les fichiers qui sont dans la corbeille depuis plus de 30 jours
$stmt = $link->prepare($requete);
$stmt->bind_param("s", $limit_date);
$stmt->execute();
$result = $stmt->get_result();
$fichiers = mysqli_fetch_all($result);
if(!empty($fichiers)){ // Si il y a des fichiers
    $requete = "DELETE  FROM `corbeille` WHERE `supprime_date` <= ?"; // On les supprime de la bdd
    $stmt = $link->prepare($requete);
    $stmt->bind_param("s", $limit_date);
    $stmt->execute();
    foreach ($fichiers as $fichier){
        unlink("./corbeille/".$fichier[0].'.'.$fichier[1]);
        unlink("./corbeille/miniature-".$fichier[0].'.'.$fichier[1]); // On supprime les fichiers du serveur
        $requete = "INSERT INTO `tableau_de_bord` (`modification`) VALUES (CONCAT('Un fichier a été supprimé automatiquement de la corbeille : ',?,' (30 jours dépassés) '))";   // On log les infos pour le journal de bord
        $stmt = $link->prepare($requete);
        $stmt->bind_param("s", $fichier[2]);
        $stmt->execute();
    }
}

