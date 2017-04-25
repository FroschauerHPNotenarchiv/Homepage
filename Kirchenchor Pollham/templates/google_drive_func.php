
		<?php
	REQUIRE_ONCE "../vendor/autoload.php";
	
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
		$query = '(name contains ';
		if(count($categorys) == 0)
		{
			return retrieveAllFiles($service, "mimeType != 'application/vnd.google-apps.folder'");
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
		
		$query = $query . ") and mimeType != 'application/vnd.google-apps.folder'";
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
	
	function uploadPdf($service, $filename, $properties, $link = null) {
	     sort($properties);
		$googleFileName = pathinfo($filename, PATHINFO_FILENAME);
		foreach($properties as $prop)
		{
			$googleFileName = $googleFileName . "." . $prop;
		}
		$googleFileName = $googleFileName . ".pdf";
		try {
			$fileMetadata = new Google_Service_Drive_DriveFile(array(
				'parents' => array("0B9hlE0xVQusSbHZSUVBKMTMxQ1k"),
				'owners' => array("chorpollham@gmail.com", "homepage@hpkirchenchorpollham.iam.gserviceaccount.com"),
				'name' => $googleFileName,
				'mimeType' => "application/pdf",
				'properties' => array('youtube-link' => $link)
			));
	
		$content = file_get_contents("pdf/" . $filename);
		$file = $service->files->create($fileMetadata, array(
			'data' => $content,
			'mimeType' => "application/pdf",
			'uploadType' => 'multipart',
			'fields' => 'id'));
			
			unlink("pdf/" . $filename);
			return $file;
		} catch(Exception $e) {
			print $e->getMessage();
			return NULL;
		}
		
	}
	
	function getFileMimeType($file) {
    if (function_exists('finfo_file')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $type = finfo_file($finfo, $file);
        finfo_close($finfo);
    } else {
        require_once 'upgradephp/ext/mime.php';
        $type = mime_content_type($file);
    }

    if (!$type || in_array($type, array('application/octet-stream', 'text/plain'))) {
        $secondOpinion = exec('file -b --mime-type ' . escapeshellarg($file), $foo, $returnCode);
        if ($returnCode === 0 && $secondOpinion) {
            $type = $secondOpinion;
        }
    }

    if (!$type || in_array($type, array('application/octet-stream', 'text/plain'))) {
        require_once 'upgradephp/ext/mime.php';
        $exifImageType = exif_imagetype($file);
        if ($exifImageType !== false) {
            $type = image_type_to_mime_type($exifImageType);
        }
    }

    return $type;
}
	
	function uploadFile($service, $filename, $properties, $parentId, $link = null) {
		$mime = getFileMimeType("pdf/" . $filename);
		$jsonData = json_decode(file_get_contents("scripts/drive_config.json"), true);
		$fileMetadata = new Google_Service_Drive_DriveFile(array(
				'parents' => array($parentId),
				'owners' => array($jsonData["drive_owner"]),
				'name' => pathinfo($filename, PATHINFO_BASENAME),
				'mimeType' => $mime,
				'properties' => array('youtube-link' => $link, 'categories' => $properties)
		));
		
		$content = file_get_contents("pdf/" . $filename);
		$file = $service->files->create($fileMetadata, array(
			'data' => $content,
			'mimeType' => $mime,
			'uploadType' => 'multipart',
			'fields' => 'id,parents'));
			
			unlink("pdf/" . $filename);
			return $file;
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

