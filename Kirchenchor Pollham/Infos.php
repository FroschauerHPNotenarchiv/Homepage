<?php
	INCLUDE_ONCE "templates/admin_user_administration_func.php";

	if(getUserRole(getUserEmail()) < $GLOBALS["ROLES_MEMBER"]) {
		header('HTTP/1.0 404 Not Found', true, 404);
		die();
	}

	//INCLUDE "templates/upload_pdf.php";
	INCLUDE "templates/download_pdf.php";



	$voices = getVoices();
	$categories = getAdditionalCategories();


	if(isset($_POST["action-edit"]))
	{
		$newFile = new Google_Service_Drive_DriveFile();
		$newFile->setName($_POST["fileName"]);
		$newFile->setProperties(array('youtube-link' => $_POST["editLink"], 'categories' => implode(";", getCategories($_POST))));
		$service->files->update($_POST["fileId"], $newFile, array()); // arr mimeType applPdf
		update_cache($service, $driveConfig["pdf_folder"], "cache/files.json");
		header("Location: Infos.php");
	}

	function getNiceName($name)
	{
		$pos = strpos($name, ".", 0);
		return substr($name, 0, $pos);
	}

	function isSelectedCategory($file, $category)
	{
		$name = $file->getName();
		if (strpos($name, str_replace(' ', '_', $category)) !== false) {
			return true;
		}
		return false;
	}

	function name($fileId, $filename, $properties)
	{
		sort($properties);
		$googleFileName = $filename;
		foreach($properties as $prop)
		{
			$googleFileName = $googleFileName . "." . $prop;
		}
		return $googleFileName . ".pdf";
	}


?>
<!doctype html>
<html>
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/main.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="css/infos.css" type="text/css">
	<link href="css/bootstrap-3.3.6.css" rel="stylesheet" type="text/css">
	<link href="jQueryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css">
	<link href="jQueryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css">
	<link href="jQueryAssets/jquery.ui.button.min.css" rel="stylesheet" type="text/css">
	<script src="jQueryAssets/jquery-1.11.1.min.js"></script>
	<script src="jQueryAssets/jquery.ui-1.10.4.button.min.js"></script>

	  <style>
	h4.action-link:hover {
		color: pink;
	}
  </style>
</head>

<body>

<?php
	if(isset($_GET["action"]) && $_GET["action"] === "alter" && getUserRole(getUserEmail()) <= $GLOBALS["ROLES_SUBADMIN"]) // &isAdmin
	{
		INCLUDE "templates/alter-modal.html";
	}
?>
<div class="container">
  <header>
    <div class="primary_header">
     <h1 class="title"> <img class="placeholder" src="images/logo.png" alt="Logo"> </h1>
    </div>
    <nav class="secondary_header" id="menu">
      <ul>
       <li><a href="Startseite.php">Startseite</a></li>
        <li><a href="Mitglieder.php">Mitglieder</a></li>
        <li><a href="News.php">News</a></li>
		<li><a href="Musik.php">Medien</a></li>
        <li><a href="Infos.php">Infos</a></li>
        <li><a href="Administration.php">Admin</a></li>
      </ul>
    </nav>
  </header>



 <section>
    <h2 class="noDisplay">Main Content</h2>
    <article class="left_article">
    <div>
      <h3 class="titel_info">Musikstücke:</h3>
	  <?php if(getUserRole(getUserEmail()) <= $GLOBALS["ROLES_SUBADMIN"]) : ?>
      <a href="Drive.php"><button type="button" class="btn btn-sm btn-default button_bearbeiten"><img class="icon_bearbeiten" src="images/bearbeiten.png" /></button></a>

    <button type='button' class="btn btn-sm btn-default" style="margin-left:5%;font-size: 15px;">
		<a href="Dateiupload.php">Ein Musikstück hochladen</a>

	</button>
	</div>

	  <?php endif; ?>
    </div>
      <div class="stimmgattung"><h4>Stimmgattungen:</h4>
        <div class="checkboxes" id="Stimmgattungen">
			<form action="" method="post">
		  <?php
		    foreach($voices as $voice)
			{
			?>
			  <input  type="checkbox" id="<?php echo $voice?>" name="<?php echo $voice?>">
              <label class="checkbox" for="<?php echo $voice?>"><?php echo $voice?></label>
			<?php
			}
		  ?>
        </div>
      </div>
      <div class="kategorie"><h4>Kategorien:</h4>
        <div class="checkboxes" id="Kategorien">
          <?php
		    foreach($categories as $cat)
			{
			?>
			  <input  type="checkbox" id="<?php echo $cat?>" name="<?php echo $cat?>">
              <label class="checkbox" for="<?php echo $cat?>"><?php echo $cat ?></label>
			<?php
			}
		  ?>
		  						<button type="submit" class="btn btn-sm btn-default" style="float:right; margin-right:25%; margin-top:15px;" name="do_search">Suchen</button>

		    </form>

        </div>
      </div>

	  <div>
		<div class="row">
					<?php
						if(count($files) == 0) {
							?> <p>Für diese Auswahl wurden keine Dateien gefunden.</p> <?php
						}
						foreach($files as $fileId => $file)
						{
							?>
								<div class="columns">
								  <a target="_blank" href="Infos.php?id=<?php echo $fileId?>&action=show"><img src="images/pdficon.png" alt="" class="thumbnail"/></a>
								  <h4><?php echo $file["name"]?></h4>

								  <h4>
								  <?php if($file["properties"] != null && !empty($file["properties"]["youtube-link"])) : ?>

								  <a href="<?php echo $file["properties"]["youtube-link"]?>" target="_blank">Hörprobe</a>
								  |
								  <?php endif; ?>
								  <a href="Infos.php?id=<?php echo $fileId?>&action=download">Herunterladen</a>
								  </h4>
								  <h4>
								  <?php if($role <= $GLOBALS["ROLES_SUBADMIN"]) : ?>
								  <a onclick="return confirm('Wollen Sie diese Datei wirklich entfernen?');" href="Infos.php?id=<?php echo $fileId?>&action=delete">Löschen</a>
								  |
								  <a href="Infos.php?id=<?php echo $fileId ?>&action=alter">Bearbeiten</a>
								  <?php endif; ?>
								  </h4>

								</div>


							<?php
						}
					?>
		</div>
	  </div>
    </article>


    <aside class="right_article">
    <h3>Chortemine:</h3>
		<?php INCLUDE "templates/calendar_temp.php"; ?>
    </aside>
</section>


  <footer class="footer">
    <div class="copyright">&copy;Lukas Knoll | Niklas Graf| Sebastian Mandl</div>
  </footer>
</div>

<!-- Datei bearbeiten Modal -->
<script type="text/javascript" src="scripts/infos.js"></script>

<script type="text/javascript">
$(function() {
	$( "#Stimmgattungen" ).buttonset();
});
</script>
<script type="text/javascript">
$(function() {
	$( "#Kategorien" ).buttonset();
});
</script>
</body>
</html>
