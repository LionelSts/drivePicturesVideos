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
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $message = file_get_contents('template.html');
    $tmpPassword = bin2hex(random_bytes(24));   // génération automatique d'un mot de passe permetant "l'unicité" du lien
    $hashedPassword = password_hash($tmpPassword, PASSWORD_BCRYPT); // hashing du mot de passe
    $message = str_replace('registerLink', 'register.php?tmpPsw='.$tmpPassword, $message);  // message du mail avec lien
    $requete = "UPDATE utilisateurs SET `mot_de_passe` = '$hashedPassword' WHERE `mail` = '$mail'"; // On met à jour la bdd avec ce mot de passe
    $result = mysqli_query($link,$requete);
    $subject = 'Votre compte Drive Les Briques Rouges'; // sujet du mail
    mail($mail, $subject, $message, $headers);  // envoi du mail
    header('location:../accounts.php'); // on renvoie l'utilisateur vers la page "accounts.php"
?>
