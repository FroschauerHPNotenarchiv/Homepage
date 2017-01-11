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
											  $GLOBALS["COLUMN_USER_FIRSTNAME"] => $_POST["user_firstname"],
											  $GLOBALS["COLUMN_USER_LASTNAME"] => $_POST["user_lastname"],
											  $GLOBALS["COLUMN_USER_PASSWORD"] => hash("sha256", $_POST["user_password"]),
											  $GLOBALS["COLUMN_USER_PHONE"] => $_POST["user_phone"],
											  $GLOBALS["COLUMN_USER_PLACE"] => $_POST["user_place"],
											  $GLOBALS["COLUMN_USER_POSTAL_CODE"] => $_POST["user_postal_code"],
											  $GLOBALS["COLUMN_USER_STREET"] => $_POST["user_street"],
											  $GLOBALS["COLUMN_USER_HOUSE_NUMBER"] => $_POST["user_house_number"],
											  $GLOBALS["COLUMN_VOICE_ID"] => $voice_id));
	    $_SESSION[$GLOBALS["CURRENT_USER"]] = $_POST["user_email"];
		
		disconnect();
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
			<?php
				default_connect();
				
				$editMode = isset($_GET["key"]);
				if($editMode) {
					$result = query("SELECT {$GLOBALS["COLUMN_USER_EMAIL"]}, {$GLOBALS["COLUMN_USER_PHONE"]}, {$GLOBALS["COLUMN_USER_PLACE"]}, {$GLOBALS["COLUMN_USER_POSTAL_CODE"]}, {$GLOBALS["COLUMN_USER_STREET"]}, {$GLOBALS["COLUMN_USER_HOUSE_NUMBER"]}, {$GLOBALS["COLUMN_USER_FIRSTNAME"]}, {$GLOBALS["COLUMN_USER_LASTNAME"]}, {$GLOBALS["COLUMN_VOICE_ID"]} 
									 FROM {$GLOBALS["USERS_TABLE"]}
								     WHERE {$GLOBALS["COLUMN_USER_EMAIL"]} = '{$_GET["key"]}'");
					
					$result = fetch_next_row($result);
				}
				
				disconnect();
			?>
		
		
			<form action="user_administration.php" method="post">
				<div class="group_box">
					<p class="header">Allgemeines</p>
					<table>					
						<tr class="item">
							<td class="header">Vorname:</td>
							<td><input spellcheck="false" type="text" name="user_firstname" value="<?php if($editMode) echo $result[6]; ?>" /></td>
						</tr>
						<tr class="item">
							<td class="header">Nachname:</td>
							<td><input spellcheck="false" type="text" name="user_lastname" value="<?php if($editMode) echo $result[7]; ?>" /></td>
						</tr>
						<tr class="item">
							<td class="header">E-Mail:</td>
							<td><input spellcheck="false" type="text" name="user_email" value="<?php if($editMode) echo $result[0]; ?>" /></td>
						</tr>
						<tr class="item">
							<td class="header">Passwort:</td>
							<td><input spellcheck="false" type="password" name="user_password" /></td>
						</tr>
						<tr class="item">
							<td class="header">Tel.:</td>
							<td><input spellcheck="false" type="text" name="user_phone" value="<?php if($editMode) echo $result[1]; ?>"/></td>
						</tr>
					</table>
				</div>
				
				<div class="group_box">
					<p class="header">Adresse</p>
					
					<table>
						<tr class="item">
							<td class="header">Ort:</td>
							<td><input spellcheck="false" type="text" name="user_place" value="<?php if($editMode) echo $result[2]; ?>" /></td>
						</tr>
						<tr class="item">
							<td class="header">PLZ:</td>
							<td><input spellcheck="false" type="text" name="user_postal_code" value="<?php if($editMode) echo $result[3]; ?>" /></td>
						</tr>
						<tr class="item">
							<td class="header">Straße:</td>
							<td><input spellcheck="false" type="text" name="user_street" value="<?php if($editMode) echo $result[4]; ?>" /></td>
						</tr>
						<tr class="item">
							<td class="header">Hausnummer:</td>
							<td><input spellcheck="false" type="text" name="user_house_number" value="<?php if($editMode) echo $result[5]; ?>" /></td>
						</tr>
					</table>
				</div>
				
				<div class="group_box">
					<p class="header">Stimme</p>
					<table>
						<tr class="item">
							<td class="header" style="width: 67%;">Stimmgattung:</td>
							<td>
								<select class="selection_box" name="user_voice" >
									<!-- insert php code here: voices are fetched from database -->
									<?php
									
										default_connect();
										
										$result00 = query("SELECT voice_display_name
											             FROM voices;");
										
										
										$index = 1;
										while($row = fetch_next_row($result00)) {
											?>
												<option <?php if(isset($result) and $index == $result[8]) echo "selected"; ?>><?php echo $row[0] ?></option>
											<?php
											$index++;
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
			<?php			
				default_connect();
				$result = query("SELECT {$GLOBALS["COLUMN_USER_EMAIL"]}, {$GLOBALS['COLUMN_USER_FIRSTNAME']}, {$GLOBALS["COLUMN_USER_LASTNAME"]}
								 FROM {$GLOBALS['USERS_TABLE']};");
				while($row = fetch_next_row($result)) {
					?>
						<div class="group_box">
							<table>
								<tr class="item">
									<td style="width: 60%; color: rgb(150, 15, 15);" class="text"><?php echo $row[0] ?></td>
									<td class="text"><?php echo $row[1] . " " . $row[2] ?></td>
									<td><img name="<?php echo $row[0] ?>" class="deletionImage" alt="Delete" src="../../res/Red_Cross.png" width="20" /></td>
								</tr>
							</table>
						</div>
					<?php
				}
				
				disconnect();
			?>
		</div>
		
		<script>
					
			function hideAllContentContainers() {
				$(".content").hide();
			}
			
			hideAllContentContainers();
			$(".user_creation_content").show(); // default setting ; display change page for users
			
			$(".deletionImage").click(function() {
				window.location = window.location + "?key=" + $(this).attr("name");
			});
		
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