<?php
$id = $_POST['id'];
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;  // connexion à la base de données
$link->query('SET NAMES utf8');
$requete = "SELECT `nom_fichier`, `date`, `duree`, `size`, `auteur` FROM `fichiers` WHERE `id` = '$id'";
$result = mysqli_query($link, $requete); // Saving the result
$file_data = mysqli_fetch_all($result);
$requete = "SELECT `nom_tag` FROM `caracteriser` WHERE `id_fichier` = '$id'";
$result = mysqli_query($link, $requete); // Saving the result
$fileTags = mysqli_fetch_all($result);
$taglist="";
foreach ($fileTags as $key=>$value){
    $taglist .= $value[0] ." ";
}
$test = $file_data[0][4];
$requete = "SELECT `nom`, `prenom` FROM `utilisateurs` WHERE `mail` = '$test'";
$result = mysqli_query($link, $requete); // Saving the result
$name = mysqli_fetch_all($result);
function filesize_formatted($size) {
    $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $power = $size > 0 ? floor(log($size, 1024)) : 0;
    return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
}

Source: https://prograide.com/pregunta/26869/conversion-de-la-taille-des-fichiers-php-en-mbkb
echo("
    <div id='FileDataRequest' class='clickMenu'> 
        <div id='Titre'><p>".$file_data[0][0]."</p></div>
        <div id='Auteur'><p>".$name[0][0]." ".$name[0][1]."</p></div>
        <div id='Tags'><p>".$taglist."</p></div>
        <div id='Date'><p>".$file_data[0][1]."</p></div>
        <div id='Taille_clicDroit'><p>".filesize_formatted($file_data[0][3])."</p></div>
        <div id='Durée'><p>".$file_data[0][2]."</p></div>
    </div>"
);
?>
