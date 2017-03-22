
		<?php
	REQUIRE_ONCE "../vendor/autoload.php";
	
	/*
	$service = new Google_Service_Drive(getClient());
	$fileZ = retrieveAllFiles($service);
	$fileZ = getFilesWithCategory($service, true, array("tenor", "bass"));
	
	foreach($fileZ as $f)
	{
		echo $f->getName();
		echo "<br/>";
	}
	*/
	
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
	
	function getFilesWithCategory($service, $exclusive = true, $categorys = array())
	{
		$query = 'name contains ';
		if(count($categorys) == 0)
		{
			return retrieveAllFiles($service, null);
		}
		$condition = $exclusive ? " and name contains " : " or name contains ";
		
		$lastIndex = count($categorys) - 1;
		
		foreach($categorys as $index => $cat)
		{
			$query = $query . "'" . $cat . "'";
			if($index != $lastIndex)
			{
				$query = $query . $condition;
			}
		}
		
		return retrieveAllFiles($service, $query);
	}
	
	function savePdfToClient($path)
	{
		header('Content-Type: application/pdf');
		header("Content-Transfer-Encoding: Binary"); 
		header("Content-disposition: attachment; filename=\"" . basename($path) . "\""); 
		readfile($path);
		unlink($path);
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
	
	function uploadPdf($service, $filename, $properties) {
	     sort($properties);
		$googleFileName = pathinfo($filename, PATHINFO_FILENAME);
		foreach($properties as $prop)
		{
			$googleFileName = $googleFileName . "." . $prop;
		}
		$googleFileName = $googleFileName . ".pdf";
		try {
			$fileMetadata = new Google_Service_Drive_DriveFile(array(
				'name' => $googleFileName,
				'mimeType' => "application/pdf"
			));
	
		$content = file_get_contents("../pdf/" . $filename);
		$file = $service->files->create($fileMetadata, array(
			'data' => $content,
			'mimeType' => "application/pdf",
			'uploadType' => 'multipart',
			'fields' => 'id'));
			
			unlink("../pdf/" . $filename);
			return $file;
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
		} else if (file_exists('scripts/service-account.json')){
			putenv('GOOGLE_APPLICATION_CREDENTIALS=scripts/service-account.json');
		} else {
			putenv('GOOGLE_APPLICATION_CREDENTIALS=../scripts/service-account.json');
		}

		
		$client = new Google_Client();
		$client->useApplicationDefaultCredentials();
		$client->setScopes(Google_Service_Drive::DRIVE);

		return $client;
	}
?>

