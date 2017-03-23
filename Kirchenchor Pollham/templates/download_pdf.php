<?php
	include "google_drive_func.php";
	include "admin_user_administration_func.php";
	
	$service = new Google_Service_Drive(getClient());
	$files = array();
	if(isset($_POST['do_search']))
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