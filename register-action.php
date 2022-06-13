<?php
    session_start();    // lancement de la session
    $mail = $_POST["email"];    // récupération du mail saisi
    $regex = "/^(?=.*[\w])(?=.*[\W])[\w\W]{8,}$/";  // expression régulière des régles du mot de passe
    $tmpPsw = $_GET['tmpPsw'];  // récupération du mot de passe par la méthode GET
    if(preg_match($regex, $_POST['password'])){ // si le mot de passe respecte les règles...
        $link = mysqli_connect("127.0.0.1", "root", "", "drivelbr");    // connexion à la bdd
        $requete = "SELECT `mot_de_passe` FROM `utilisateurs` WHERE `mail` = '$mail' "; // recherche du mot de passe correspondant au mail saisi
        $result = mysqli_query($link,$requete);
        $psw = mysqli_fetch_array($result)['mot_de_passe'];
        if(password_verify($tmpPsw, $psw)){ // vérification de la similitude du mot de passe récupéré et celui enregistré dans la bdd
            $mdp = password_hash($_POST['password'], PASSWORD_BCRYPT);  // hashage du mot de passe saisi
            $requete = "UPDATE `utilisateurs` SET `mot_de_passe` = '$mdp', `etat` = 'actif' WHERE `mail` = '$mail' AND `etat` = 'en attente'";  // insertion du nouveau mot de passe dans la bdd
            $result = mysqli_query($link, $requete);
            echo '<script> alert("Nouveau mot de passe enregistré."); window.location.replace("index.php");</script>';  // redirection vers le login avec un message de confirmation
        }
        else{
            echo '<script> alert("Lien erroné."); window.location.replace("index.php");</script>';  // dans le cas contraire, on redirige vers le login avec un message d'erreur
        }

    }
    else {  // mot de passe ne respectant pas les régles
        echo '<script> alert("Veuillez saisir un mot de passe contenant au minimum 1 minuscule, 1 majuscule, 1 chiffre et 1 caractère spécial."); 
            window.location.replace("register.php?tmpPsw="'. $tmpPsw .');</script>';    // message d'erreur et redirection sur  la page de login
    }
?>
