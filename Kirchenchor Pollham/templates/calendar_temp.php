<link href="css/startseite.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="css/calendar-style.css">
<link rel="stylesheet" href="css/modal-style.css">	
<?php

	$json = json_decode(file_get_contents('templates/results.json'), true);
	$CALENDAR_ID = $json['intern'];

	function getGoogleClient()
	{
		putenv('GOOGLE_APPLICATION_CREDENTIALS=scripts/service-account.json');
	
		$client = new Google_Client();
		$client->useApplicationDefaultCredentials();
		$client->setScopes(Google_Service_Calendar::CALENDAR);

		return $client;
	}
?>
<form action="" method="post">
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
				<!-- If user is Admin or Sub-Admin then -->
				<?php if(getUserRole(getUserEmail()) <= $GLOBALS["ROLES_SUBADMIN"]) : ?>
			<button id="deleteBtn" type="submit" value="" name="deletion">Eintrag löschen</button>
			<button id="alterBtn" type="submit" value="" name="alteration">Eintrag bearbeiten</button>
				<?php endif; ?>
					  
		  </div>

		</div>
		</form>
<div class="list-group" style="width:250%;">
	
	  <div class="calendar" id="calendar">
			<?php 
			
			$client = getGoogleClient();
			$service = new Google_Service_Calendar($client);
			$queryParams = array(
			'orderBy' => 'startTime',
			'singleEvents' => true,
			'maxResults' => 10,
			'timeMin' => date_format(new DateTime('now'), DateTime::ATOM)
		);
			$events = $service->events->listEvents($CALENDAR_ID, $queryParams);
			
			if(count($events->getItems()) < 1) {
				echo "Derzeit ist leider nichts eingetragen, sorry!";
			}
				/* Fetching calendar events and displaying them */
					while(true) {
						
						foreach ($events->getItems() as $event) {
							$lol = $event->getStart()->getDateTime();
							$date = date("d.m.Y - H:i", strtotime($lol));
							$click = "onclick=\"calendarClick('" . $event->getId() . "', '" . $event->getSummary() . "', '" . str_replace("\r\n", "</br>", $event->getDescription()) . "', '" . $date . "', '" . $event->getLocation() . "')\"";
							?>
							<div class="calendar-entry" <?php echo $click ?>>
								
								<h4 class="list-group-item-heading" style="width: 100%;">  <?php echo $event->getSummary(); ?> </h4>
								<?php $desc = str_replace("\r\n", "</br>", $event->getDescription());
									if(strlen($desc) < 1) {
										$desc = "Keine Beschreibung";
									} else if(strlen($desc) > 100) {
										$desc = substr($desc, 0, 100);
										$desc = $desc . '...';
									}
								?>
								<p class="list-group-item-text"><?php echo $date ?></p>
								<p style="text-transform: none;"class="list-group-item-text"><?php echo $desc ?></p>
								
								
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
	  
     </div>
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
	
	function calendarClick(id, title, description, date, location) {
		document.getElementById("modalHeader").innerHTML = title;
		document.getElementById("modalContent").innerHTML = description;
		document.getElementById("modalFooter").innerHTML = date;
		modal.style.display = "block";
		
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
		
	
	</script>