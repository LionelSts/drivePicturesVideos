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
        <form class="profile" method="post" action="./gestion-action/accounts-action1.php">
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
                <input class='profile' type='text' name="nom" id='nom' required>
            </div>
            <div class="formLine">
                <label class='profile' for='prenom'>Prénom : </label>
                <input class='profile' type='text' name="prenom" id='prenom' required>
            </div>
            <div class="formLine">
                <label class='profile' for='mail' >Adresse mail : </label>
                <input class='profile' type='text' name="mail" id='mail' required>
            </div>
            <div class="formLine">
                <label class='profile' for='role' >Rôle : </label>
                <div class="lbrSelect">
                    <select class="profile, role-select" id="modifRole" name='role'>
                        <option class="role-choices" id="invite" value='invite'>invité</option>
                        <option class="role-choices-1" id="ecriture" value='ecriture'>ecriture</option>
                        <option class="role-choices" id="lecture" value='lecture'>lecture</option>
                        <option class="role-choices-1"id="admin" value='admin'>admin</option>
                    </select required>
                </div>
            </div>
            <div class="formLine">
                <label class='profile' for='password' >Mot de passe : </label>
                <input class='profile' type='password' name="password" id='password'>
            </div>
            <input class="profile" type="submit" name="supprimer" value="Supprimer le compte">
            <input class="profile" type="submit" name="modifier" value="Appliquer les modifications">
        </form>
        <div id="limit"></div>
    </div>
</div>


<div id="pageContent">
    <h1 class="bigTitle">Créer un compte</h1>
    <form class="profile" method="post" action="./gestion-action/accounts-action2.php">
        <div class="formLine">
            <label class='profile' for='nom' >Nom : </label>
            <input class='profile' name="nom" type='text' id='nom' required>
        </div>
        <div class="formLine">
            <label class='profile' for='prenom'>Prénom : </label>
            <input class='profile' name="prenom" type='text' id='prenom' required>
        </div>
        <div class="formLine">
            <label class='profile' for='mail' >Adresse mail : </label>
            <input class='profile' name="mail" type='text' id='mail' required>
        </div>
        <div class="formLine">
            <label class='profile' for='role' >Rôle : </label>
            <div class="lbrSelect">
                <select class="profile, role-select" id="modifRole" name='role'>
                    <option class="role-choices" id="invite" value='invite'>invité</option>
                    <option class="role-choices-1" id="ecriture" value='ecriture'>ecriture</option>
                    <option class="role-choices" id="lecture" value='lecture'>lecture</option>
                    <option class="role-choices-1"id="admin" value='admin'>admin</option>
                </select required>
            </div>
        </div>
        <div class="formLine">
            <label class='profile' for='descriptif' >Descriptif : </label>
            <input class='profile' name="descriptif" type='text' id='descriptif' required>
        </div>
        <div class="formLine">
            <label class='profile' for='password' >Mot de passe : </label>
            <input class='profile' name="password" type='password' id='password' required>
        </div>
        <div class="formLine">
            <label for="mdp">Laisser choisir le mot de passe :</label>
            <input type="checkbox" id="mdp" name="mdp" value="mdp">
        </div>
        <input class="profile" type="submit" value="Créer le compte">
    </form>
    <div id="limit"></div>
</div>


<div id="pageContent">
    <h1 class="bigTitle">Comptes en attente :</h1>
    <form class="profile" method="post" action="./gestion-action/accounts-action3.php">
        <div class="formLine">
            <TABLE >
                <?php
                    $count = 0;
                    $requete="SELECT `mail` FROM `utilisateurs` WHERE `etat` != 'inactif'"; // Preparing the request to verify the password where the login entered is found on the database
                    $result = mysqli_query($link, $requete); // Saving the result
                    while($row = mysqli_fetch_array($result)) // Searching the right line
                    {
                        $count++;
                        echo "<div class='formLine'>";
                        if($count %2) echo "<TR class='role-choices'><TH class='profile'> ".$row["mail"]." </TH><TH class='profile'><input class='profile' type='submit' value='Renvoyer le mail'></TH></TR>";
                        else echo"<TR class='role-choices-1'><TH class='profile'> ".$row["mail"]." </TH><TH class='profile'><input class='profile' type='submit' value='Renvoyer le mail'></TH></TR>";
                        echo "</div>";
                    }
                ?>
            </TABLE>
        </div>
    </form>
    <div id="limit"></div>
</div>


<div id="pageContent">
    <h1 class="bigTitle">Comptes supprimés:</h1>
    <form class="profile" method="post" action="./gestion-action/accounts-action4.php">
        <div class="formLine">
            <TABLE >
                <?php
                $count = 0;
                $requete="SELECT `mail` FROM `utilisateurs` WHERE `etat` != 'inactif'"; // Preparing the request to verify the password where the login entered is found on the database
                $result = mysqli_query($link, $requete); // Saving the result
                while($row = mysqli_fetch_array($result)) // Searching the right line
                {
                    $count++;
                    echo "<div class='formLine'>";
                    if($count %2) echo "<TR class='role-choices'><TH class='profile'> ".$row["mail"]." </TH><TH class='profile'><input class='profile' type='submit' value='Réactiver le compte'></TH></TR>";
                    else echo"<TR class='role-choices-1'><TH class='profile'> ".$row["mail"]." </TH><TH class='profile'><input class='profile' type='submit' value='Réactiver le compte'></TH></TR>";
                    echo "</div>";
                }
                ?>
            </TABLE>
        </div>
    </form>
</div>
<script src="./accounts.js"></script>
</body>
</html>