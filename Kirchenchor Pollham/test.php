<?php
	include "google_drive_func.php";
	
	$client = getClient();
	$service = new Google_Service_Drive($client);
	
	// Delete all files
	foreach(retrieveAllFiles($service, null) as $f) {
		$service->files->delete($f->getId());
	}
	
	$fileMetadata = new Google_Service_Drive_DriveFile(array(
		'name' => 'TEST.csv',
		'mimeType' => "text/plain",
		'properties' => array('yt' => 'myCustomMetaData')
	));

	$content = file_get_contents("categories.csv");
	$file = $service->files->create($fileMetadata, array(
	'data' => $content,
	'mimeType' => "text/plain",
	'uploadType' => 'multipart',
	'fields' => 'id'));
	
	echo "Finished upload, fileId: " . $file->getId() . "</br>";
	
	$download = $service->files->get($files->getId(), array('fields' => 'properties'));
	var_dump($download->getProperties());
	
	foreach(retrieveAllFiles($service, null) as $f) {
		var_dump($f->getCategories());
	}
	
	
?>
<!DOCTYPE html>
<html>
</html>