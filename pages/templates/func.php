<?php

require_once __DIR__.'/vendor/autoload.php';

$_PUBLIC_CALENDAR_ID = 'ool425dd1d9cja0gbttopntlb8@group.calendar.google.com';
$_INTERN_CALENDAR_ID = 'ool425dd1d9cja0gbttopntlb8@group.calendar.google.com';

function deleteEvents($service) {
	$response = $service->events->listEvents($_PUBLIC_CALENDAR_ID);
	echo json_encode($response) . "\n";
	echo 'Events: ' . "\n";

	if(count($response->getItems()) == 0) {
		echo 'Nothing to delete ...';
	} else {
		$i = 0;
		foreach ($response->getItems() as $event) {
			echo 'Deleting event ' . $event->getId() . ' [' . $event->getSummary() . '] ... ';
			$service->events->delete($_PUBLIC_CALENDAR_ID, $event->getId());
			echo 'Deletion done!' . "\n";
			$i++;
		}

		echo 'Sucessfully deleted ' . $i . ' calendar entrys!';
	}

}

function createEvent($service, $summary, $location = 'Somewhere', $description = "Adequate Description") {

	$event = new Google_Service_Calendar_Event(array(
			'summary' => $summary,
			'location' => $location,
			'description' => $description,
			'start' => array(
					'dateTime' => '2016-11-28T09:00:00-07:00',
					'timeZone' => 'America/Los_Angeles',
			),
			'end' => array(
					'dateTime' => '2016-11-28T17:00:00-07:00',
					'timeZone' => 'America/Los_Angeles',
			),
	));

	$calendarId = $_PUBLIC_CALENDAR_ID;
	$event = $service->events->insert($calendarId, $event);
	printf('Event created: %s\n', $event->htmlLink);

}

function listEvents($service) {
	echo "\nDisplaying all calendar entrys!\n";
	$events = $service->events->listEvents($_PUBLIC_CALENDAR_ID);
	if(count($events) == 0) {
		echo "   Nothing to display ...";
		return;
	}
	$i = 1;
	while(true) {
		foreach ($events->getItems() as $event) {
			echo $i . ') ' . $event->getSummary();
			echo "\n";
			$i++;
		}
			
		$pageToken = $events->getNextPageToken();
		if ($pageToken) {
			$optParams = array('pageToken' => $pageToken);
			$events = $service->events->listEvents($_PUBLIC_CALENDAR_ID, $optParams);
		} else {
			break;
		}
	}
}

function getClient() {
	putenv('GOOGLE_APPLICATION_CREDENTIALS=../../res/service-account.json');
	
	$client = new Google_Client();
	$client->useApplicationDefaultCredentials();
	$client->setScopes(Google_Service_Calendar::CALENDAR);

	return $client;
}

?>