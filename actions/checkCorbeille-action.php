<?php

$current_date = new DateTime('now');;
date_sub($current_date,date_interval_create_from_date_string("30 days"));
$limit_date = date_format($current_date,"Y-m-d H:i:s");
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;  // connexion à la base de données
$link->query('SET NAMES utf8');
$requete = "SELECT `nom_stockage`, `extension` FROM `corbeille` WHERE `supprime_date` <= '$limit_date'";
$result = mysqli_query($link, $requete);
$fichiers = mysqli_fetch_all($result);
if(!empty($fichiers)){
    $requete = "DELETE  FROM `corbeille` WHERE `supprime_date` <= '$limit_date'";
    mysqli_query($link, $requete);
    foreach ($fichiers as $fichier) unlink("./corbeille/".$fichier[0].'.'.$fichier[1]); unlink("./corbeille/miniature-".$fichier[0].'.'.$fichier[1]);
}
