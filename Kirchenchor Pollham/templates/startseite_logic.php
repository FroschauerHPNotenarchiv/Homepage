<?php
	$title = "<Undefined>";
	$text = "<Undefined>";
	$extension = "";
	$uploadText = "";
		
	if(isset($_POST['action-edit'])) { // AND isAdmin()	
		
			$target_dir = "images/";
			$type = pathinfo($_FILES["editImage"]["name"], PATHINFO_EXTENSION);
			$target_file = $target_dir . "index-image." . $type;
			$fileOk = 1;
			
			// If there has been selected a file to upload
			if(!empty($_FILES["editImage"]["tmp_name"])) {
				
				// Max File Size: 3 MB
				if($_FILES["editImage"]["size"] > 3000000) {
					$fileOk = 0;
					$uploadText = "FEHLER: Maximale Bildgröße: 3MB";
				}
				
				if(getimagesize($_FILES["editImage"]["tmp_name"]) == false) {
					$uploadText = "FEHLER: Die gewählte Datei (." . $type . ") scheint kein Bild zu sein";
					$fileOk = 0;
				}
				
				if($type !== "jpg" && $type !== "png" && $type !== "jpeg") {
					$fileOk = 0;
					$uploadText = "FEHLER: Der Dateityp ." . $type . " wird nicht unterstützt! Mögliche Dateiformate: jpg, png, jpeg";
				}
				
				if($fileOk == 1) {
					$uploaded = move_uploaded_file(
						$_FILES["editImage"]["tmp_name"], $target_file
					);
					
					if($uploaded) {
						$uploadText = "Das Bild wurde erfolgreich hochgeladen!";
					} else {
						$uploadText = "Beim Hochladen des Bildes ist ein Fehler aufgetreten";
					}
				}
				
			
		}
		
		
		$title = $_POST['editHeader'];
		$text = $_POST['editText'];
		
		$json = array(
			'title' => $title,
			'summary' => $text,
			'extension' => json_decode(file_get_contents('scripts/indexpage-data.json'), true)['extension']
		);
		
		if(!empty($_FILES["editImage"]["tmp_name"])&& $fileOk == 1) {
			$json['extension'] = $type;
		}
		
		$fp = fopen('scripts/indexpage-data.json', 'w');
		fwrite($fp, json_encode($json));
		fclose($fp);
	}
	
	
	if(file_exists('scripts/indexpage-data.json')) {
			$data = json_decode(file_get_contents('scripts/indexpage-data.json'), true);
			
			$title = $data['title'];
			$text = str_replace("\r\n", "</br>", $data['summary']);
			$extension = $data['extension'];
		
		}
		
	
?>