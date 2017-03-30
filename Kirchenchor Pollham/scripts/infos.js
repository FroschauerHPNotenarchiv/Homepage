// Get the modal
var editModal = document.getElementById('editFileModal');

// Get the button that opens the modal
var btn = document.getElementById("showEdit");

// Get the <span> element that closes the modal
var closeBtn = document.getElementsByClassName("editclose")[0];

// When the user clicks the button, open the modal 

function onEditFileClicked(fileId, fileName) {
	document.getElementById('editName').value = fileName;
    editModal.style.display = "block";
}

String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.split(search).join(replacement);
};

// When the user clicks on <span> (x), close the modal
closeBtn.onclick = function() {
    editModal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == editModal) {
        editModal.style.display = "none";
    }
}