<?php
	include "templates/google_drive_func.php";
	
	$service = new Google_Service_Drive(getClient());
	
	$fileMetadata = new Google_Service_Drive_DriveFile(array(
  'name' => 'Musik MP4',
  'mimeType' => 'application/vnd.google-apps.folder'));
  
$file = $service->files->create($fileMetadata, array(
  'fields' => 'id'));
printf("Folder ID: %s\n", $file->id);
?>
<!DOCTYPE html>
<html>
	<head>
	</head>
	
	<body>
	</body>
</html>