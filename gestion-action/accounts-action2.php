<?php
    if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("./index.html");</script>';
    session_start();
    $link = mysqli_connect("127.0.0.1", "root", "", "drivelbr");
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $mail = $_POST['mail'];
    $role = $_POST['role'];
    $psw = $_POST['password'];
    $descriptif = $_POST['descriptif'];
    $password = password_hash($psw, PASSWORD_BCRYPT); // Hashing the password for more safety
    $requete = "SELECT `mail` FROM `utilisateurs` Where `mail`= '$mail'"; // The request to see if this login doesn't exist already
    $result = mysqli_query($link,$requete); // Applying the request
    $exist = mysqli_num_rows($result); // If the login doesn't exist already then $exist=0 if it exists already then exist= 1
    if($exist==1)
    {
        echo '<script> 
                alert("Le compte saisi existe déjà.");
                window.location.replace("../account.php");
              </script>'; // Please chose another login
    }
    else //
    {
        if (isset($_POST['mdp'])) {
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $message = file_get_contents('template.html');
            $tmpPassword = bin2hex(random_bytes(24));
            $hashedPassword = password_hash($tmpPassword, PASSWORD_BCRYPT);
            $message = str_replace('registerLink', 'register.php?tmpPsw='.$tmpPassword, $message);
            $requete = "INSERT INTO utilisateurs(`prenom`, `nom`, `mail`, `mot_de_passe`,`role`,`descriptif`, `etat`) VALUES ('$prenom', '$nom', '$mail', '$hashedPassword', '$role','$descriptif', 'en attente') "; // So we create your account in our database
            $result = mysqli_query($link,$requete); // the request itself
            $subject = 'Votre compte Drive Les Briques Rouges';
            mail($mail, $subject, $message, $headers);
        }
        header("location:../home.php"); // Now you are to login...
    }

?>
