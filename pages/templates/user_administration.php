<?php
	session_start();
	include_once 'functions.php';
	
	if(isset($_POST['user_email'])) {
		// form has been submitted
		
		default_connect();
		$result = query("SELECT voice_id
						 FROM voices
						 WHERE voice_display_name = '{$_POST['user_voice']}'");
		$voice_id = fetch_next_row($result)[0];
		insert($GLOBALS["USERS_TABLE"], array($GLOBALS["COLUMN_USER_EMAIL"] => $_POST["user_email"],
											  $GLOBALS["COLUMN_USER_PASSWORD"] => hash("sha256", $_POST["user_password"]),
											  $GLOBALS["COLUMN_USER_PHONE"] => $_POST["user_phone"],
											  $GLOBALS["COLUMN_USER_PLACE"] => $_POST["user_place"],
											  $GLOBALS["COLUMN_USER_POSTAL_CODE"] => $_POST["user_postal_code"],
											  $GLOBALS["COLUMN_USER_STREET"] => $_POST["user_street"],
											  $GLOBALS["COLUMN_USER_HOUSE_NUMBER"] => $_POST["user_house_number"],
											  $GLOBALS["COLUMN_VOICE_ID"] => $voice_id));
	    $_SESSION[$GLOBALS["CURRENT_USER"]] = $_POST["user_email"];
	}
?>

<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="user_administration.css" />	
	</head>
	<body>

		<script src="../../res/jquery-3.1.1.min.js"></script>
	
		<ul>
			<li class="menu_item user_creation" >Benutzer Anlegen</li>
			<li class="menu_item user_alteration " >Benutzer Bearbeiten</li>
		</ul>
		
		<div class="content user_creation_content" >
			<form action="user_administration.php" method="post">
				<div class="group_box">
					<p class="header">Allgemeines</p>
					<table>
						<tr class="item">
							<td class="header">E-Mail:</td>
							<td><input spellcheck="false" type="text" name="user_email" /></td>
						</tr>
						<tr class="item">
							<td class="header">Passwort:</td>
							<td><input spellcheck="false" type="password" name="user_password" /></td>
						</tr>
						<tr class="item">
							<td class="header">Tel.:</td>
							<td><input spellcheck="false" type="text" name="user_phone" /></td>
						</tr>
					</table>
				</div>
				
				<div class="group_box">
					<p class="header">Adresse</p>
					
					<table>
						<tr class="item">
							<td class="header">Ort:</td>
							<td><input spellcheck="false" type="text" name="user_place" /></td>
						</tr>
						<tr class="item">
							<td class="header">PLZ:</td>
							<td><input spellcheck="false" type="text" name="user_postal_code" /></td>
						</tr>
						<tr class="item">
							<td class="header">Straße:</td>
							<td><input spellcheck="false" type="text" name="user_street" /></td>
						</tr>
						<tr class="item">
							<td class="header">Hausnummer:</td>
							<td><input spellcheck="false" type="text" name="user_house_number" /></td>
						</tr>
					</table>
				</div>
				
				<div class="group_box">
					<p class="header">Stimme</p>
					<table>
						<tr class="item">
							<td class="header">Stimmgattung:</td>
							<td>
								<select class="selection_box" name="user_voice" >
									<!-- insert php code here: voices are fetched from database -->
									<?php
									
										default_connect();
										
										$result = query("SELECT voice_display_name
											             FROM voices;");
										
										while($row = fetch_next_row($result)) {
											?>
												<option><?php echo $row[0] ?></option>
											<?php
										}
										
										disconnect();
									
									?>
								</select>
							</td>
						</tr>
					</table>
				</div>
				
				<button type="submit">Bestätigen</button>
			</form>
		</div>
		
		<div class="content user_alteration_content">
			benutzer bearbeiten
		</div>
		
		<script>
					
			function hideAllContentContainers() {
				$(".content").hide();
			}
			
			hideAllContentContainers();
			$(".user_creation_content").show(); // default setting ; display change page for users
			
		
			$(".user_creation").click(function() {
				hideAllContentContainers();
				$(".user_creation_content").show();
			});
			
			$(".user_alteration").click(function() {
				hideAllContentContainers();
				$(".user_alteration_content").show();
			});

		
		</script>
	</body>

</html>