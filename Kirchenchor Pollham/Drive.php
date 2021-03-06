<?php
	INCLUDE_ONCE "templates/admin_user_administration_func.php";
	INCLUDE_ONCE "templates/admin_constants.php";
	
	/*
	if(getUserRole(getUserEmail()) > $GLOBALS["ROLES_SUBADMIN"]) {
		header("HTTP/1.0 404 Not Found");
		die();
	}
	*/
	
	$owner;
	$pdf;
	$mp3;
	$root;
	$categories;
	
	if(isset($_POST["add"])) {
		$toAdd = $_POST["new"];
		$ctgrys = getAdditionalCategories();
		array_push($ctgrys, $toAdd);
		$fp = fopen('templates/categories.csv', 'w');
		fputcsv($fp, $ctgrys);
		fclose($fp);
	}
	if(isset($_POST["delete"])) {

		$toDelete = $_POST["del"];
		$ctgrys = getAdditionalCategories();
		$i = 0;
		foreach($ctgrys as $c) {
			if($c == $toDelete) {
				unset($ctgrys[$i]);
				break;
			}
			$i++;
		}
		$fp = fopen('templates/categories.csv', 'w');
		fputcsv($fp, $ctgrys);
		fclose($fp);
	}
	
	if(isset($_POST["submit"])) {
		$config = json_decode(file_get_contents("scripts/drive_config.json"), true);
		$config["root_folder_id"] = $_POST["root_folder_id"];
		$config["drive_owner"] = $_POST["drive_owner"];
		$config["pdf_folder"] = $_POST["pdf_folder"];
		$config["mp3_folder"] = $_POST["mp3_folder"];
		
		file_put_contents("scripts/drive_config.json", json_encode($config));
		header("Location: Infos.php");
	} else {
		$config = json_decode(file_get_contents("scripts/drive_config.json"), true);
		$owner = $config["drive_owner"];
		$root = $config["root_folder_id"];
		$pdf = $config["pdf_folder"];
		$mp3 = $config["mp3_folder"];
	}
	
	include "templates/driveconfig-modal.html";
	
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