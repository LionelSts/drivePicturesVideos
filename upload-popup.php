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
}
?>
<div id="uploadPopUp">
    <div class="closeButton"><h1>X</h1></div>
    <h1 id="uploadTitle">Téléverser vos fichiers</h1>
    <div id="uploadsTags">
        <div>
            <p class="bold">Tags :</p>
        </div>
        <div class="uploadCategories">
            <p>Edition</p>
            <div class="uploadsTagList">
                <div class="uploadsTag">
                    <input class="customCheckBox" type="checkbox" id="2021" name="2021">
                    <label for="2021">2021</label>
                </div>
                <div class="uploadsTag">
                    <input class="customCheckBox" type="checkbox" id="2023" name="2023">
                    <label for="2023">2023</label>
                </div>
                <div class="newTag">
                    <input type="text" name="newTag"> <label>+</label>
                </div>
            </div>

        </div>
        <div class="uploadCategories">
            <p>Lieu</p>
            <div class="uploadsTagList">
                <div class="uploadsTag">
                    <input class="customCheckBox" type="checkbox" id="Scène 1" name="Scène 1">
                    <label for="Scène 1">Scène 1</label>
                </div>
                <div class="uploadsTag">
                    <input class="customCheckBox" type="checkbox" id="BackStage" name="BackStage">
                    <label for="BackStage">BackStage</label>
                </div>
                <div class="uploadsTag">
                    <input class="customCheckBox" type="checkbox" id="Camping" name="Camping">
                    <label for="Camping">BackStage</label>
                </div>
                <div class="uploadsTag">
                    <input class="customCheckBox" type="checkbox" id="Village" name="Village">
                    <label for="Village">Village</label>
                </div>
                <div class="uploadsTag">
                    <input class="customCheckBox" type="checkbox" id="Parking" name="Parking">
                    <label for="Parking">Parking</label>
                </div>
                <div class="uploadsTag">
                    <input class="customCheckBox" type="checkbox" id="Parking" name="Parking">
                    <label for="Parking">Parking</label>
                </div>
                <div class="uploadsTag">
                    <input class="customCheckBox" type="checkbox" id="Parking" name="Parking">
                    <label for="Parking">Parking</label>
                </div>
                <div class="uploadsTag">
                    <input class="customCheckBox" type="checkbox" id="Parking" name="Parking">
                    <label for="Parking">Parking</label>
                </div>
                <div class="newTag">
                    <input type="text" name="newTag"> <label>+</label>
                </div>
            </div>
        </div>
    </div>
    <div id="lowPartUploads">
        <form id="uploadForm" method='post' action='' enctype='multipart/form-data'>
            <div id="uploadsFiles">
                <input type="file" accept="image/*,video/*" name="file[]" id="file" multiple required>
            </div>
            <input id="uploadButton" type='submit' name='submit' value='Envoyer'>
        </form>
    </div>
</div>
