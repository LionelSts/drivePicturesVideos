<?php
$id = $_POST['id'];
$link = mysqli_connect("127.0.0.1", "root", "", "drivelbr");  // connexion à la base de données
$link->query('SET NAMES utf8');
$requete = "SELECT `nom_fichier`, `date`, `duree`, `size`, `auteur`, `supprime_date`, `supprime_par` FROM `corbeille` WHERE `id` = ?";
$stmt = $link->prepare($requete);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$file_data = mysqli_fetch_all($result);
$mail1 = $file_data[0][4];
$mail2 = $file_data[0][6];
$requete = "SELECT `nom`, `prenom` FROM `utilisateurs` WHERE `mail` = ?";                                               // On récupère le nom de l'auteur à partir de son mail
$stmt = $link->prepare($requete);
$stmt->bind_param("s", $mail1);
$stmt->execute();
$result = $stmt->get_result();
$name = mysqli_fetch_all($result);
$requete = "SELECT `nom`, `prenom` FROM `utilisateurs` WHERE `mail` = ?";                                               // On récupère le nom de la personne qui l'a supprimé à partir de son mail
$stmt = $link->prepare($requete);
$stmt->bind_param("s", $mail2);
$stmt->execute();
$result = $stmt->get_result();
$name1 = mysqli_fetch_all($result);
function filesize_formatted($size)
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');                                                // Fonction pour convertir des bits à une unité plus significative
    $power = $size > 0 ? floor(log($size, 1024)) : 0;
    return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
}

echo("
    <div id='FileDataRequest' class='clickMenu'> 
        <div id='Titre'><p>" . htmlspecialchars($file_data[0][0], ENT_QUOTES, 'UTF-8') . "</p></div>
        <div id='Auteur'><p>" . htmlspecialchars($name[0][0], ENT_QUOTES, 'UTF-8') . " " . htmlspecialchars($name[0][1], ENT_QUOTES, 'UTF-8') . "</p></div>
        <div id='Date'><p>" . $file_data[0][1] . "</p></div>
        <div id='Taille_clicDroit'><p>" . filesize_formatted($file_data[0][3]) . "</p></div>
        <div id='Durée'><p>" . $file_data[0][2] . "</p></div>
        <div id='Supprime'><p> Supprimé par " . htmlspecialchars($name1[0][0], ENT_QUOTES, 'UTF-8') . " " . htmlspecialchars($name1[0][1], ENT_QUOTES, 'UTF-8') . "</p></div>
        <div id='supprimeDate'><p> Supprimé le " . $file_data[0][5] . "</p></div>
    </div>"
);                                                                                                                      // Le menu que l'on affiche
