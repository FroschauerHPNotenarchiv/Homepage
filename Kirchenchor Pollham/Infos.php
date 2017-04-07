<?php
	//INCLUDE "templates/upload_pdf.php";
	INCLUDE "templates/download_pdf.php";
	
	$voices = getVoices();
	$categories = getAdditionalCategories();
	
<<<<<<< HEAD
	function getNiceName($name)
	{
		$pos = strpos($name, ".", 0);
		return substr($name, 0, $pos) . ".pdf";

	if(isset($_POST["action-edit"]))
	{
		$newFile = new Google_Service_Drive_DriveFile();
		echo name($_POST["fileId"], $_POST["fileName"], getCategories($_POST));
		$newFile->setName(name($_POST["fileId"], $_POST["fileName"], getCategories($_POST)));
		$service->files->update($_POST["fileId"], $newFile, array("mimeType" => "application/pdf"));
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
		//$googleFileName = $googleFileName . ".pdf";
		
>>>>>>> b2082c116368a810923e4e87d8cb2650f2743565
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
<<<<<<< HEAD
</head>
<body>
=======
 <style>
	h4.action-link:hover {
		color: pink;
	}
</style>
</head>
<body>

<?php 
	if(isset($_GET["action"]) && $_GET["action"] === "alter") // &isAdmin
	{
		INCLUDE "templates/alter-modal.html";
	}
?>

>>>>>>> b2082c116368a810923e4e87d8cb2650f2743565
<div class="container">
  <header>
    <div class="primary_header">
     <h1 class="title"> <img class="placeholder" src="images/logo.png" alt="Logo"> </h1>
    </div>
    <nav class="secondary_header" id="menu">
      <ul>
       <li><a href="Startseite.html">Startseite</a></li>
        <li><a href="Mitglieder.html">Mitglieder</a></li>
        <li><a href="News.html">News/Termine</a></li>
        <li><a href="Infos.html">Infos</a></li>
        <li><a href="Administration.html">Administration</a></li>
      </ul>
    </nav>
  </header>
 
 <section>
    <h2 class="noDisplay">Main Content</h2>
    <article class="left_article">
    <div>
      <h3 class="titel_info">Musikstücke:</h3>
      <button type="button" class="btn btn-sm btn-default button_bearbeiten"><img class="icon_bearbeiten" src="images/bearbeiten.png" /></button>
    </div>  
      <div class="stimmgattung"><h4>Stimmgattungen:</h4>
        <div class="checkboxes" id="Stimmgattungen">
			<a href="Dateiupload.php">Ein Musikstück hochladen</a>
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
              <label class="checkbox" for="<?php echo $cat?>"><?php echo $cat?></label>
			<?php
			}
		  ?>
		    <button type="submit" name="do_search">Suchen</button>
		    </form>
        </div>
      </div>
	  <div>
		<div class="row">
					<?php 
						foreach($files as $file)
						{
							?>
<<<<<<< HEAD
								<div class="columns" value="Motherfucker"> <!-- data-toggle="modal" data-target="#MemberModal" -->
								  <img src="hallo" alt="" class="thumbnail"/>
								  <a href="Infos.php?id=<?php echo $file->getId()?>"><h4><?php echo getNiceName($file->getName())?></h4></a>
=======
								<div class="columns">
								  <img src="images/pdficon.png" alt="" class="thumbnail"/>
								  <a href="Infos.php?id=<?php echo $file->getId()?>"><h4 class="action-link"><?php echo getNiceName($file->getName()) . ".pdf"?></h4></a>
								  <a onclick="return confirm('Wollen Sie diese Datei wirklich entfernen?');" href="Infos.php?id=<?php echo $file->getId()?>&action=delete"><h4 class="action-link">Diese Datei entfernen</h4></a>
								  <a href="Infos.php?id=<?php echo $file->getId()?>&action=alter"><h4 class="action-link">Diese Datei bearbeiten</h4></a>
>>>>>>> b2082c116368a810923e4e87d8cb2650f2743565
								</div>
							<?php
						}
					?>
		</div>
	  </div>
    </article>

    
    <aside class="right_article">
    <h3>Chortemine:</h3>
		<?php INCLUDE "templates/calendar_temp.php";?>
    </aside>
</section>
 
<<<<<<< HEAD
  <footer class="secondary_header footer">
    <div class="copyright">&copy;Lukas Knoll | Niklas Graf | Sebastian Mandl</div>
  </footer>
</div>
=======
  <footer class="footer">
    <div class="copyright">&copy;Lukas Knoll | Niklas Graf | Sebastian Mandl</div>
  </footer>
</div>

<!-- Datei bearbeiten Modal -->
<script type="text/javascript" src="scripts/infos.js"></script>

>>>>>>> b2082c116368a810923e4e87d8cb2650f2743565
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
