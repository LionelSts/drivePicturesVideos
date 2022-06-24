<?php
    session_start();    // démarrage de la session
    if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("./index.php");</script>'; // redirection vers le login si l'utilisateur n'est pas connecté
    $mail = $_SESSION['mail'];
    if($_SESSION['role'] == "admin"){   // si l'utilisateur est un admin...
    $prenom = $_POST['prenom']; $nom = $_POST['nom']; $role = $_POST['role'];    // on enregistre le mail de sa session et les autres infos saisies
    }else{  // si l'utilisateur n'est pas un admin
    $prenom = $_SESSION['prenom']; $nom = $_SESSION['nom']; $role = $_SESSION['role'];   // on enregistre les infos de sa session
    }
    $regex = "/^(?=.*\w)(?=.*\W)[\w\W]{8,}$/";
    $link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;  // connexion à la bdd
    $link->query('SET NAMES utf8');
    $requete = "SELECT `nom`, `prenom`, `mail`, `role`, `mot_de_passe` FROM `utilisateurs` WHERE `mail` = ?"; // on récupère les infos associées à l'email de l'utilisateur connecté
    $stmt = $link->prepare($requete);
    $stmt->bind_param("s", $mail);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = mysqli_fetch_array($result);    // on crée un tableau avec les infos trouvées dans la bdd
    if(!preg_match($regex, $_POST['password']) && $_POST['password'] != "") echo '<script>alert("Le mot de passe saisi ne respecte pas les règles. (1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial et 8 caractères au minimum)"); window.location.replace("my_account.php");</script>';
    else{
        if($_POST['password'] != "" && preg_match($regex, $_POST['password']) && password_hash($_POST['password'], PASSWORD_BCRYPT) != $data['mot_de_passe']) $mdp = password_hash($_POST['password'], PASSWORD_BCRYPT); // si le mot de passe renseigné n'est pas nul alors on le hash
        else $mdp = $data['mot_de_passe'];  // dans le cas contraire où le mot de passe est celui de la bdd, on récupère le mot de passe dans le tableau précédent
        $requete = "SELECT COUNT(*) FROM `utilisateurs` WHERE `role` = 'admin'";    // on compte le nombre d'admins dans la bdd
        $result = mysqli_query($link,$requete);
        $countAdmin = mysqli_fetch_array($result);
        if ($_SESSION['role'] == "admin"){  // si l'utilisateur connecté est un admin...
            if($countAdmin[0] > 1) $role = $_POST['role'];  // on vérifie qu'il y ait toujours au moins 2 admin, si oui l'admin peut changer son rôle
            else $role = $_SESSION['role']; // dans le cas contraire, il ne peut pas, son rôle reste 'admin'
            $requete = "UPDATE `utilisateurs` SET `prenom` = ?, `nom` = ?, `mot_de_passe` = ?, `role` = ? WHERE `mail` = ?";  // on modifie les infos dans la bdd
            $_SESSION['role'] = $role;  // enregistrement du (posssible nouveau) rôle de l'utilisateur connecté
            $stmt = $link->prepare($requete);
            $stmt->bind_param("sssss", $prenom,$nom,$mdp,$role,$mail);
            $stmt->execute();
        }
        else {
            $requete = "UPDATE `utilisateurs` SET `prenom` = ?, `nom` = ?, `mot_de_passe` = ? WHERE `mail` = ?";
            $stmt = $link->prepare($requete);
            $stmt->bind_param("ssss", $prenom, $nom,$mdp,$mail);
            $stmt->execute();
        } // si l'utilisateur connecté n'est pas un admin, modifie ses infos dans la bdd mais pas son rôle
        if($_SESSION['role'] == "admin" && $role != $_POST['role']) echo '<script> alert("Votre rôle n\'a pas été modifié (vous êtes le seul admin)")</script>';
        else echo '<script> alert("Vos changements ont bien été appliqués")</script>';
        $_SESSION['mail'] = $mail; // enregistrement du (posssible nouveau) mail de l'utilisateur connecté
        $_SESSION['nom'] = $nom; // enregistrement du (possible nouveau) nom de l'utilisateur connecté
        $_SESSION['prenom'] = $prenom;  // enregistrement du (possible nouveau) prénom de l'utilisateur connecté
        $requete = "INSERT INTO `tableau_de_bord` (`modification`) VALUES (CONCAT('Compte ',?,' (',?,') a modifié ses informations de compte'))";
        $stmt = $link->prepare($requete);
        $stmt->bind_param("ss", $mail, $role);
        $stmt->execute();
        echo '<script>window.location.replace("../my_account.php")</script>';  // redirection vers la page "my_account.php"
    }

