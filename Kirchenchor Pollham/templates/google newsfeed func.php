<?php
	session_start();
	require_once 'google calendar general func.php';
	
	$client = getClient();
	$service = new Google_Service_Calendar($client);

	if(isset($_POST['deletion'])) {
		$service->events->delete($_PUBLIC_CALENDAR_ID, $_POST['deletion']);
	}
	
	if(isset($_POST['alteration'])) {
		//$service->events->delete($_PUBLIC_CALENDAR_ID, $_POST['alteration']);
		header("Location: templates/google_add_entry.php?" . 'id=' . $_POST['alteration']);
	}
	$_SESSION['email'] = 'hi';
	try {		
		// Do not fetch entrys from past events
		$queryParams = array(
			'orderBy' => 'startTime',
			'singleEvents' => true,
			'maxResults' => 10,
			'timeMin' => date_format(new DateTime('now'), DateTime::ATOM)
		);
			
		$events = $service->events->listEvents($_PUBLIC_CALENDAR_ID, $queryParams);

	} catch(Exception $exception) {
		echo "Google Calendar Error: Unable to fetch events from calendar!";
		echo $exception->getMessage();
	}
	
	
function isLoggedIn() {
	if(isset($_SESSION['email'])) {
		return true;
	}
	return false;
}
	
?>