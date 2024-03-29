<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <?php
    if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("./index.php");</script>';
    if($_SESSION["role"] != 'admin') echo '<script> alert("Vous n`êtes pas autorisé à accéder à cette page.");window.location.replace("./home.php");</script>';
    $link = mysqli_connect("127.0.0.1", "root", "", "drivelbr");
    $link->query('SET NAMES utf8');
    $requete= "SELECT `nom`, `prenom`, `mail`, `role`, `descriptif` FROM `utilisateurs`";
    $result = mysqli_query($link,$requete);
    $data = mysqli_fetch_all($result);
    ?>
    <script>
        const mapAccounts = new Map();
        <?php foreach ($data as $item) : ?>
        mapAccounts.set( '<?php echo $item[2] ?>', <?php echo json_encode($item)?>);
        <?php endforeach; // Ici on stocke toutes les infos des comptes dans une map javascript pour pouvoir s'en servir dynamiquement ?>
    </script>
    <title>Gestion des comptes - DriveLBR</title>
    <link data-n-head="1" rel="icon" type="image/x-icon" href="./images/icons/favicon.ico">
</head>
<body>
<div id="header">
    <a href="home.php"> <img alt="logoLBR" id="logo-header" src="images/graphiqueLBR/logoLONGUEURClassic.png"></a>
</div>
<div id="main">
    <label>
        <input hidden type="text" name="emailConcerne" value="">
    </label>
    <?php
    include './menu.php';
    echo getMenu();
    ?>
    <div class="pageContent">
        <h1 class="bigTitle">Gestion des comptes</h1>
        <form class="profile" method="post" action="./gestion-action/accounts-action1.php">
            <div class="formLine">
                <label class='profile'>Sélectionnez un compte :</label>
                <div class="lbrSelect">
                    <label for='account'></label><select class="profile, role-select" onclick='formReload(mapAccounts);check(2)' id='account' name="selectedMail">
                        <?php
                        // On charge les comptes dans le menu déroulant
                        $counter =0;
                        foreach ($data as $item) :
                            {
                                if($counter%2){
                                    echo "<option class='role-choices' value= '".$item[2]."'>". $item[2]."</option><br>";
                                }else {
                                    echo "<option class='role-choices-1' value= '" . $item[2] . "'>" . $item[2] . "</option><br>";
                                }
                                $counter++;
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
                <label class='profile' for='mailCompte' >Adresse mail : </label>
                <input class='profile' type='text' name="mail" id="mailCompte" required disabled>
            </div>
            <div class="formLine">
                <label class='profile' for='descriptif2' >Descriptif : </label>
                <input class='profile' type='text' name="descriptif2" id='descriptif2' required>
            </div>
            <div class="formLine">
                <label class='profile' for='role' >Rôle : </label>
                <div class="lbrSelect">
                    <label for="modifRole"></label><select class="profile, role-select" id="modifRole" name='role' onclick="check(2)">
                        <option class="role-choices" id="invite" value='invite'>invité</option>
                        <option class="role-choices-1" id="ecriture" value='ecriture'>ecriture</option>
                        <option class="role-choices" id="lecture" value='lecture'>lecture</option>
                        <option class="role-choices-1" id="admin" value='admin'>admin</option>
                    </select required>
                </div>
            </div>
            <?php
            // On charge les tags dans un array javascript pour pouvoir les réutiliser plus tard
            $tableau = []; $i = 0;
            $requete2 = "SELECT * FROM `attribuer`";
            $result2 = mysqli_query($link, $requete2);
            $data2 = mysqli_fetch_all($result2);
            $jsCode = "let tabTags = [];";
            foreach ($data2 as $info){
                $jsCode .= 'tabTags.push(["'. htmlspecialchars($info[0], ENT_QUOTES, 'UTF-8') .'","'. htmlspecialchars($info[1], ENT_QUOTES, 'UTF-8') .'"]);';
            }
            $jsCode .= 'let filtre = (mail) => tabTags.filter(word => word[0] == mail);';
            echo '<script>' . $jsCode . '</script>';
            ?>
            <div class="formLine" id="tags1">
                <label class='profile' for='tags' >Accès au(x) tag(s) : </label>
                <div class="autreTagsContainer">
                    <?php
                    $counter = 0;
                    $requete = "SELECT `nom_tag` FROM `tags`";
                    $result = mysqli_query($link, $requete);
                    while($row = mysqli_fetch_array($result))               // On charge les tags dans le menu déroulant
                    {
                        if($row["nom_tag"] != "Sans tag") {                 // Sauf le tag Sans tag
                            $tag = htmlspecialchars($row["nom_tag"], ENT_QUOTES, 'UTF-8');
                            if ($counter % 2) {
                                echo "<div class='tag-choices'>
                                            <label class='redCheckboxContainer'>" . $tag . "
                                                <input type='checkbox' id='".$tag."' name='listeTag' value ='".$tag."'>
                                                <span class='tagCheckbox redCheckbox'></span>
                                            </label>
                                         </div>";
                            } else {
                                echo "<div class='tag-choices-1'>
                                            <label class='redCheckboxContainer'>" . $tag . "
                                                <input type='checkbox' id='".$tag."' name='listeTag' value ='".$tag."'>
                                                <span class='tagCheckbox redCheckbox'></span>
                                            </label>
                                         </div>";
                            }
                            $counter++;
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="formLine">
                <label class='profile' for='password' >Mot de passe : </label>
                <input class='profile' type='password' name="password" id='password'>
            </div>
            <input class="profile" type="submit" name="modifier" value="Appliquer les modifications">
            <input class="profile" type="submit" name="supprimer" value="Supprimer le compte">
        </form>
        <div id="limit"></div>
        <br>
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
                    <label for="modifRoleCrea"></label><select class="profile, role-select" id="modifRoleCrea" name='role' onclick="check(1)" >
                        <option class="role-choices" id="invite" value='invite'>invité</option>
                        <option class="role-choices-1" id="ecriture" value='ecriture'>ecriture</option>
                        <option class="role-choices" id="lecture" value='lecture'>lecture</option>
                        <option class="role-choices-1" id="admin" value='admin'>admin</option>
                    </select required>
                </div>
            </div>
            <div class="formLine" id="tags2">
                <label class='profile' for='tags' >Accès au(x) tag(s) : </label>
                <div class="autreTagsContainer">
                    <?php
                    // Ici on refait la même chose que précédement mais pour la création des comptes
                    $counter =0;
                    // On a déjà exactement la même requête plus haut, pas besoin de la re-déclarer
                    $result = mysqli_query($link, $requete);
                    while($row = mysqli_fetch_array($result))
                    {
                        if($row["nom_tag"] != "Sans tag") {
                            $tag=htmlspecialchars($row["nom_tag"], ENT_QUOTES, 'UTF-8');
                            if ($counter % 2) {
                                echo "<div class='tag-choices'>
                                                <label class='redCheckboxContainer'>" . $tag . "
                                                    <input type='checkbox' name='listeTag' value ='" . $tag . "'>
                                                    <span class='tagCheckbox redCheckbox'></span>
                                                </label>
                                             </div>";
                            } else {
                                echo "<div class='tag-choices-1'>
                                                <label class='redCheckboxContainer'>" . $tag . "
                                                    <input type='checkbox' name='listeTag' value =''" . $tag . "'>
                                                    <span class='tagCheckbox redCheckbox'></span>
                                                </label>
                                             </div>";
                            }
                            $counter++;
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="formLine">
                <label class='profile' for='descriptif' >Descriptif : </label>
                <input class='profile' name="descriptif" type='text' id='descriptif' required>
            </div>
            <div class="formLine">
                <label class='profile' for='password' >Mot de passe : </label>
                <input class='profile' name="password" type='password' id='password'>
            </div>
            <div class="formLine">
                <label for="mdp">Laisser choisir le mot de passe :</label>
                <label class="redCheckboxContainer">
                    <input type="checkbox" id="randomPassword" name="randomPassword" value="randomPassword">
                    <span class="redCheckbox "></span>
                </label>
            </div>
            <input class="profile" type="submit" value="Créer le compte">
        </form>
        <div id="limit"></div>
        <br>
        <h1 class="bigTitle">Comptes en attente :</h1>
        <form class="profile" method="post" action="./gestion-action/accounts-action3.php">
            <div class="tableContainer">
                <TABLE class="lbrTable" >
                    <tbody>
                    <?php
                    // On récupère tous les comptes en attentes
                    $requete="SELECT `mail` FROM `utilisateurs` WHERE `etat` = 'en attente'"; // On prépare la requête pour vérifier dans la base de données
                    $result = mysqli_query($link, $requete); // On récupère le résultat
                    while($row = mysqli_fetch_array($result)) // Pour chaque ligne
                    {
                        echo "<tr><td> ".$row["mail"]." </td><td><input class='profile' type='submit' name='".$row["mail"]."' value='Renvoyer le mail'></td></tr>";
                    }
                    ?>
                    </tbody>
                </TABLE>
            </div>
        </form>
        <div id="limit"></div>
        <br>
        <h1 class="bigTitle">Comptes supprimés:</h1>
        <form class="profile" method="post" action="./gestion-action/accounts-action4.php">
            <div class="tableContainer">
                <TABLE class="lbrTable" >
                    <tbody>
                    <?php
                    // On récupère tous les comptes supprimés
                    $requete="SELECT `mail` FROM `utilisateurs` WHERE `etat` = 'inactif'"; // Reqête SQL
                    $result = mysqli_query($link, $requete); // Résultat de la requête
                    while($row = mysqli_fetch_array($result)) // Pour chaque ligne on fait :
                    {
                        echo "<tr><td> ".$row["mail"]." </td><td><input class='profile' type='submit' name='".$row["mail"]."' value='Réactiver le compte'></td></tr>";
                    }
                    ?>
                    </tbody>
                </TABLE>
            </div>
        </form>
    </div>
</div>
<script src="./accounts.js"></script>
</body>
</html>
