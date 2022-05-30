<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <?php
    session_start();
    $link = mysqli_connect("127.0.0.1", "root", "", "drivelbr");
    $requete= "SELECT `nom`, `prenom`, `mail`, `role` FROM `utilisateurs`";
    $result = mysqli_query($link,$requete);
    $data = mysqli_fetch_all($result);
    ?>
    <script>

        const mapAccounts = new Map();
        <?php foreach ($data as $item) : ?>
        mapAccounts.set( '<?php echo $item[2] ?>', <?php echo json_encode($item)?>);
        <?php endforeach; ?>
    </script>
</head>
<body>
<div id="header">
    <a href="home.php"> <img  id="logo-header" src="images/graphiqueLBR/logoLONGUEURClassic.png"></a>
</div>
<div id="main">
    <?php
    include './menu.php';
    echo getMenu();
    ?>
</div>
<div id="pageContent">
    <h1 class="bigTitle">Gestion des comptes</h1>
    <form class="profile" method="post" action="accounts-action.php">
        <?php
        echo "<label class='profile'>Sélectionnez un compte :</label><select onclick='formReload(mapAccounts)' id='account'>";
        foreach ($data as $item) :
        {
           echo "<option value= ".$item[2].">".$item[2]."</option><br>";
        }
        endforeach;
        echo "</select>";
        //$mail = "<script language='JavaScript'> document.getElementById('account').value;</script>";
        //echo "<script language='JavaScript'> let mail =document.getElementById('account').value; mail= .$mail.;</script>";
        //$requete2 = "SELECT `nom`, `prenom`, `mail`, `mot_de_passe`, `role` FROM `utilisateurs` WHERE  `mail` = '$mail'";
        //$result2 = mysqli_query($link,$requete2);
        echo "<label class='profile' for='nom' >Nom : </label><input class='profile' type='text' id='nom'><br>";
        echo "<label class='profile' for='prenom'>Prénom : </label><input class='profile' type='text' id='prenom'><br>";
        echo "<label class='profile' for='mail' >Email : </label><input class='profile' type='text' id='mail'><br>";
        echo "<label class='profile' for='password' >Nom : </label><input class='profile' type='text' id='password'><br>";
        ?>
        <input class="profile" type="submit" style="cursor: pointer;" value="Supprimer le compte">
        <input class="profile" type="submit" style="cursor: pointer;" value="Appliquer les modifications">
    </form>
</div>
</div>
<script src="./accounts.js"></script>
</body>

