<?php
	//include "templates/google_drive_func.php";
	include "templates/download_pdf.php";
	//include "cache/cache_func.php";
	
	foreach($files as $f) {
		echo $f->getId() . " / " . $f->getName() . " / </br>";
		$service->files->delete($f->getId());
	}
	
	
?>
<!DOCTYPE html>
<html>
</html>