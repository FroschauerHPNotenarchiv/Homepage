<?php

	$title = "<Undefined>";
	$text = "<Undefined>";
	if(file_exists('scripts/indexpage-data.json')) {
		$data = json_decode(file_get_contents('scripts/indexpage-data.json'), true);
		
		$title = $data['title'];
		$text = $data['summary'];
	}
	
	
	if(isset($_POST['action-edit'])) { // AND isAdmin()
		
		$title = $_POST['editHeader'];
		$text = $_POST['editText'];
		
		$json = array(
			'title' => htmlspecialchars($title),
			'summary' => htmlspecialchars($text)
		);
		
		$fp = fopen('scripts/indexpage-data.json', 'w');
		fwrite($fp, json_encode($json));
		fclose($fp);
		
	}
	
?>