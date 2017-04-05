<!DOCTYPE html>
<html>
	<head></head>
	
	<body>
	Hello!
		<?php
			INCLUDE "google_drive_func.php";
			
			$client = getClient();
			$service = new Google_Service_Drive($client);
			
			$file = uploadPdf($service, "test.pdf", "TEST.pdf");
			$lol = $service->files->get($file->getId());
			echo count($lol->getAppProperties());
		?>
	</body>
</html>