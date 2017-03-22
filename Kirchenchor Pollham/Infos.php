<?php
	//INCLUDE "templates/upload_pdf.php";
	INCLUDE "templates/download_pdf.php";
	
	$voices = getVoices();
	$categories = getAdditionalCategories();
	
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
</head>
<body>
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
		  <?php
		    foreach($voices as $voice)
			{
			?>
			  <input  type="checkbox" id="<?php echo $voice?>">
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
			  <input  type="checkbox" id="<?php echo $cat?>">
              <label class="checkbox" for="<?php echo $cat?>"><?php echo $cat?></label>
			<?php
			}
		  ?>
        </div>
      </div>
	  <div>
		<div class="row">
			<div class="columns" value="hello"> <!-- data-toggle="modal" data-target="#MemberModal" -->
				<?php
					foreach($files as $file)
					{
						?>
						<img src="" alt="" class="thumbnail"/>
						<h4><?php echo $file->getName()?></h4>
						<a href="Infos.php?id=<?php echo $file->getId();?>">Herunterladen</a>
						<?php
					}
			      ?>
		    </div>
		</div>
	  </div>
    </article>

    
    <aside class="right_article">
    <h3>Chortemine:</h3>
		<?php INCLUDE "templates/calendar_temp.php";?>
    </aside>
</section>
 
  <footer class="secondary_header footer">
    <div class="copyright">&copy;Lukas Knoll | Niklas Graf | Sebastian Mandl</div>
  </footer>
</div>
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
