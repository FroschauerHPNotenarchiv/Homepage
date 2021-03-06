// Get the modal
var modal = document.getElementById('editModal');

// Get the button that opens the modal
var btn = document.getElementById("showEdit");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("editclose")[0];

// When the user clicks the button, open the modal 

function editClicked(title, desc) {
	document.getElementById('editHeader').value = title;
	document.getElementById('editText').innerHTML = desc.replaceAll("</br>", "\r\n");
    modal.style.display = "block";
}

String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.split(search).join(replacement);
};

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}