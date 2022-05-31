function addTag(categorie){
    const newTagName = document.getElementById("newTag"+categorie).value;
    document.getElementById("newTag"+categorie).value = "";
    let newLine = document.createElement("div")
    newLine.innerHTML = "<label class='checkboxContainer'>" + newTagName +
        "                                <input type='checkbox id='" + newTagName + "' name='"+ newTagName+ "'>" +
        "                                <span class=\"customCheckBox\"></span>" +
        "                            </label>";
    document.getElementById("list"+categorie).prepend(newLine);
}
