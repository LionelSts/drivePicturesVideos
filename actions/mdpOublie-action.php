<?php
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;  // connexion à la bdd
$email = $_POST["email"];
$requete = "SELECT `mail` FROM `utilisateurs` WHERE `mail` = '$email' ";
$result = mysqli_query($link, $requete);
$exist = mysqli_num_rows($result); // on associe la variable '$exists' au nombre d'apparition de l'email saisi dans la bdd
if($exist==1)   // si le mail existe dans la bdd...
{
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $message = file_get_contents('template.html');
    $tmpPassword = bin2hex(random_bytes(24));   // génération automatique d'un mot de passe permetant "l'unicité" du lien
    $hashedPassword = password_hash($tmpPassword, PASSWORD_BCRYPT); // hashing du mot de passe
    $message = str_replace('registerLink', 'register.php?tmpPsw='.$tmpPassword, $message);  // message du mail avec lien
    $subject = 'Cliquez sur le lien pour rénitialiser votre mot de passe'; // sujet du mail
    mail($email, $subject, $message, $headers);  // envoi du mail de confirmation
    echo '<script> alert("Un mail vous a été envoyé."); window.location.replace("../index.php"); </script>'; // on informe l'utilisateur et on le redirige vers la page d'accueil
}
else    // si le mail n'existe pas dans la bdd...
{
    echo '<script> alert("Le mail saisi est incorrect."); window.location.replace("../mdpOublie.php"); </script>'; // on informe l'utilisateur et on le redirige vers la page "mdpOublie.php"
}
