<?php
	session_start();
	require_once 'func.php';

	$client = getClient();
	$service = new Google_Service_Calendar($client);
	
	$_SESSION['email'] = 'hi'; 
	
	$internEvents;
	$publicEvents;
	
	if(isLoggedIn()) {
		$internEvents = $service->events->listEvents($_INTERN_CALENDAR_ID);
	}
	$publicEvents = $service->events->listEvents($_PUBLIC_CALENDAR_ID);
	
	function isLoggedIn() {
		if(isset($_SESSION['email'])) {
			return true;
		}
		return false;
	}
	
	function populateCalendar($events) {
		
		echo '<script type="text/javascript">',
				'removeCalendarNodes();',
			'</script>';
		
		while(true) {
				foreach ($events->getItems() as $event) {
					$lol = $event->getStart()->getDateTime();
					$date = date("d.m.Y - h:i", strtotime($lol));
					$click = "onclick=\"calendarClick('" . $event->getSummary() . "', '" . $event->getDescription() . "', '" . $date . "')\"";
					?>
					<div class="calendar-entry" <?php echo $click ?>>
						
						<p>  <?php echo $event->getSummary(); ?> </p>
						<p class="entry-date"> <?php echo $date ?></p>
						
					</div>
					<?php
				}
					
					
				$pageToken = $events->getNextPageToken();
				if ($pageToken) {
					$optParams = array('pageToken' => $pageToken);
					if(isLoggedIn()) {
						$events = $service->events->listEvents($_INTERN_CALENDAR_ID, $optParams);
					} else {
						$events = $service->events->listEvents($_PUBLIC_CALENDAR_ID, $optParams);
					}
					
				} else {
					break;
				}
			}
			}
			?>
	
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Google Calendar Test</title>
		<link rel="stylesheet" href="../../res/calendar-style.css">
		<link rel="stylesheet" href="../../res/modal-style.css">		
	</head>
	
	
	<body>
		<h1>Calendar Entrys</h1>
		<?php
			if(isLoggedIn()) {
				?> <label class='switch'>
					<input id="checkbox" type='checkbox' onclick='checkboxClick()'>
					  <div class='slider round'></div>
					</label>
					<p id="whatToShow"> Anzeigen: Chorinterne Termine</p>
					<?php
			}
		?>
		
			<div id="myModal" class="modal">
			

		  <!-- Modal content -->
		  <div class="modal-content">
			<div class="modal-header">
			  <span class="close">×</span>
			  <h2 id="modalHeader">Headline</h2>
			</div>
			<div class="modal-body">
			  <p id="modalContent">Text loading ... </p>
			</div>
			<div class="modal-footer">
			  <h3 id="modalFooter">Modal Footer</h3>
			</div>
		  </div>

		</div>
		
			<div class="calendar" id="calendar">
			<?php 
				populateCalendar($internEvents);
			?>
			</div>
		
		
	</body>
	
	<script>
	
		// Get the modal
	var modal = document.getElementById('myModal');

	// Get the <span> element that closes the modal
	var span = document.getElementsByClassName("close")[0];

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
	
	function calendarClick(title, description, date) {
		document.getElementById("modalHeader").innerHTML = title;
		document.getElementById("modalContent").innerHTML = description;
		document.getElementById("modalFooter").innerHTML = date;
		modal.style.display = "block";
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
		
	
	</script>

</html>