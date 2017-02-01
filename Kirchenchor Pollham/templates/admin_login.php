<?php
	include_once "admin_user_administration_func.php";

	if(isset($_POST[$GLOBALS["PARAM_EMAIL"]])) {
		$email = $_POST[$GLOBALS["PARAM_EMAIL"]];
		$password = hash("sha256", $_POST[$GLOBALS["PARAM_PASSWORD"]]);
		
		default_connect();
		
		$result = query("SELECT COUNT(*)
					     FROM {$GLOBALS["USERS_TABLE"]}
					     WHERE {$GLOBALS["COLUMN_USER_EMAIL"]} = '{$email}' AND
							{$GLOBALS["COLUMN_USER_PASSWORD"]} = '{$password}'");
		$result = fetch_next_row($result);
		if($result[0] == 1) { // input data coincides with user data
			$_SESSION[$GLOBALS["SESSION_EMAIL"]] = $email;
			echo "<script>console.log('user logged in successfully');</script>";
		} else {
			?>
			<script>
				$('.upload-info').html('Anmeldeinformationen sind falsch');
				$('.upload-info').css("color", "red");
				$('.upload-info').css("font-size", "2em");
				$('.upload-info').css("text-align", "center");
				$('.upload-info').show();
				
				setTimeout(function() {
					$('.upload-info').fadeOut(1000);
				}, 2000);
				
				console.log('Check user credentials! Verify your input data! check for spelling mistakes! For further information on spelling look into a dictionary!');
			</script>
			<?php
		}
		
		disconnect();
	}
?>