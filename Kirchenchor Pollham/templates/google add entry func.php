<?php
	session_start();
	
	$CLIENT_PATH = '../scripts/service-account.json';
	
	require_once 'google calendar general func.php';
	
	$buttonText = isset($_GET['id']) /* && isAdmin() */ ? 'Korrigieren' : 'Hinzugeben';
	$selectedCalendar = "";
		$title = "";
		$place = "";
		$date = "";
		$desc = "";
		
		$firstTime = 12;
		$secondTime = 13;
		
		
	if( isset($_POST['form-sent']) ) {
		$selectCalendar = $_POST['selectCalendar'];
		$title = $_POST['eventname'];
		$place = $_POST['place'];
		$date = $_POST['date'];
		$desc = $_POST['message'];
		
		$firstTime = $_POST['selectFrom'] + 1;
		$secondTime = $_POST['selectTo'];
	}
	else if( isset($_GET['id']) && !isset($_POST['form-sent'])) { // AND isAdmin()
		$client = getClient();
		$service = new Google_Service_Calendar($client);
		
		$event = $service->events->get($_PUBLIC_CALENDAR_ID, $_GET['id']);
		
		if($event != null) {
			$selectedCalendar = "extern";
			$title = $event->getSummary();
			$place = $event->getLocation();
			
			$tmpDate = $event->getStart()->getDateTime();
			$date = date('d.m.Y', strtotime($tmpDate));
			$desc = $event->getDescription();
			
			$firstTime = date('G', strtotime($tmpDate));
			$secondTime = date('G', strtotime($event->getEnd()->getDateTime()));
		}
	}
	
	if(isset($_POST['form-sent']) && !empty($title) && !empty($date) && validateDate($date, 'd.m.Y')) {
		// Parameters are correct
		
		if($buttonText == "Korrigieren") { // AND isAdmin()
			$client = getClient();
			$service = new Google_Service_Calendar($client);
			
			$event = $service->events->get($_PUBLIC_CALENDAR_ID, $_GET['id']);
				$event->setSummary($title); 
				$event->setLocation($place);
				$event->setDescription($desc);
				
				$gdate = new Google_Service_Calendar_EventDateTime();
				$gdate2 = new Google_Service_Calendar_EventDateTime();
				$gdate->setDateTime(date_format(date_create_from_format('d.m.Y:G', $date . ':' . $firstTime), DateTime::ATOM));
				$event->setStart($gdate);
				$gdate2->setDateTime(date_format(date_create_from_format('d.m.Y:G', $date . ':' . $secondTime), DateTime::ATOM));
				$event->setEnd($gdate2);
				
				$service->events->update($_PUBLIC_CALENDAR_ID, $event->getId(), $event);
				
				header("Location: ../Startseite.php");
		} else {
			if($selectCalendar === "intern") {
				addEntry($_INTERN_CALENDAR_ID, $title, $place, $desc, $date, $firstTime, $secondTime);
			}
			if($selectCalendar === "extern") {
				addEntry($_PUBLIC_CALENDAR_ID, $title, $place, $desc, $date, $firstTime, $secondTime);
			}
			if($selectCalendar === "both") {
				addEntry($_INTERN_CALENDAR_ID, $title, $place, $desc, $date, $firstTime, $secondTime);
				addEntry($_PUBLIC_CALENDAR_ID, $title, $place, $desc, $date, $firstTime, $secondTime);
			}
		}
		
		
		echo "Kalender hinzugefügt!";
	}
	
	function addEntry($calendarId, $title, $place, $desc, $date, $startTime = 5, $endTime = 6) {
		echo __DIR__;
		$client = getClient();
		$service = new Google_Service_Calendar($client);
		
		$dateFrom = $date . ':' . $startTime;
		$dateTo = $date . ':' . $endTime;
		
		
		
		$event = new Google_Service_Calendar_Event(array(
			'summary' => $title,
			'location' => $place,
			'description' => $desc,
			'start' => array(
					'dateTime' => date_format(date_create_from_format('d.m.Y:G', $dateFrom), DateTime::ATOM),
					'timeZone' => 'Europe/Vienna',
			),
			'end' => array(
					'dateTime' => date_format(date_create_from_format('d.m.Y:G', $dateTo), DateTime::ATOM),
					'timeZone' => 'Europe/Vienna',
			),
		));
		
		$service->events->insert($calendarId, $event);
	}
	
	
?>