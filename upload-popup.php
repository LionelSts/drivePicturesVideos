<?php
    $link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;
    $link->query('SET NAMES utf8');
    $final_tab = [];
    $requete = "SELECT `nom_tag`, `nom_categorie` FROM `tags`";
    $result = mysqli_query($link, $requete);
    $data = mysqli_fetch_all($result);
    $requete = "SELECT `nom_categorie` FROM `categorie`";
    $result = mysqli_query($link, $requete);
    while($row = mysqli_fetch_array($result)){
        $categorie[] = $row['nom_categorie'];
    }

    foreach($categorie as $key => $value) {
        foreach ($data as list($value1, $value2)) {
            if($value == $value2) {
                $tab[] = $value1;
            }
        }
        $final_tab[] = $tab;
        unset($tab);
    }
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<div id="uploadPopUp">
    <div class="closeButton"><h1 onclick="closePopup()">X</h1></div>
    <h1 id="uploadTitle">Téléverser vos fichiers</h1>
    <form id="uploadForm" method='post' action='upload-popup-action.php' enctype="multipart/form-data">
        <div id="uploadsTags">
            <div>
                <p class="bold">Tags :</p>
            </div>
            <?php
            $i = 0;
            foreach ($final_tab as $key => $value1) {
                echo('
                        <div class="uploadCategories">
                            <p>' . $categorie[$i] . '</p>
                            <div class="uploadsTagList" >
                            <div id="list' . $categorie[$i] . '">'
                );
                foreach ($value1 as $key => $value2) {
                    echo('
                                <label class="checkboxContainer">' . $value2 . '
                                    <input type="checkbox" id="' . $value2 . '" name="'. $categorie[$i] .'-' .  $value2 . '" value="Yes">
                                    <span class="customCheckBox"></span>
                                </label>'
                    );
                }
                $i++;
                echo '          </div>  
                                <div class="newTag">
                                    <input id="newTag'.$categorie[$i-1].'" type="text" name="newTag" > <label onclick="addTag(`'.$categorie[$i-1].'`)" >+</label>
                                </div>
                            </div>
                        </div>';
            }
            ?>
        </div>
        <div id="lowPartUploads">
                <div id="uploadsFiles">
                    <input type="file" accept="image/*,video/*" name="file[]" id="file" multiple required>
                </div>
                <input id="uploadButton" type='submit' name='submit' value='Envoyer'>
        </div>
    </form>
</div>

<script src="addTag.js"></script>

<script>
    function closePopup(){
        document.getElementById("uploadPopUp").hidden = true;
    }
</script>
