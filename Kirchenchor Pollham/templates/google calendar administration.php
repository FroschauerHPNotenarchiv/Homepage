<?php 
	session_start();
	require_once 'google calendar general func.php';
	
	if(isset($_POST['verify'])) {
		
		$externId = verifyCalendar($_POST['externId']);
		$internId = verifyCalendar($_POST['internId']);
		
		if($internId) {
			$intern = "../images/verify-ok.png";
		} else {
			$intern = "../images/verify-fail.png";
		}
		if($externId) {
			$extern = "../images/verify-ok.png";
		} else {
			$extern = "../images/verify-fail.png";
		}
	}
	else if(isset($_POST['save'])) {
		$response = array(
			'intern' => $_POST['internId'],
			'extern' => $_POST['externId']
		);
		$fp = fopen('results.json', 'w');
		fwrite($fp, json_encode($response));
		fclose($fp);
	} else {
		$_POST['externId'] = $_PUBLIC_CALENDAR_ID;
		$_POST['internId'] = $_INTERN_CALENDAR_ID;
	}
	
?>

<!DOCTYPE html>
<html>

	<style>
		label {
			text-align: left;
			float: left;
			width: 100px;
			font-weight: bold;
		}
		
		p {
			clear: both;
			font-family: Trebuchet MS;
		}
		
		input {
			width: 80%;
		}
		
		form {
			width: 300px;
		}
		
		img {
			width: 24px;
			height: 24px;
		}
		
		p.changed {
			color: green;
			padding: 5px;
			font-family: Tahoma;
			background: lime;
			border-radius: 10px;
			
		}
	</style>
	<head>
		
	</head>
	
	<body>
		
		<form method="post">
			<fieldset>
				<legend>Kalenderdaten</legend>
				<p><label>Interne ID:</label>
					<input name="internId" value="<?php echo $_POST['internId'] ?>"></input>
					
					<?php if(isset($_POST['verify'])) : ?>
						<img src="<?php echo $intern;?>"></img>
					<?php endif; ?>
					
					<?php if(isset($_POST['verify'])) : ?>
						<p><?php echo $internId ?></p>
					<?php endif; ?>
					
				</p>
				
				
				<p><label>Externe ID:</label><input name="externId" value="<?php echo $_POST['externId'] ?>"></input>
				<?php if(isset($_POST['verify'])) : ?>
						<img src="<?php echo $extern;?>"></img>
					<?php endif; ?>
					
					<?php if(isset($_POST['verify'])) : ?>
						<p><?php echo $externId ?></p>
					<?php endif; ?>
				</p>
				<p><button name="verify" type="submit">Verifizieren</button><button name="save" type="submit">Speichern</button></p>
				
				<?php if(isset($_POST['save'])) : ?>
			<p class="changed">Die Kalenderdaten wurden erfolgreich geändert!</p>
			<a class="changed" href="../Startseite.php">Zurück zur Startseite!</a> 
			<?php endif;?>
			
			</fieldset>
			
		</form>
		
	</body>
</html>