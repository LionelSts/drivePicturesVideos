<?php
$id = $_POST['id'];
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;  // connexion à la base de données
$link->query('SET NAMES utf8');
$requete = "SELECT `nom_fichier`, `date`, `duree`, `size`, `auteur` FROM `fichiers` WHERE `id` = ?";
$stmt = $link->prepare($requete);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$file_data = mysqli_fetch_all($result);
$requete = "SELECT `nom_tag` FROM `caracteriser` WHERE `id_fichier` = ?";                                           // On récupère les tags du fichier
$stmt = $link->prepare($requete);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$fileTags = mysqli_fetch_all($result);
$taglist="";
foreach ($fileTags as $key=>$value){
    $taglist .= $value[0] ." ";
}
$mail = $file_data[0][4];
$requete = "SELECT `nom`, `prenom` FROM `utilisateurs` WHERE `mail` = ?";                                         // On récupère le nom de l'auteur à partir de son mail
$stmt = $link->prepare($requete);
$stmt->bind_param("s", $mail);
$stmt->execute();
$result = $stmt->get_result();
$name = mysqli_fetch_all($result);
function filesize_formatted($size) {                                                                                    // Fonction pour convertir des bits à une unité plus significative
    $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $power = $size > 0 ? floor(log($size, 1024)) : 0;
    return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
}

echo("
    <div id='FileDataRequest' class='clickMenu'> 
        <div id='Titre'><p>".$file_data[0][0]."</p></div>
        <div id='Auteur'><p>".$name[0][0]." ".$name[0][1]."</p></div>
        <div id='Tags'><p>".$taglist."</p></div>
        <div id='Date'><p>".$file_data[0][1]."</p></div>
        <div id='Taille_clicDroit'><p>".filesize_formatted($file_data[0][3])."</p></div>
        <div id='Durée'><p>".$file_data[0][2]."</p></div>
    </div>"
);                                                                                                                      // Le menu que l'on affiche
