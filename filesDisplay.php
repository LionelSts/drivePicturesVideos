<?php
    if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("./index.php");</script>';
    // Fonction qui permet d'afficher des fichiers
    function loadFiles($myPage, $search = []): void
    {
        if(isset($_GET["page"])){                                                                                       // On récupère le numéro de page (*20 pour le nombre d'éléments)
            $page = $_GET["page"]*20;
        }else{
            $page = 0;
        }
        $currentPage = "./".$myPage.".php";
        if(isset($_GET)){                                                                                               // Si il y a une recherche (effectuée avec get) on récupère tous ces éléments pour pouvoir les garder en changent de page
            $currentPage .= "?";
            foreach ($_GET as $key => $parameter){
                if($key != 'page'){
                    $currentPage .= $key.'='.$parameter.'&';
                }
            }
            $currentPage = str_replace(' ', '+',$currentPage);
            $currentPage .= 'page=';
        }                                                                                                               // L'url de redirection de navigation des pages et donc maintenant définie
        $mail = $_SESSION['mail'];
        $link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;
        $link->query('SET NAMES utf8');
        if($myPage == 'my_files'){                                                                                      // Sur la page my files, c'est pareil pour tout le monde on récupère les fichiers qui appartiennent à cet utilisateur
            $requete = "SELECT * FROM `fichiers` WHERE `auteur` = '$mail' ORDER BY `date` DESC, `id`  LIMIT 20 OFFSET ".intval($page); // On défini ici une limite de 20 fichiers et l'offset correspond à la page à la quelle nous sommes fois 20
            $result = mysqli_query($link, $requete); // Saving the result
            $files = mysqli_fetch_all($result);
        }else if($myPage == 'home'){                                                                                    // La page home, si l'utilisateur est invité il ne voit que ses fichiers plus ceux avec les tags qui lui sont attribués
            if(empty($search)){                                                                                         // Cas ù il n'y a pas de recherche
                if($_SESSION['role'] == "invite"){
                    $requete = "SELECT * FROM `fichiers` WHERE `id`  IN (SELECT DISTINCT `id_fichier` FROM `caracteriser` WHERE `nom_tag` IN (SELECT `nom_tag` FROM attribuer WHERE `email`='$mail')) OR auteur='$mail' ORDER BY `date` DESC, `id`  LIMIT 20 OFFSET ".intval($page);
                }else{
                    $requete = "SELECT * FROM `fichiers` ORDER BY `date` DESC, `id`  LIMIT 20 OFFSET ".intval($page);
                }
                $result = mysqli_query($link, $requete); // Saving the result
                $files = mysqli_fetch_all($result);
            }else{                                                                                                      // Si il y a une recherche
                $requete = "SELECT * FROM `fichiers` LIMIT 20 OFFSET ".intval($page);
                if($_SESSION['role'] == "invite"){
                    $requete = "SELECT * FROM `fichiers` WHERE `id`  IN (SELECT DISTINCT `id_fichier` FROM `caracteriser` WHERE `nom_tag` IN (SELECT `nom_tag` FROM attribuer WHERE `email`='$mail')) OR auteur='$mail' ORDER BY `date` DESC, `id`  LIMIT 20 OFFSET ".intval($page);
                }else{                                                                                                  // Ici on prend en comtpe tous les cas de recherche possible
                    if(!empty($search['tags']) && !empty($search['extensions'])){                                       // Si tags et extensions ne sont pas vide, alors on génère les requète sql
                        $tags = $search['tags'];
                        $extensions = $search['extensions'];
                        $requete = "SELECT * FROM `fichiers` WHERE `extension` IN (";                                   // On ajoute toutes les extensions selectionnés à la requete
                        for ($i = 0; $i < count($extensions); $i++) {
                            if ($i !== 0) $requete .= ' , ';
                            $requete .= '"' . $extensions[$i] . '"';
                        }
                        $requete .= ") AND `id` IN (SELECT DISTINCT `id_fichier` FROM `caracteriser` WHERE `nom_tag` IN (";
                        for($i = 0; $i < count($tags); $i++) {                                                          // On ajoute toutes les tags selectionnés à la requete
                            if ($i !== 0) $requete .= ' , ';
                            $requete .= '"'.$tags[$i].'"';
                        }
                        $requete .= ")) ORDER BY `date` DESC, `id`  LIMIT 20 OFFSET " . intval($page);
                    }else if(!empty($search['tags'])){                                                                  // Si la recherche n'est que par rapport aux tags
                        $tags = $search['tags'];
                        $requete = "SELECT * FROM `fichiers` WHERE `id` IN (SELECT DISTINCT `id_fichier` FROM `caracteriser` WHERE `nom_tag` IN (";
                        for($i = 0; $i < count($tags); $i++) {
                            if ($i !== 0) $requete .= ' , ';
                            $requete .= '"'.$tags[$i].'"';
                        }
                        $requete .=")) ORDER BY `date` DESC, `id`  LIMIT 20 OFFSET ".intval($page);
                    }else if(!empty($search['extensions'])) {                                                           // Si la recherche n'est que par rapport aux extensions
                        $requete = "SELECT * FROM `fichiers` WHERE `extension` IN (";
                        $extensions = $search['extensions'];
                        for ($i = 0; $i < count($extensions); $i++) {
                            if ($i !== 0) $requete .= ' , ';
                            $requete .= '"' . $extensions[$i] . '"';
                        }
                        $requete .= ") ORDER BY `date` DESC, `id`  LIMIT 20 OFFSET " . intval($page);
                    }
                }
                $result = mysqli_query($link, $requete); // Saving the result
                $files = mysqli_fetch_all($result);
            }
        }else if($myPage == "corbeille"){                                                                               // Si nous sommes dans la corbeille,
            if($_SESSION['role'] == 'admin'){                                                                           // l'admin voit tout
                $requete = "SELECT * FROM `corbeille` ORDER BY `supprime_date` DESC  LIMIT 20 OFFSET ".intval($page);
                $result = mysqli_query($link, $requete); // Saving the result
                $files = mysqli_fetch_all($result);
            }else{                                                                                                      // Les autres voient leurs fichiers
                $requete = "SELECT * FROM `corbeille` WHERE `auteur` = '$mail' ORDER BY `supprime_date` DESC  LIMIT 20 OFFSET ".intval($page);
                $result = mysqli_query($link, $requete); // Saving the result
                $files = mysqli_fetch_all($result);
            }

        }
        echo'
    <div class="filesNavigation">
        <h2 class="mediumTitle">Récents</h2>
        <div>
            <div id="checkActionButtons">
                <p id="filesSize"></p>
                <div class="actionButtonsContainer">
                    <div id="downloadZone"></div>';
                    if($myPage == "corbeille"){                                                                         // Les éléments à affichers (quand des fichiers sont cochés) sont différents si nous somme dans la corbeille
                        echo'
                    <img alt="supprimer" src="./images/icons/trash.png" onclick="deleteFiles()">
                    <img alt="restaurer" src="./images/icons/recycle.png" onclick="restoreFile()">';
                    }else{
                        if($myPage == 'home' && ($_SESSION['role'] == 'lecture' || $_SESSION['role'] == 'invite')){     // Dans home les éléments à affichers (quand des fichiers sont cochés) sont différents si l'utilisateurs n'as pas les droits de modifications
                            echo'<img alt="télécharger" src="./images/icons/download.png" onclick="downloadFiles()">';
                        }else{
                            echo'<img alt="supprimer" src="./images/icons/trash.png" onclick="deleteFiles()">
                            <img alt="télécharger" src="./images/icons/download.png" onclick="downloadFiles()">
                            <p id="editFilesTags" onclick="tagSelection()">Modifier les tags</p>';
                        }
                    }
                echo '</div>
            </div>
            <a href="';                                                                                                 // Liens pour accéder aux pages précédentes et suivantes
        if($page <= 0){                                                                                                 // SI la page est 0 ou moins, on va à la page 0
            echo $currentPage.'0';
        }else{
            echo $currentPage.$page/20 -1;
        }
        echo'">< Page précédente</a>';
        echo '<a href="';
        if(!isset($files)){
            echo $currentPage.'0';
            $files = [];
        }else{
            if(count($files) < 20){                                                                                     // Si il y a moins de 20 fichiers sur la page on ne va pas à la page suivante
                echo $currentPage.$page/20;
            }else{
                echo $currentPage.$page/20 +1;
            }
        }
        echo'">Page suivante ></a>
        </div>
    </div>
    <div id="filesDisplayContainer">';                                                                                  // On affiche tous les fichiers chargés
        foreach ($files as $fichier){
            if($myPage == "corbeille"){                                                                                 // Si nous sommes dans la corbeille, on va les chercher dans la corbeille
                $taglist ="Sans tag";
                $miniature= ".\corbeille\\"."miniature-" . $fichier[7] . ".png";
            }else{                                                                                                      // Si non, on va les chercher dans le stockage
                $requete = "SELECT `nom_tag` FROM `caracteriser` WHERE `id_fichier` = '$fichier[0]'";
                $result = mysqli_query($link, $requete); // Saving the result
                $fileTags = mysqli_fetch_all($result);
                $taglist="";
                foreach ($fileTags as $value){
                    $taglist .= $value[0] ." ";
                }
                $miniature= ".\miniatures\\" . $fichier[7] . ".png";
            }

                echo '<div class="fichierContainer">
                            <div class="fichierSubContainer">
                            <label onclick="clicCheckBox()" class="checkboxContainer checkboxFiles">       
                                <input type="checkbox" id="' . $fichier[0] . '" name="'. $fichier[0] . '.' . $fichier[2] . '" value="'.$fichier[6].'" onclick="buttonsAction()">
                                     <span class="customCheckBox"></span>
                                </label>';

            // We return the thumbnail
            echo '<img alt="miniature du fichier '. $fichier[0] . '.' . $fichier[2] .'" class="miniatureFichier"  src="' . $miniature . '">';

            echo '      <p>
                                <span class="fileNameContainer">'.$fichier[1].'.</span>
                                <span class="fileExtensionContainer">'.$fichier[2].'</span>
                        </p>
                        </div>
                        <p>
                            Tags : '.$taglist.'
                        </p>
                   </div>';
        }
        echo'
    </div>';
        $requete = "SELECT `nom_tag` FROM `tags`";
        $result = mysqli_query($link, $requete);
        $tags = mysqli_fetch_all($result);
        $tagsString = "[";
        foreach ($tags as $tag){                                                                                        // On met tous les tags dans une chaine de characters qui correspond à un array javascript
            $tagsString .= "'".$tag[0]."'".",";
        }
        $tagsString = rtrim($tagsString, ',');
        $tagsString.=']';
        echo'<script>
        let listTag = '.$tagsString.';'.
        'let page = "'.$myPage.'";';                                                                                    // On stock la page actuel
        echo'</script>';
        if($myPage == 'corbeille'){                                                                                     // Script js pour la corbeille et pour le reste
            echo'<script src="selectionComponentCorbeille.js"></script>';
        }else{
            echo'<script src="selectionComponent.js"></script>';
        }
        if($myPage != "corbeille"){                                                                                     // Si nous ne sommes pas dans la corbeille on peut prévisualiser les fichiers avec ce script
            echo '<script src="filesPreview.js"></script>';
        }
    }

