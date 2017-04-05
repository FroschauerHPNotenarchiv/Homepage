<?php
	INCLUDE "templates/upload_pdf.php";
<<<<<<< HEAD
=======
	
>>>>>>> b2082c116368a810923e4e87d8cb2650f2743565
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
<<<<<<< HEAD
			<button type="submit" name="fileSubmit">Lalala</button>
=======
			<button type="submit" name="fileSubmit">UPLOAD</button>
>>>>>>> b2082c116368a810923e4e87d8cb2650f2743565
		</form>
		
	</body>
</html>