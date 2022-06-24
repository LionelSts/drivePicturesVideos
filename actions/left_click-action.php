<?php
session_start();
// On génère l'html du clic
$role = $_SESSION['role'];
$name = $_POST['name'];
$downloadFile = "<div id='Telecharger'><p onclick='downloadFiles(`".$name."`)'>Télécharger</p></div>";
$deleteFile="";
$modifTags="";
if($role == 'ecriture' || $role == 'admin'){                                                            // Si l'utilisateur a les droits il a l'option de supprimer le fichier ou de modifier les tags
    $deleteFile = "<div id='Supprimer'><p onclick='deleteFiles(`".$name."`)'>Supprimer</p></div>";
    $modifTags = "<div id='modifTags'><p onclick='tagSelection(`".$name."`)'>Modifier les tags</p></div>";
}

echo("
    <div id='FileDataRequest' class='clickMenu'>"
    .$downloadFile
    .$deleteFile
    .$modifTags.
    "</div>"
);
