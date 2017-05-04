<?php
	$owner;
	$pdf;
	$mp3;
	$root;
	
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
?>