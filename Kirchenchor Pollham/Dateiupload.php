<?php
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