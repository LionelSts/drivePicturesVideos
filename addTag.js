
// Fonction de l'ajout des tags dans les pages my files et home
function addTag(categorie){
    let newTagName = document.getElementById("newTag"+categorie).value;
    document.getElementById("newTag"+categorie).value = "";
    document.getElementById("list"+categorie).innerHTML += "<label class='checkboxContainer'>" + newTagName +
        "                                <input type='checkbox' id='" + newTagName + "' name='" + categorie + "-" + newTagName + "'>" +
        "                                <span class=\"customCheckBox\"></span>" +
        "                            </label>";
}
