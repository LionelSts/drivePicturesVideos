<?php
$id = $_POST['id'];
$link = mysqli_connect("127.0.0.1", "root", "", "drivelbr");  // connexion à la base de données
$link->query('SET NAMES utf8');
$requete = "SELECT `nom_fichier`, `date`, `duree`, `size`, `auteur`, `supprime_date`, `supprime_par` FROM `corbeille` WHERE `id` = '$id'";
$result = mysqli_query($link, $requete); // Saving the result
$file_data = mysqli_fetch_all($result);
$mail1 = $file_data[0][4];
$mail2 = $file_data[0][6];
$requete = "SELECT `nom`, `prenom` FROM `utilisateurs` WHERE `mail` = '$mail1'";
$result = mysqli_query($link, $requete); // Saving the result
$name = mysqli_fetch_all($result);
$requete = "SELECT `nom`, `prenom` FROM `utilisateurs` WHERE `mail` = '$mail2'";
$result = mysqli_query($link, $requete); // Saving the result
$name1 = mysqli_fetch_all($result);
function filesize_formatted($size)
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $power = $size > 0 ? floor(log($size, 1024)) : 0;
    return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
}

echo("
    <div id='FileDataRequest' class='clickMenu'> 
        <div id='Titre'><p>" . $file_data[0][0] . "</p></div>
        <div id='Auteur'><p>" . $name[0][0] . " " . $name[0][1] . "</p></div>
        <div id='Date'><p>" . $file_data[0][1] . "</p></div>
        <div id='Taille_clicDroit'><p>" . filesize_formatted($file_data[0][3]) . "</p></div>
        <div id='Durée'><p>" . $file_data[0][2] . "</p></div>
        <div id='Supprime'><p> Supprimé par " . $name1[0][0] . " " . $name1[0][1] . "</p></div>
        <div id='supprimeDate'><p> Supprimé le " . $file_data[0][5] . "</p></div>
    </div>"
);
