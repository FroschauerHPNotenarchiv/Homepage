<?php
	INCLUDE_ONCE "templates/admin_user_administration_func.php";
	
	if(getUserRole(getUserEmail()) > $GLOBALS["ROLES_SUBADMIN"]) {
		header("HTTP/1.0 404 Not Found");
		die(file_get_contents("templates/error.php"));
	}
	INCLUDE "templates/upload_pdf.php";
	
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Dateiupload</title>
	</head>
	
	<body>
		<form action="" method="post" enctype="multipart/form-data">
			<fieldset>
			<legend>Stimmen:</legend>
				<?php
					foreach(getVoices() as $voice)
					{
						?>
							<input type="checkbox" name="<?php echo $voice?>"><?php echo $voice?></input>
						<?php
					}
				?>
			</fieldset>
			<fieldset>
			<legend>Tags:</legend>
			<?php
				foreach(getAdditionalCategories() as $cat)
					{
						?>
							<input type="checkbox" name="<?php echo $cat?>"><?php echo $cat?></input>
						<?php
					}
			?>
			</fieldset>
			<p>Drive-Ordner:</p>
			<select name="folder">
				<?php foreach($folders as $folderId => $folderName) {
					?> <option name="<?php echo $folderId?>" value="<?php echo $folderId?>"><?php echo $folderName ?></option> <?php
				}
				?>
			</select>
			<p>Ornder nicht mehr aktuell? <button type="submit" name="refresh">Update!</button> </p>
			<input onclick="toggleLink()" id="linkBox" type="checkbox">Medienlink</input>
			<p style="display:none" id="link"><input type="text" name="media-link"/></p>
			<br/>
			<input type="file" name="file"/>
			<button type="submit" name="fileSubmit">Upload</button>

		</form>
		
	</body>
	
	<script>
		function toggleLink() {
			var item = document.getElementById("linkBox");
			var textbox = document.getElementById("link");
			if(item.checked) {
				textbox.style.display = "block";
			} else {
				textbox.style.display = "none";
			}
		}
	</script>
</html>