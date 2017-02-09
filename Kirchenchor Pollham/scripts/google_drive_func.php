<?php
	REQUIRE_ONCE "../vendor/autoload.php";
	INCLUDE "google drive constants.php";
	
	$client = getClient();
	$service = new Google_Service_Drive($client);
	
	function retrieveAllFiles($service, $query = null) {
	  $result = array();
	  $pageToken = NULL;

	  do {
		try {
		  $parameters = array();
		  $parameters['q'] = $query;
		  if ($pageToken) {
			$parameters['pageToken'] = $pageToken;
		  }
		  $files = $service->files->listFiles($parameters);

		  // getItems() bzw. listFiles() sind veraltet: getFiles() !!!
		  $result = array_merge($result, $files->getFiles());
		  $pageToken = $files->getNextPageToken();
		} catch (Exception $e) {
		  print "An error occurred: " . $e->getMessage();
		  $pageToken = NULL;
		}
	  } while ($pageToken);
	  return $result;
	}
	
	function deleteFile($service, $fileId) {
		try {
			$service->files->delete($fileId);
			return true;
		} catch(Exception $e) {
			print $e->getMessage();
			return false;
		}
	}
	
	function uploadPdf($service, $filename, $title) {
		try {
			$fileMetadata = new Google_Service_Drive_DriveFile(array(
				'name' => $title,
				'mimeType' => "application/pdf"
			));
	
		$content = file_get_contents('test.pdf');
		$file = $service->files->create($fileMetadata, array(
			'data' => $content,
			'mimeType' => "application/pdf",
			'uploadType' => 'multipart',
			'fields' => 'id'));
			
			return $file->getId();
		} catch(Exception $e) {
			print $e->getMessage();
			return NULL;
		}
		
		
	}
	
	function downloadPdf($service, $fileId, $saveName) {
		try 
		{
			$response = $service->files->get($fileId, array('alt' => 'media'));
			file_put_contents($saveName, $response->getBody()->getContents());
			
		} catch(Exception $e) {
			print "Error: " . $e->getMessage();
		}
	}
	
	
	
	function type($mime) {
		INCLUDE "google drive constants.php";
		if($mime == $CONST["MIMETYPE_FOLDER"]) {
			return "Folder";
		} else {
			return "File";
		}
	}
	
	function getClient() {
	
		if (file_exists('service-account.json')) {
			putenv('GOOGLE_APPLICATION_CREDENTIALS=service-account.json');
		} else {
			putenv('GOOGLE_APPLICATION_CREDENTIALS=../scripts/service-account.json');
		}

		
		$client = new Google_Client();
		$client->useApplicationDefaultCredentials();
		$client->setScopes(Google_Service_Drive::DRIVE);

		return $client;
	}
?>