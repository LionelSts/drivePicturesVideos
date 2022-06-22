<?php
    session_start();     // démarage de la session
    if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("./index.php");</script>';  // redirection vers le login si l'utilisateur n'est pas connecté
    $link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;  // connexion à la base de données
    $link->query('SET NAMES utf8');
    $chaine = urldecode(file_get_contents('php://input'));  // récupération d'une chaine contenant le mail sélectionné
    $mail = str_replace("=Renvoyer le mail",'', $chaine);   // suppression des éléments inutiles de cette chaine
    $requete = "SELECT  `prenom`, `nom` FROM utilisateurs WHERE `mail`='$mail'";    // recherche dans la bdd du nom et prénom associé à cet email
    $result = mysqli_query($link,$requete);
    while($row = mysqli_fetch_array($result)) // enregistrement du nom et du prénom associé à cet email
    {
        $nom = $row['nom'];
        $prenom = $row['prenom'];
    }
try {
    $tmpPassword = bin2hex(random_bytes(24));
} catch (Exception $e) {
}   // génération automatique d'un mot de passe permetant "l'unicité" du lien
    $hashedPassword = password_hash($tmpPassword, PASSWORD_BCRYPT); // hashing du mot de passe
    $requete = "UPDATE utilisateurs SET `mot_de_passe` = '$hashedPassword' WHERE `mail` = '$mail'"; // On met à jour la bdd avec ce mot de passe
    $result = mysqli_query($link,$requete);
    $data = [
        'mailType' => 'renvoyer',
        'mailTo' => $mail,
        'tmpPsw' => $hashedPassword,
        'nom' => $nom,
        'prenom' => $prenom
    ];
    $curl = curl_init('http://test-mail.lesbriquesrouges.fr/mails_grp12/sendMail.php');
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($curl);
    print_r($result);
    curl_close($curl);
    // header('location:../accounts.php'); // on renvoie l'utilisateur vers la page "accounts.php"
