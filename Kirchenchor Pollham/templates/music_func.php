<?php
	//$folderId = '0B0dXPPQill-kWnBwRHJ1ZHVIUWM';
	// 0B0dXPPQill-kWnBwRHJ1ZHVIUWM

	//uploadMp3($service, "templates/musicfile.mp3", "Musik4Life.mp3");
	
	function uploadMp3($service, $path, $name) {
		$fileMetadata = new Google_Service_Drive_DriveFile(array(
		  'name' => $name,
		  'parents' => array('0B0dXPPQill-kWnBwRHJ1ZHVIUWM'))
		);
		$content = file_get_contents($path);
		$file = $service->files->create($fileMetadata, array(
		  'data' => $content,
		  'mimeType' => 'audio/mpeg',
		  'uploadType' => 'multipart',
		  'fields' => 'id')
		);
	}
	
	function downloadMp3($service, $fileId, $saveToPath) {
		try 
		{
			$response = $service->files->get($fileId, array('alt' => 'media'));
			file_put_contents($saveToPath, $response->getBody()->getContents());
			
		} catch(Exception $e) {
			print "Error: " . $e->getMessage();
		}
	}
	
	function saveMp3ToClient($path, $listen)
	{
		$dis = $listen == true ? "inline" : "attachment";
		echo $dis;
		//header('Content-Description: File Transfer');
		header('Content-Type: audio/mpeg');
		header("Content-Transfer-Encoding: Binary"); 
		header("Content-disposition: " . $dis . "; filename=\"" . basename($path) . "\""); // inline
		readfile($path);
		unlink($path);
	}

	
?>