<?php
	function update_cache($service, $folderId, $filename) {
		$googleFolders = get_files($service, "'" . $folderId . "' in parents and mimeType = 'application/vnd.google-apps.folder'");
		$cache = array();
		foreach($googleFolders as $folder) {
			$googleFiles = get_files($service, "'" . $folder->getId() . "' in parents");
			foreach($googleFiles as $f) {
				$file = $service->files->get($f->getId(), array("fields" => "id,name,properties"));
				$cache[$f->getId()] = array (
				  'name' => $file->getName(),
				  'parents' => $folder->getId(),
				  'properties' => $file->getProperties()
				);
			}
		}
		
		file_put_contents($filename, json_encode($cache));
	}
	
	function get_files_from_folder($file, $folderId) {
		$array = get_cache_content($file);
		foreach($array as $fileId => $fileValue) {
			if($fileValue["parents"] != $folderId) {
				unset($array[$fileId]);
			}
		}
		return $array;
	}
	
	function get_cache_content($file) {
		return json_decode(file_get_contents($file), true);
	}
	
	function needs_refresh($file) {
		$timediff = time() - filemtime($file);
		/* If the file is older than 5 minutes - Refresh */
		if($timediff > 5 * 60) {
			return true;
		}
		return false;
	}
	
	function get_files($service, $query = null) {
	  $result = array();
	  $pageToken = NULL;

	  do {
		try {
		  $parameters = array();
		  $parameters['q'] = $query;
		  if ($pageToken) {
			$parameters['pageToken'] = $pageToken;

		  }
		  $files = $service->files->listFiles($parameters);

		  // getItems() bzw. listFiles() sind veraltet: getFiles() !!!
		  $result = array_merge($result, $files->getFiles());
		  $pageToken = $files->getNextPageToken();
		} catch (Exception $e) {
		  print "An error occurred: " . $e->getMessage();
		  $pageToken = NULL;
		}
	  } while ($pageToken);
	  return $result;
	}
?>