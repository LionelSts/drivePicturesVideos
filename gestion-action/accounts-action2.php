<?php
    session_start();    // démarage de la session
    if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("./index.php");</script>';  // redirection vers le login si l'utilisateur n'est pas connecté
    $link = mysqli_connect("127.0.0.1", "root", "", "drivelbr");    // connexion à la base de données
    $link->query('SET NAMES utf8');
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
    $regex = "/^(?=.*\w)(?=.*\W)[\w\W]{8,}$/";  // on vérifie le respect des régles du mot de passe (1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial et 8 caractères au minimum)

    if($exist==1)   // si le mail existe déjà dans la bdd...
    {
        echo '<script> alert("Le compte saisi existe déjà.");window.location.replace("../accounts.php");</script>'; // on informe l'utilisateur et on le redirige vers la page "accounts.php"
    }
    else    // si le mail n'existe pas dans la bdd...
    {
        $message = file_get_contents('template.html');  // contenu du mail
        if (isset($_POST['mdp'])) { // si la person
            $tmpPassword = bin2hex(random_bytes(24));   // génération automatique d'un mot de passe permetant "l'unicité" du lien
            $hashedPassword = password_hash($tmpPassword, PASSWORD_BCRYPT); // hashing du mot de passe
            $message = str_replace('registerLink', 'register.php?tmpPsw='.$tmpPassword, $message);  // message du mail avec lien
            $requete = "INSERT INTO utilisateurs(`prenom`, `nom`, `mail`, `mot_de_passe`,`role`,`descriptif`, `etat`) VALUES ('$prenom', '$nom', '$mail', '$hashedPassword', '$role','$descriptif', 'en attente') "; // Insertion du compte saisi, dans la bdd avec le statut "en attente"
            $result = mysqli_query($link,$requete);
        }
        else if($_POST["password"] != ""){  // si l'utilisateur rentre un mot de passe ...
            if (preg_match($regex, $_POST['password'])) {   // si le mot de passe répond aux critères de sécurité
                $mdp = password_hash($_POST['password'], PASSWORD_BCRYPT); // hashing du mot de passe;  // on hash le mot de passe
                $message = str_replace('registerLink', 'register.php?tmpPsw='.$_POST['password'], $message);  // message du mail avec lien
                $requete = "INSERT INTO utilisateurs(`prenom`, `nom`, `mail`, `mot_de_passe`,`role`,`descriptif`, `etat`) VALUES ('$prenom', '$nom', '$mail', '$mdp', '$role','$descriptif', 'en attente') "; // Insertion du compte saisi, dans la bdd avec le statut "en attente"
                $result = mysqli_query($link,$requete);
            }
            else echo '<script> alert("Veuillez saisir un mot de passe contenant au minimum 1 minuscule, 1 majuscule, 1 chiffre et 1 caractère spécial."); window.location.replace("../accounts.php");</script>';   //si le mot de passe ne respecte pas les régles, on affiche un message d'erreur et on réactualise la page
        }
        else echo '<script> alert("Erreur"); window.location.replace("../accounts.php");</script>';
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $subject = 'Votre compte Drive Les Briques Rouges'; // sujet du mail
        mail($mail, $subject, $message, $headers);  // envoi du mail de confirmation
        echo '<script> alert("Compte crée avec succés."); window.location.replace("../home.php");</script>'; // redirection vers la page d'accueil
    }
