<?php
    session_start();    // démarage de la session
    if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("./index.php");</script>';  // redirection vers le login si l'utilisateur n'est pas connecté
    $link = mysqli_connect("127.0.0.1", "root", "", "drivelbr");    // connexion à la base de données
    $nom = $_POST['nom'];   // récupération des données du formulaire de Création de compte
    $prenom = $_POST['prenom'];
    $mail = $_POST['mail'];
    $role = $_POST['role'];
    $psw = $_POST['password'];
    $descriptif = $_POST['descriptif'];
    $password = password_hash($psw, PASSWORD_BCRYPT); // Hashing du mot de passe récupéré
    $requete = "SELECT `mail` FROM `utilisateurs` Where `mail`= '$mail'"; // on vérifie dans la bdd que le mail saisi est bien disponible
    $result = mysqli_query($link,$requete);
    $exist = mysqli_num_rows($result); // on associe la variable '$exists' au nombre d'apparition de l'email saisi dans la bdd
    if($exist==1)   // si le mail existe déjà dans la bdd...
    {
        echo '<script> 
                alert("Le compte saisi existe déjà.");
                window.location.replace("../accounts.php");
              </script>'; // on informe l'utilisateur et on le redirige vers la page "accounts.php"
    }
    else    // si le mail n'existe pas dans la bdd...
    {
        if (isset($_POST['mdp'])) {
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $message = file_get_contents('template.html');
            $tmpPassword = bin2hex(random_bytes(24));   // génération automatique d'un mot de passe permetant "l'unicité" du lien
            $hashedPassword = password_hash($tmpPassword, PASSWORD_BCRYPT); // hashing du mot de passe
            $message = str_replace('registerLink', 'register.php?tmpPsw='.$tmpPassword, $message);  // message du mail avec lien
            $requete = "INSERT INTO utilisateurs(`prenom`, `nom`, `mail`, `mot_de_passe`,`role`,`descriptif`, `etat`) VALUES ('$prenom', '$nom', '$mail', '$hashedPassword', '$role','$descriptif', 'en attente') "; // Insertion du compte saisi, dans la bdd avec le statut "en attente"
            $result = mysqli_query($link,$requete); // the request itself
            $subject = 'Votre compte Drive Les Briques Rouges'; // sujet du mail
            mail($mail, $subject, $message, $headers);  // envoi du mail de confirmation
        }
        header("location:../home.php"); // redirection vers la page d'accueil
    }

?>
