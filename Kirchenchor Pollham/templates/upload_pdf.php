<?php 
	include "google_drive_func.php";
	include "admin_user_administration_func.php";
	
	$service = new Google_Service_Drive(getClient());
	
	$fileOk = 0;
	if(isset($_POST['fileSubmit']) && !empty($_FILES["file"]["tmp_name"]))
	{
		$fileOk = 1;
		$type = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
		if($type != "pdf")
		{
			$fileOk = 0;
		}
		
		if($fileOk == 1)
		{
			$ok = move_uploaded_file($_FILES["file"]["tmp_name"], "pdf/" . $_FILES["file"]["name"]);
			uploadPdf($service, $_FILES["file"]["name"], getCategories($_POST), $_POST["media-link"]);
			if($ok)
			{
				header("Location: Infos.php");
			}
			else
			{
				echo "Failure.";
			}
		}
	}
	
	function getCategories($array)
	{
		$categories = array();
		foreach($array as $key => $value)
		{
			if($value == "on")
				array_push($categories, $key);
		}
		return $categories;
	}
	
	function getVoices()
	{
		$voices = array();
		
		// Dummy values
		array_push($voices, "Tenor", "Bass", "Supran");
		/*
		$result = query("SELECT voice_display_name FROM voices");
		foreach(fetch_next_row($result)[0] as $voice)
		{
			array_push($voices, $voice);
		}
		*/
		return $voices;
		
	}
	
	function getAdditionalCategories()
	{
		$csv = array_map('str_getcsv', file('templates/categories.csv'));
		$strings = array();
		foreach($csv[0] as $index => $val)
		{
			array_push($strings, $val);
		}
		return $strings;
	}

?>
