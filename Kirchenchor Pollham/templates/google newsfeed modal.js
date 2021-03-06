		// Get the modal
	var modal1 = document.getElementById('myModal');

	// Get the <span> element that closes the modal
	var span = document.getElementsByClassName("close")[0];

	// When the user clicks on <span> (x), close the modal
	span.onclick = function() {
		modal1.style.display = "none";
	}

	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
		if (event.target == modal1) {
			modal1.style.display = "none";
		}
	}
	
	function calendarClick(id, title, description, date, place) {
		document.getElementById("modalHeader").innerHTML = title;
		document.getElementById("modalContent").innerHTML = description;
		document.getElementById("modalFooter").innerHTML = place + ", " + date;
		modal1.style.display = "block";
		
		var delBtn = document.getElementById("deleteBtn");
		var altBtn = document.getElementById("alterBtn");
		
		if(delBtn != null) {
			delBtn.value = id; 
		}
		
		if(altBtn != null) {
			altBtn.value = id; 
		}
	}
	
	function removeCalendarNodes() {
		var calendar = document.getElementById('calendar');
		while (calendar.hasChildNodes()) {
			calendar.removeChild(calendar.lastChild);
		}
	}
	
	function checkboxClick() {
		
		var text = document.getElementById("whatToShow");
		var box = document.getElementById("checkbox");
		
		if(box.checked) {
			text.innerHTML = "Anzeigen: Externe Termine";
		} else {
			text.innerHTML = "Anzeigen: Chorinterne Termine";
		}
		
		
	}
	