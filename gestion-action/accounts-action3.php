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
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $message = file_get_contents('template.html');
    $tmpPassword = bin2hex(random_bytes(24));
    $hashedPassword = password_hash($tmpPassword, PASSWORD_BCRYPT);
    $message = str_replace('registerLink', 'register.php?tmpPsw='.$tmpPassword, $message);
    $requete = "UPDATE utilisateurs SET `mot_de_passe` = '$hashedPassword' WHERE `mail` = '$mail'"; // So we create your account in our database
    $result = mysqli_query($link,$requete); // the request itself
    $subject = 'Votre compte Drive Les Briques Rouges';
    mail($mail, $subject, $message, $headers);
    header('location:../accounts.php');
?>
