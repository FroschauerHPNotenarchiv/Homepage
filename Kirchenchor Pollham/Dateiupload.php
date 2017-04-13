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
			<?php
				foreach(getAdditionalCategories() as $cat)
					{
						?>
							<input type="checkbox" name="<?php echo $cat?>"><?php echo $cat?></input>
						<?php
					}
			?>
			</fieldset>
			
			<input type="file" name="file"/>

			<button type="submit" name="fileSubmit">Lalala</button>

		</form>
		
	</body>
</html>