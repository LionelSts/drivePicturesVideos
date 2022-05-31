<?php
    if(isset($_POST['submit'])){
        $countfiles = count($_FILES['file']['name']);
        for($i = 0 ; $i < $countfiles ; $i++){
            $filename = $_FILES['file']['name'][$i];
            echo($_FILES['file']['type'][$i]);
            if(preg_match("/image|video/", $_FILES['file']['type'][$i])){
                move_uploaded_file($_FILES['file']['tmp_name'][$i],'fichiers/'.$filename);
            }
        }
    }else{
        $link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;
        $link->query('SET NAMES utf8');
        $requete = "SELECT `nom_tag`, `categorie` FROM `tags`";
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
    }
?>
<div id="uploadPopUp">
    <div class="closeButton"><h1>X</h1></div>
    <h1 id="uploadTitle">Téléverser vos fichiers</h1>
    <div id="uploadsTags">
        <div>
            <p class="bold">Tags :</p>
        </div>
        <?php
            $i =0;
            foreach($final_tab as $key => $value1) {
                echo('
                <div class="uploadCategories">
                    <p>'.$categorie[$i].'</p>
                    <div class="uploadsTagList">'
                        );
                        foreach ($value1 as $key => $value2) {
                        echo('
                        <label class="uploadsTag">'.$value2.'
                            <input type="checkbox" id="'.$value2.'" name="'.$value2.'">
                            <span class="customCheckBox"></span>
                        </label>'
                        );
                        }
                        $i++;
                        echo('
                        <div class="newTag">
                            <input type="text" name="newTag"> <label>+</label>
                        </div>
                    </div>
                </div>'
                );
            }
        ?>
    </div>
    <div id="lowPartUploads">
        <form id="uploadForm" method='post' action='my_files.php' enctype='multipart/form-data'>
            <div id="uploadsFiles">
                <input type="file" accept="image/*,video/*" name="file[]" id="file" multiple required>
            </div>
            <input id="uploadButton" type='submit' name='submit' value='Envoyer'>
        </form>
    </div>
</div>
