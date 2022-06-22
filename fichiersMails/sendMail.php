<?php

if(isset($_POST)){
	$to = $_POST['mailTo'];
	$headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: no-reply@lesbriquesrouges.fr' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
    $message = file_get_contents('template.html');
    $subject = 'Votre compte Drive Les Briques Rouges'; // sujet du mail

    $messageBienvenu = 'Bonjour '.$_POST['prenom'].' '.$_POST['nom'].'<br>';
	switch ($_POST['mailType']) {
		case 'renvoyer':
			$tmpPassword = $_POST['tmpPsw'];
			$messageBienvenu .= 'Bienvenue sur le drive LBR ! <br>
            Choisis ton mot de passe en cliquant sur le boutton pour valider !';
    		$message = str_replace('registerLink', 'register.php?tmpPsw='.$tmpPassword, $message);  // On remplace le lien du boutton du mail
			break;
		case 'mdpRandom':
			$tmpPassword = $_POST['tmpPsw'];
			$messageBienvenu .= 'Bienvenue sur le drive LBR ! <br>
            Choisis ton mot de passe en cliquant sur le boutton pour valider !';
    		$message = str_replace('registerLink', 'register.php?tmpPsw='.$tmpPassword, $message);  // On remplace le lien du boutton du mail
			break;
		case 'mdpChoisis':
			$messageBienvenu .= 'Bienvenue sur le drive LBR ! <br> 
			Ton nom d\'utilisateur est ton adresse mail. <br>
            Ton mdp est : '.$_POST['motdepasse'].'<br>
            Connecte toi directement en cliquant sur le boutton ci dessous :';
            $message = str_replace('registerLink', 'index.php', $message);  // On remplace le lien du boutton du mail
			break;
		case 'compteGoogle':
			$subject = 'Il y a un nouveau sur le drive !'; // sujet du mail
			$messageBienvenu = 'Un memebre des briques rouges a créé un comtpe avec google ! <br> 
			Son nom est '.$_POST['prenom'].' '.$_POST['nom'].' <br>
            Tu peux aller modifier ses informations dans le panel de gestion :';
            $message = str_replace('registerLink', 'index.php', $message);  // On remplace le lien du boutton du mail
			break;
		default:
			break;
	}
	$message = str_replace('TEXTEVAR', $messageBienvenu, $message);  // On remplace le message du mail
	mail($to, $subject, $message, $headers);
}
