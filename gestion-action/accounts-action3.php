<?php
    session_start();
    $link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;
    $chaine = urldecode(file_get_contents('php://input'));
    $mail = str_replace("=Renvoyer le mail",'', $chaine);
    $requete = "SELECT  `prenom`, `nom` FROM utilisateurs WHERE `mail`='$mail'";
    $result = mysqli_query($link,$requete);
    while($row = mysqli_fetch_array($result)) // Searching the right line
    {
        $nom = $row['nom']; // Saving the user ID needed later
        $prenom = $row['prenom'];
    }
    $message = "Bonjour ".$prenom. " " .$nom.",\r\n Veuillez choisir votre mot de passe en cliquant sur le lien suivant : http://localhost/driveBriquesRouges/register.html ";
    mail($mail, "Confirmation d'inscription", $message);
    header('location:../accounts.php');
?>