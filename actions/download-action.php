<?php
ob_start();                                                                                                             // gère l'intégrité des fichiers créés
$files = explode(",",$_POST["fichiers"]);
$link = mysqli_connect("127.0.0.1", "root", "", "drivelbr");
$link->query('SET NAMES utf8');
$requete = "SELECT `nom_stockage`, `extension`, `nom_fichier` FROM `fichiers` WHERE `id` IN (";                                        // On génère la requête qui récupère tous les noms et extensions
foreach ($files as $file){
    $file = substr($file,0,strpos($file, '.'));
    $requete .= $file.",";
}
$requete = substr($requete, 0, -1).")";
$result = mysqli_query($link, $requete);
$data = mysqli_fetch_all($result);
$zipname = '../temporary/driveLBR.zip';
$zip = new ZipArchive;
$zip->open($zipname, ZipArchive::CREATE);
foreach ($data as $file) {                                                                                              // On met tous les fichiers dasn un zip avec leur nom de fichier (pas le nom de stockage)
    $zip->addFile("../fichiers/".$file[0].".".$file[1], $file[2].".".$file[1]);
}
$zip->close();

if (headers_sent()) {
    echo 'HTTP header already sent';
} else {
    if (!is_file($zipname)) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');                                                   // Si le fichier n'existe pas, error 404
        echo 'File not found';
    } else if (!is_readable($zipname)) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');                                                   // Si le fichier n'est pas accesible pas, error 403
        echo 'File not readable';
    } else {
        header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');                                                          // Si tout va bien on initialize le header pour les données que nous allons envoyer (un zip)
        header('Content-Type: application/zip');
        header("Content-Transfer-Encoding: Binary");
        header('Content-Length: ' . filesize($zipname));
        header("Content-Disposition: attachment; filename=\"" . basename($zipname) . "\"");
        while (ob_get_level()) {                                                                                        // On vérifie que les données que nous avons ne sont pas corompus
            ob_end_clean();
        }
        readfile($zipname);                                                                                             // On envoi le fichier
        ignore_user_abort(true);                                                                                 // On continue le script même si l'utilisateur abandonne
        unlink($zipname);                                                                                               // On supprime le zip
        exit();
    }
}
