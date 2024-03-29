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
        }else{
            $currentPage = "?page=";
        }                                                                                                    // L'url de redirection de navigation des pages est donc maintenant défini
        $mail = $_SESSION['mail'];
        $link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;
        $link->query('SET NAMES utf8');
        if($myPage == 'my_files'){                                                                                      // Sur la page my files, c'est pareil pour tout le monde on récupère les fichiers qui appartiennent à cet utilisateur
            $requete = "SELECT * FROM `fichiers` WHERE `auteur` = ? ORDER BY `date` DESC, `id`  LIMIT 20 OFFSET ".intval($page); // On définit ici une limite de 20 fichiers et l'offset correspond à la page à laquelle nous sommes fois 20
            $stmt = $link->prepare($requete);
            $stmt->bind_param("s", $mail);
            $stmt->execute();
            $result = $stmt->get_result();
            $files = mysqli_fetch_all($result);
        }else if($myPage == 'home'){                                                                                    // La page home, si l'utilisateur est invité il ne voit que ses fichiers plus ceux avec les tags qui lui sont attribués
            if(empty($search)){                                                                                         // Cas où il n'y a pas de recherche
                if($_SESSION['role'] == "invite"){
                    $requete = "SELECT * FROM `fichiers` WHERE `id`  IN (SELECT DISTINCT `id_fichier` FROM `caracteriser` WHERE `nom_tag` IN (SELECT `nom_tag` FROM attribuer WHERE `email`=?)) OR auteur=? ORDER BY `date` DESC, `id`  LIMIT 20 OFFSET ".intval($page);
                    $stmt = $link->prepare($requete);
                    $stmt->bind_param("ss", $mail,$mail);
                    $stmt->execute();
                    $result = $stmt->get_result();
                }else{
                    $requete = "SELECT * FROM `fichiers` ORDER BY `date` DESC, `id`  LIMIT 20 OFFSET ".intval($page);
                    $result = mysqli_query($link, $requete); // Resultat de la requête
                }
                $files = mysqli_fetch_all($result);
            }else{
                $requete = "SELECT `nom_tag` FROM `tags`";
                $result = mysqli_query($link, $requete);
                $data = mysqli_fetch_all($result);                                                                      // On sélectionne tous les tags
                $requete = "SELECT DISTINCT `extension` FROM `fichiers`";                                               // On récupère toutes les extensions
                $result = mysqli_query($link, $requete);
                $data1 = mysqli_fetch_all($result);
                $noms_tag = [];
                $exts = [];
                foreach ($data as $tag){                                                                                // On récupère tous les tags pour vérifier l'intégrité de ceux reçus
                    $noms_tag[] =$tag[0];
                }
                foreach ($data1 as $extension){                                                                         // On récupère toutes les extensions pour vérifier l'intégrité de celles reçus
                    $exts[] = $extension[0];
                }
                if($_SESSION['role'] == "invite"){                                                                      // Si il y a une recherche
                    $requete = "SELECT * FROM `fichiers` WHERE `id`  IN (SELECT DISTINCT `id_fichier` FROM `caracteriser` WHERE `nom_tag` IN (SELECT `nom_tag` FROM attribuer WHERE `email`=?)) OR auteur=? ORDER BY `date` DESC, `id`  LIMIT 20 OFFSET ".intval($page);
                    $stmt = $link->prepare($requete);
                    $stmt->bind_param("ss", $mail,$mail);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $files = mysqli_fetch_all($result);
                }else{                                                                                                  // Ici on prend en compte tous les cas de recherche possibles
                    if(!empty($search['tags']) && !empty($search['extensions'])){                                       // Si tags et extensions ne sont pas vides, alors on génère les requète sql
                        $tags = $search['tags'];
                        $extensions = $search['extensions'];
                        $requete = "SELECT * FROM `fichiers` WHERE `extension` IN (";                                   // On ajoute toutes les extensions selectionnées à la requête
                        for ($i = 0; $i < count($extensions); $i++) {
                            if(in_array($extensions[$i],$exts)){
                                if ($i !== 0) $requete .= ' , ';
                                $requete .= '"' . $extensions[$i] . '"';
                            }
                        }
                        $requete .= ") AND `id` IN (SELECT DISTINCT `id_fichier` FROM `caracteriser` WHERE `nom_tag` IN (";
                        for($i = 0; $i < count($tags); $i++) {                                                          // On ajoute tous les tags selectionnés à la requête
                            if(in_array($tags[$i],$noms_tag)) {
                                if ($i !== 0) $requete .= ' , ';
                                $requete .= '"' . $tags[$i] . '"';
                            }
                        }
                        $requete .= ")) ORDER BY `date` DESC, `id`  LIMIT 20 OFFSET " . intval($page);
                    }else if(!empty($search['tags'])){                                                                  // Si la recherche n'est que par rapport aux tags
                        $tags = $search['tags'];
                        $requete = "SELECT * FROM `fichiers` WHERE `id` IN (SELECT DISTINCT `id_fichier` FROM `caracteriser` WHERE `nom_tag` IN (";
                        for($i = 0; $i < count($tags); $i++) {
                            if(in_array($tags[$i],$noms_tag)) {
                                if ($i !== 0) $requete .= ' , ';
                                $requete .= '"' . $tags[$i] . '"';
                            }
                        }
                        $requete .=")) ORDER BY `date` DESC, `id`  LIMIT 20 OFFSET ".intval($page);
                    }else if(!empty($search['extensions'])) {                                                           // Si la recherche n'est que par rapport aux extensions
                        $requete = "SELECT * FROM `fichiers` WHERE `extension` IN (";
                        $extensions = $search['extensions'];
                        for ($i = 0; $i < count($extensions); $i++) {
                            if(in_array($extensions[$i],$exts)){
                                if ($i !== 0) $requete .= ' , ';
                                $requete .= '"' . $extensions[$i] . '"';
                            }
                        }
                        $requete .= ") ORDER BY `date` DESC, `id`  LIMIT 20 OFFSET " . intval($page);
                    }
                    $result = mysqli_query($link, $requete);
                    $files = mysqli_fetch_all($result);
                }
                if(!isset($requete)){
                    $requete = "SELECT * FROM `fichiers` LIMIT 20 OFFSET ".intval($page);
                    $result = mysqli_query($link, $requete);
                    $files = mysqli_fetch_all($result);
                }

            }
        }else if($myPage == "corbeille"){                                                                               // Si nous sommes dans la corbeille,
            if($_SESSION['role'] == 'admin'){                                                                           // l'admin voit tout
                $requete = "SELECT * FROM `corbeille` ORDER BY `supprime_date` DESC  LIMIT 20 OFFSET ".intval($page);
                $result = mysqli_query($link, $requete);
            }else{                                                                                                      // Les autres voient leurs fichiers
                $requete = "SELECT * FROM `corbeille` WHERE `auteur` = ? ORDER BY `supprime_date` DESC  LIMIT 20 OFFSET ".intval($page);
                $stmt = $link->prepare($requete);
                $stmt->bind_param("s", $mail);
                $stmt->execute();
                $result = $stmt->get_result();
            }
            $files = mysqli_fetch_all($result);
        }
        echo'
    <div class="filesNavigation">
        <h2 class="mediumTitle">Récents</h2>
        <div>
            <div id="checkActionButtons">
                <p id="filesSize"></p>
                <div class="actionButtonsContainer">
                    <div id="downloadZone"></div>';
                    if($myPage == "corbeille"){                                                                         // Les éléments à afficher (quand des fichiers sont cochés) sont différents si nous sommes dans la corbeille
                        echo'
                    <img alt="supprimer" src="./images/icons/trash.png" onclick="deleteFiles()">
                    <img alt="restaurer" src="./images/icons/recycle.png" onclick="restoreFile()">';
                    }else{
                        if($myPage == 'home' && ($_SESSION['role'] == 'lecture' || $_SESSION['role'] == 'invite')){     // Dans home les éléments à afficher (quand des fichiers sont cochés) sont différents si l'utilisateur n'a pas les droits de modification
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
        if($page <= 0){                                                                                                 // Si la page est 0 ou moins, on va à la page 0
            echo $currentPage.'0';
        }else{
            echo $currentPage.($page/20 -1);
        }
        echo'">< Page précédente</a>';
        echo '<a href="';
        if(!isset($files)){
            echo $currentPage.'0';
            $files = [];
        }else{
            if(count($files) < 20){                                                                                     // Si il y a moins de 20 fichiers sur la page on ne va pas à la page suivante
                echo $currentPage.($page/20);
            }else{
                echo $currentPage.($page/20 +1);
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
            }else{                                                                                                      // Sinon, on va les chercher dans le stockage
                $requete = "SELECT `nom_tag` FROM `caracteriser` WHERE `id_fichier` = ?";
                $stmt = $link->prepare($requete);
                $stmt->bind_param("i", $fichier[0]);
                $stmt->execute();
                $result = $stmt->get_result();
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

            // On renvoie la miniature
            echo '<img alt="miniature du fichier '. $fichier[0] . '.' . $fichier[2] .'" class="miniatureFichier"  src="' . $miniature . '">';

            echo '      <p>
                            <span class="fileNameContainer">'.htmlspecialchars($fichier[1], ENT_QUOTES, 'UTF-8').'.</span>
                            <span class="fileExtensionContainer">'.htmlspecialchars($fichier[2], ENT_QUOTES, 'UTF-8').'</span>
                        </p>
                        </div>
                        <p>
                            Tags : '.htmlspecialchars($taglist, ENT_QUOTES, 'UTF-8').'
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
            $tagsString .= "'".htmlspecialchars($tag[0], ENT_QUOTES, 'UTF-8')."'".",";
        }
        $tagsString = rtrim($tagsString, ',');
        $tagsString.=']';
        echo'<script>
        let listTag = '.$tagsString.';'.
        'let page = "'.$myPage.'";';                                                                                    // On stocke la page actuelle
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

