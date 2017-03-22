<?php
	include "google_drive_func.php";
	
	$service = new Google_Service_Drive(getClient());
	if(isset($_POST))
	{
		$files = getFilesWithCategory($service,true, getCategories($_POST));
	}
	else
	{
		$files = retrieveAllFiles($service);
	}
	
	if(isset($_GET["id"]))
	{
		$f = $service->files->get($_GET["id"]);
		$name = $f->getName();
		downloadPdf($service, $_GET["id"], $name);
		savePdfToClient($name);
	}
	
	function getCategories($array = array())
	{
		$categories = array();
		foreach($array as $key => $value)
		{
			if($value == "on")
				array_push($categories, $key);
		}
		return $categories;
	}
?>

<!DOCTYPE html>
<html>
	
	<form method="post">
		<button type="submit">Suchen</button>
	</form>
	
	<ul>
	<?php
		foreach($files as $file)
		{
			?>
				<li>
					<?php echo $file->getName();?>
					<a href="download_pdf.php?id=<?php echo $file->getId();?>">Download</a>
				</li>
			<?php
		}
	?>
	</ul>
	
</html>