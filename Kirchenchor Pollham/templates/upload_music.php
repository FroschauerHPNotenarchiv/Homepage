<?php
	include_once "google_drive_func.php";
	//include "admin_user_administration_func.php";
	
	$service = new Google_Service_Drive(getClient());
	
	$fileOk = 0;
	if(isset($_POST['fileSubmit']) && !empty($_FILES["file"]["tmp_name"]))
	{
		$fileOk = 1;
		$type = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
		if($type != "mp3")
		{
			$fileOk = 0;
		}
		
		if($fileOk == 1)
		{
			$ok = move_uploaded_file($_FILES["file"]["tmp_name"], "music/" . $_FILES["file"]["name"]);
			uploadMp3($service, "music/" . $_FILES["file"]["name"], $_FILES["file"]["name"]);
			unlink("music/" . $_FILES["file"]["name"]);
			if($ok)
			{
				header("Location: Musik.php");
			}
			else
			{
				echo "Failure.";
			}
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Musikupload</title>
	</head>
	
	<body>
		<form action="" method="post" enctype="multipart/form-data">
			<input type="file" name="file"/>
			<button type="submit" name="fileSubmit">Upload</button>
		</form>
		
	</body>
</html>