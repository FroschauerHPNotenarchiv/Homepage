<?php
	include "google_drive_func.php";
	include "admin_user_administration_func.php";
	include "cache/cache_func.php";
	
	$driveConfig = json_decode(file_get_contents("scripts/drive_config.json"), true);
	$service = new Google_Service_Drive(getClient());
	
	if(@needs_refresh("cache/files.json")) {
		@update_cache($service, $driveConfig["root_folder_id"], "cache/files.json");
	}
	
	$files = @get_files_from_folder("cache/files.json", $driveConfig["pdf_folder"]);	
	//update_cache($service, "0B0dXPPQill-kNlI3U1JOU2ZRMTg", "cache/files.json");
	
	if(isset($_POST['do_search']))
	{
		$files = filter_files_categories($files, getCategories($_POST));
		//$files = getFilesWithCategory($service,true, getCategories($_POST));
	}

	$showAlterDialog = false;
	
	if(isset($_GET["id"])) // & isAdmin()
	{
		try 
		{
			if(isset($_GET["action"]) && $_GET["action"] === "delete")
			{
				$f = $service->files->delete($_GET["id"]);
				update_cache($service, $driveConfig["root_folder_id"], "cache/files.json");
				header("Location: Infos.php");
			}
			else if(isset($_GET["action"]) && $_GET["action"] === "alter")
			{
				$showAlterDialog = true;
				update_cache($service, $driveConfig["root_folder_id"], "cache/files.json");
			}
			else
			{
				$f = $service->files->get($_GET["id"]);
				$name = "pdf/" . $f->getName();
				downloadPdf($service, $_GET["id"], $name);
				savePdfToClient($name);
			}
		}
		catch(Exception $e)
		{
			@update_cache($service, $driveConfig["root_folder_id"], "cache/files.json");
			header("Location: Infos.php");
		}
		

	}
	
	function filter_files_categories($files = array(), $categories) {
		foreach($files as $id => $value) {
			$fileCategories = $value["properties"]["categories"];
			foreach($categories as $cat) {
				if(strpos($fileCategories, $cat) != 0) {
					
				}
			}
			
		}
	}
	
	function filter_files($fileArray, $extension) {
		$arr = array();
		foreach($fileArray as $file) {
			if(endsWith($file["name"], $extension)) {
				array_push($arr, $file);
			}
		}
		return $arr;
	}
	
	function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}
	
	function getCategories($array = array())
	{
		$categories = array();
		foreach($array as $key => $value)
		{

			if($value == "on") {
				array_push($categories, $key);
			}
				
		}
		return $categories;
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
?>