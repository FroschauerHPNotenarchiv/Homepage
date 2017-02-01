<?php
	REQUIRE_ONCE "../vendor/autoload.php";
	INCLUDE "google drive constants.php";
	
	$service = new Google_Service_Drive(getClient());
	$filters = retrieveAllFiles($service, "'0BxWIS_jgbetbRDhuT2ZCU3VhTFk' in parents and mimeType = '" . $CONST["MIMETYPE_FOLDER"] . "'");
	
	// 0BxWIS_jgbetbdzh1akhfUlVuajQ
	// 0BxWIS_jgbetbRDhuT2ZCU3VhTFk - The root FOLDER
	
	// Scan for Files:  mimeType != 'application/vnd.google-apps.folder'
	
	function retrieveAllFiles($service, $query = null) {
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
	
	
	
	function type($mime) {
		INCLUDE "google drive constants.php";
		if($mime == $CONST["MIMETYPE_FOLDER"]) {
			return "Folder";
		} else {
			return "File";
		}
	}
	
	function getClient() {
	
		if (file_exists('service-account.json')) {
			putenv('GOOGLE_APPLICATION_CREDENTIALS=service-account.json');
		} else {
			putenv('GOOGLE_APPLICATION_CREDENTIALS=../scripts/service-account.json');
		}

		
		$client = new Google_Client();
		$client->useApplicationDefaultCredentials();
		$client->setScopes(Google_Service_Drive::DRIVE);

		return $client;
	}
?>
<html>
	<head></head>
	
	<body>
		<h1>All Available Filters:</h1>
		<form method="post" action="">
			<select id="filter" name="filter">
				<?php
					foreach($filters as $folder) {
						?> <option value="<?php echo $folder->getId()?>"><?php echo $folder->getName()?></option><?php
					}
				?>
			</select>
			<input type="text" name="query" placeholder="Nach Dateinamen suchen ..."/>
			
			<button name="search" value="yes">Suchen!</button>
		</form>
		
		<?php
			if(isset($_POST["search"])) {
				$id = $_POST["filter"];
				$name = $_POST["query"];
				if( strlen($name) < 1) {
					$q = " '" . $id . "' in parents";
				} else {
					$q = " '" . $id . "' in parents and name contains '" . $name . "'";
				}
				
				$arr = retrieveAllFiles($service, $q);
				
				foreach($arr as $entry) { ?> 
					<div style="margin: 10px">
					<img src="../images/pdficon.png" style="width:30px;"/>
					<label><?php echo $entry->getName(); ?></label>
					</div>
					<?php
				}
			}
		?>
	</body>
</html>