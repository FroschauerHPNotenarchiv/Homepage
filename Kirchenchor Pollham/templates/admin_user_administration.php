<?php
	session_start();
	include_once 'admin_user_administration_func.php';
	
	if(isset($_GET["action"])) {
		default_connect();
		
		if($_GET["action"] === $GLOBALS["ACTION_DELETE"]) {
			$result = query("SELECT{$GLOBALS["COLUMN_PORTRAIT_PATH"]}
							 FROM {$GLOBALS["USERS_TABLE"]}
							 WHERE {$GLOBALS["COLUMN_USER_EMAIL"]} = '{$_GET["email"]}'");
			
			$result = fetch_next_row($result);
			unlink($result[0]);
			
			delete_entries($GLOBALS["USERS_TABLE"], array($GLOBALS["COLUMN_USER_EMAIL"] => $_GET["email"]));
		}
		
		if($_GET["action"] === $GLOBALS["ACTION_ALTER"]) {
			
			$ids = request_voice_and_role_id();
			
			$result = query("SELECT {$GLOBALS["COLUMN_USER_PASSWORD"]}, {$GLOBALS["COLUMN_PORTRAIT_PATH"]}, {$GLOBALS["COLUMN_USER_FIRSTNAME"]}, {$GLOBALS["COLUMN_USER_LASTNAME"]}
							 FROM {$GLOBALS["USERS_TABLE"]}
							 WHERE {$GLOBALS["COLUMN_USER_EMAIL"]} = '{$_GET[$GLOBALS["PARAM_EMAIL"]]}'");
			$result = fetch_next_row($result);
			
			$newUserPassword = $result[0];
			if($_POST["user_password"] !== "")
				$newUserPassword = hash("sha256", $_POST["user_password"]);
			
			$newPortraitPath = $result[1];
			if(strlen($_FILES["user_portrait"]['name']) > 0) {
				unlink($newPortraitPath);
				$internalPath = /*$_SERVER['DOCUMENT_ROOT'] . */"{$GLOBALS["MEMBER_PICTURE_PATH"]}/" . "{$_POST["user_lastname"]}_{$_POST["user_firstname"]}." . pathinfo($_FILES["user_portrait"]['name'], PATHINFO_EXTENSION);
				move_uploaded_file($_FILES["user_portrait"]['tmp_name'], $internalPath);
				$newPortraitPath = $internalPath;
			} else if ($result[2] != $_POST["user_firstname"] || $result[3] != $_POST["user_lastname"]) {
				$internalPath = "{$GLOBALS["MEMBER_PICTURE_PATH"]}/" . "{$_POST["user_lastname"]}_{$_POST["user_firstname"]}." . explode(".", $result[1])[3];
				rename("{$GLOBALS["MEMBER_PICTURE_PATH"]}/" . "{$result[3]}_{$result[2]}." . explode(".", $result[1])[3], 
					   $internalPath);
				$newPortraitPath = $internalPath;
			}
			
			
			update($GLOBALS["USERS_TABLE"], array($GLOBALS["COLUMN_USER_EMAIL"] => $_POST["user_email"],
												  $GLOBALS["COLUMN_USER_FIRSTNAME"] => $_POST["user_firstname"],
												  $GLOBALS["COLUMN_USER_LASTNAME"] => $_POST["user_lastname"],
											      $GLOBALS["COLUMN_USER_PASSWORD"] => $newUserPassword,
											      $GLOBALS["COLUMN_USER_PHONE"] => $_POST["user_phone"],
											      $GLOBALS["COLUMN_USER_PLACE"] => $_POST["user_place"],
												  $GLOBALS["COLUMN_USER_POSTAL_CODE"] => $_POST["user_postal_code"],
											      $GLOBALS["COLUMN_USER_STREET"] => $_POST["user_street"],
											      $GLOBALS["COLUMN_USER_HOUSE_NUMBER"] => $_POST["user_house_number"],
											      $GLOBALS["COLUMN_VOICE_ID"] => $ids[0],
											      $GLOBALS["COLUMN_ROLES_ID"] => $ids[1],
												  $GLOBALS["COLUMN_PORTRAIT_PATH"] => $newPortraitPath), 
											array($GLOBALS["COLUMN_USER_EMAIL"] => $_GET[$GLOBALS["PARAM_EMAIL"]]));
											
		}
		
		if($_GET["action"] === $GLOBALS["ACTION_CREATE"]) {
			if(isset($_POST['user_email'])) {
				// form has been submitted
				
				default_connect();
				$ids = request_voice_and_role_id();
				
				$internalPath = /* $_SERVER['DOCUMENT_ROOT'] . */ "{$GLOBALS["MEMBER_PICTURE_PATH"]}/" . "{$_POST["user_lastname"]}_{$_POST["user_firstname"]}." . pathinfo($_FILES["user_portrait"]['name'], PATHINFO_EXTENSION);
				
				insert($GLOBALS["USERS_TABLE"], array($GLOBALS["COLUMN_USER_EMAIL"] => $_POST["user_email"],
													  $GLOBALS["COLUMN_USER_FIRSTNAME"] => $_POST["user_firstname"],
													  $GLOBALS["COLUMN_USER_LASTNAME"] => $_POST["user_lastname"],
													  $GLOBALS["COLUMN_USER_PASSWORD"] => hash("sha256", $_POST["user_password"]),
													  $GLOBALS["COLUMN_USER_PHONE"] => $_POST["user_phone"],
													  $GLOBALS["COLUMN_USER_PLACE"] => $_POST["user_place"],
													  $GLOBALS["COLUMN_USER_POSTAL_CODE"] => $_POST["user_postal_code"],
													  $GLOBALS["COLUMN_USER_STREET"] => $_POST["user_street"],
													  $GLOBALS["COLUMN_USER_HOUSE_NUMBER"] => $_POST["user_house_number"],
													  $GLOBALS["COLUMN_VOICE_ID"] => $ids[0],
													  $GLOBALS["COLUMN_ROLES_ID"] => $ids[1],
													  $GLOBALS["COLUMN_PORTRAIT_PATH"] => $internalPath));
				$_SESSION[$GLOBALS["CURRENT_USER"]] = $_POST["user_email"];
				
				move_uploaded_file($_FILES["user_portrait"]['tmp_name'], $internalPath);
				
				disconnect();
			}
		}
		
		$_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?'));
		header("Location: {$_SERVER['REQUEST_URI']}");
		
		disconnect();
	}
	
?>

<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="../css/admin_user_administration.css" />	
	</head>
	<body>

		<script src="../scripts/jquery-3.1.1.min.js"></script>
	
		<ul>
			<li class="menu_item user_creation" >Benutzer Anlegen</li>
			<li class="menu_item user_alteration " >Benutzer Bearbeiten</li>
		</ul>
		
		<div class="content user_creation_content" >
			<?php
				default_connect();
				
				$editMode = isset($_GET[$GLOBALS["PARAM_EMAIL"]]);
				if($editMode) {
					$result = query("SELECT {$GLOBALS["COLUMN_USER_EMAIL"]}, {$GLOBALS["COLUMN_USER_PHONE"]}, {$GLOBALS["COLUMN_USER_PLACE"]}, {$GLOBALS["COLUMN_USER_POSTAL_CODE"]}, {$GLOBALS["COLUMN_USER_STREET"]}, {$GLOBALS["COLUMN_USER_HOUSE_NUMBER"]}, {$GLOBALS["COLUMN_USER_FIRSTNAME"]}, {$GLOBALS["COLUMN_USER_LASTNAME"]}, {$GLOBALS["COLUMN_VOICE_ID"]}, {$GLOBALS["COLUMN_ROLES_ID"]}, {$GLOBALS["COLUMN_PORTRAIT_PATH"]}
									 FROM {$GLOBALS["USERS_TABLE"]}
									 WHERE {$GLOBALS["COLUMN_USER_EMAIL"]} = '{$_GET["email"]}'");
					
					$result = fetch_next_row($result);
				}
				
				disconnect();
			?>
		
		
			<form action="admin_user_administration.php<?php if($editMode) echo "?action={$GLOBALS['ACTION_ALTER']}&{$GLOBALS['PARAM_EMAIL']}={$_GET[$GLOBALS['PARAM_EMAIL']]}"; else echo "?action={$GLOBALS['ACTION_CREATE']}" ?>" method="post" enctype="multipart/form-data">
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
							<td><input spellcheck="false" type="number" name="user_postal_code" value="<?php if($editMode) echo $result[3]; ?>" /></td>
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
					
				<div class="group_box" id="portrait_group_box" >
					<p class="header">Portrait</p>
					<table>
						<tr class="item">
						<div>
							<td class="header" style="width: 75%; text-align: center">Bild wählen</td>
							<td><p class="user_portrait"><input id="user_portrait_input" style="position: absolute; opacity: 0; width: 100%;border: 1px solid blue" type="file" name="user_portrait" /></p></td>
						</div>
						<td><img style="width: 200px" alt="Kein Bild" src="<?php if($editMode) echo $result[10] ?>" /></td>
						</tr>
					</table>
				</div>
				
				<div class="group_box">
					<p class="header">Stimme</p>
					<table>
						<tr class="item">
							<td class="header" style="width: 25%;">Stimmgattung:</td>
							<td>
								<select class="selection_box" name="user_voice" >
									<!-- insert php code here: voices are fetched from database -->
									<?php
									
										default_connect();
										
										$result00 = query("SELECT {$GLOBALS["COLUMN_VOICES_DISPLAY_NAME"]}
											               FROM {$GLOBALS["VOICES_TABLE"]};");
										
										
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
				
				<div class="group_box">
					<p class="header">Administratives</p>
					<table>
						<tr class="item">
							<td class="header" style="width: 25%;">Rolle:</td>
							<td>
								<select class="selection_box" name="user_role" >
									<!-- insert php code here: voices are fetched from database -->
									<?php
									
										default_connect();
										
										$result00 = query("SELECT {$GLOBALS["COLUMN_ROLES_DISPLAY_NAME"]}
											               FROM {$GLOBALS["ROLES_TABLE"]}
														   ORDER BY {$GLOBALS["COLUMN_ROLES_ID"]} DESC;");
										
										
										$index = 3; // can be hardcoded due to never changing user roles
										while($row = fetch_next_row($result00)) {
											?>
												<option <?php if(isset($result) and $index == $result[9]) echo "selected"; ?>><?php echo $row[0] ?></option>
											<?php
											$index--;
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
								 FROM {$GLOBALS['USERS_TABLE']}
								 ORDER BY {$GLOBALS["COLUMN_USER_LASTNAME"]}, {$GLOBALS['COLUMN_USER_FIRSTNAME']}, {$GLOBALS["COLUMN_USER_EMAIL"]};");
				while($row = fetch_next_row($result)) {
					?>
						<div class="group_box">
							<table class="hoverable">
								<tr name="<?php echo $row[0] ?>"  class="item">
									<td style="width: 60%; color: rgb(150, 15, 15);" class="text clickable_item"><?php echo $row[0] ?></td>
									<td class="text clickable_item"><?php echo $row[1] . " " . $row[2] ?></td>
									<td><img class="deletion_image" alt="Delete" src="../images/red cross.png" width="20" /></td>
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
			
			function redirectByItemAttribute(item, additionalAttributes = "") {
				var location = window.location.href;
				if(hrefContainsKey)
					location = location.substr(0, location.indexOf("?"));
				window.location.href = location + "?<?php echo $GLOBALS["PARAM_EMAIL"] ?>=" + item.attr("name") + additionalAttributes;
			}
			
			hideAllContentContainers();
			
			var hrefContainsKey = window.location.href.match('.*\\?<?php echo $GLOBALS["PARAM_EMAIL"] ?>.*');
			
			if(hrefContainsKey) {
				$(".user_creation_content").show();
			} else {
				$(".user_alteration_content").show(); // default setting ; display customization page for users
			}
			
			$(".clickable_item").click(function() {
				redirectByItemAttribute($(this).parent());
			});
			
			$(".deletion_image").dblclick(function() {
				redirectByItemAttribute($(this).parent().parent(), "&action=<?php echo $GLOBALS["ACTION_DELETE"] ?>");
			});
		
			$(".user_creation").click(function() {
				hideAllContentContainers();
				$(".user_creation_content").show();
			});
			
			$(".user_alteration").click(function() {
				hideAllContentContainers();
				$(".user_alteration_content").show();
			});
			
			setInterval(function() {
				/*var contentWidth = $(".content").width();
				if($("#user_alteration_content").css("display") !== "none")
					contentWidth /= 2;
				
				if(contentWidth <= 300)
					$(".menu_item").css("width", contentWidth + "px");
				else
					if($("#user_alteration_content").css("display") !== "none")
						$(".menu_item").css("width", contentWidth / 1.67 + "px");
					else
						$(".menu_item").css("width", contentWidth / 2 + "px");*/
				
				var groupBoxWidthPortrait = parseInt($("#portrait_group_box").css("width"));
				$("#user_portrait_input").css("width", groupBoxWidthPortrait);
				$("#user_portrait_input").css("left", parseInt($("#portrait_group_box").position().left) + "px");
				$("#user_portrait_input").css("top", parseInt($("#portrait_group_box").position().top) + 25 + "px");
				$("#user_portrait_input").css("height", parseInt($("#portrait_group_box").css("height")) - 25 + "px");
				$("#user_portrait_input").css("width", parseInt($("#portrait_group_box").css("width")) - 10 + "px");
			}, 30);
		
		</script>
	</body>

</html>