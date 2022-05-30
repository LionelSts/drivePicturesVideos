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
    <div id="pageContent">
        <h1 class="bigTitle">Gestion des comptes</h1>
        <form class="profile" method="post" action="accounts-action.php">
            <div class="formLine">
                <label class='profile'>Sélectionnez un compte :</label>
                <div class="lbrSelect">
                    <select class="profile, role-select" onclick='formReload(mapAccounts)' id='account'>
                        <?php
                            $counter =0;
                            foreach ($data as $item) :
                            {
                               if($counter%2){
                                   echo "<option class='role-choices' value= ".$item[2].">".$item[2]."</option><br>";
                               }else {
                                   echo "<option class='role-choices-1' value= " . $item[2] . ">" . $item[2] . "</option><br>";
                               }
                            }
                            endforeach;
                        ?>
                    </select>
                </div>
            </div>
            <div class="formLine">
                <label class='profile' for='nom' >Nom : </label>
                <input class='profile' type='text' id='nom'>
            </div>
            <div class="formLine">
                <label class='profile' for='prenom'>Prénom : </label>
                <input class='profile' type='text' id='prenom'>
            </div>
            <div class="formLine">
                <label class='profile' for='mail' >Email : </label>
                <input class='profile' type='text' id='mail'>
            </div>
            <div class="formLine">
                <label class='profile' for='password' >Mot de passe : </label>
                <input class='profile' type='text' id='password'>
            </div>
            <input class="profile" type="submit" style="cursor: pointer;" value="Supprimer le compte">
            <input class="profile" type="submit" style="cursor: pointer;" value="Appliquer les modifications">

        </form>
    </div>
</div>
<script src="./accounts.js"></script>
</body>

