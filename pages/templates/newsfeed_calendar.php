<!DOCTYPE html>
<html>
<?php

require_once 'func.php';

$client = getClient();
$service = new Google_Service_Calendar($client);
$events = $service->events->listEvents($_PUBLIC_CALENDAR_ID);

?>
	<head>
		<title>Google Calendar Test</title>
		<link rel="stylesheet" type="text/css" href="../../res/modal-style.css">
	</head>
	
	<style>
		.event-title {
			font-family: Arial;
			font-size: 1em;
		}
		
		.calendar-entry {
			margin-bottom: 3%;
			font-family: Arial;
			background: #b7babf;
			padding: 2px 6px;
			border-radius: 20px;
		}
		
		.calendar-entry:hover {
			background: #c6c9ce;
			cursor: pointer;
		}
		
		.calendar {
			width: 30%;
		}
		
		.entry-date {
			
			color: gray;
			font-size: 0.9em;
			margin-top: -5px;
			
		}
		
		
		
		
	</style>
	
	<body>
		<h1>Calendar Entrys</h1>
		
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
		
			<div class="calendar">
			<?php 
			
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
					$events = $service->events->listEvents($_PUBLIC_CALENDAR_ID, $optParams);
				} else {
					break;
				}
			}
			
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
		
	
	</script>

</html>
