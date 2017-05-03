<?php 
	include "google_drive_func.php";
	include "admin_user_administration_func.php";
	include "cache/cache_func.php";
	
	$folders = json_decode(file_get_contents("scripts/drive_config.json"), true)["folders"];
	$service = new Google_Service_Drive(getClient());
	
	if(isset($_POST['refresh'])) {
		$jsonData = json_decode(file_get_contents("scripts/drive_config.json"), true);
		$rootId = $jsonData["root_folder_id"];
		$files = retrieveAllFiles($service, "'" . $rootId . "' in parents and mimeType = 'application/vnd.google-apps.folder'");
		
		$jsonData["folders"] = array();
		foreach($files as $file) {
			$jsonData["folders"][$file->getId()] = $file->getName();
		}
		file_put_contents("scripts/drive_config.json", json_encode($jsonData));
	}
	if(isset($_POST['fileSubmit']) && !empty($_FILES["file"]["tmp_name"]))
	{
		$type = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
	
		$ok = move_uploaded_file($_FILES["file"]["tmp_name"], "pdf/" . $_FILES["file"]["name"]);
	//	uploadPdf($service, $_FILES["file"]["name"], getCategories($_POST), $_POST["media-link"]);
		$f = uploadFile($service, $_FILES["file"]["name"], getCategories($_POST), $_POST["folder"], $_POST["media-link"]);
		if($ok)
		{
			update_cache($service, json_decode(file_get_contents("scripts/drive_config.json"), true)["root_folder_id"], "cache/files.json");
			//header("Location: Infos.php");
		}
		else
		{
			echo "Failure.";
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
