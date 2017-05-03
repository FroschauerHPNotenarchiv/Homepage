<?php
	include_once "admin_user_administration_func.php";
	
	echo "<script>
			$('#tag01').html('Anmeldung');
			$('#tag02').html('Name:');
			$('#tag03').html('Passwort:');
			$('#input01').attr('type', 'text');
		 </script>
	";
	
	if(isset($_GET["checkRequestPassword"])) {
		default_connect();
		$email = getUserEmail();
		$result = query("SELECT {$GLOBALS['COLUMN_REQUEST_PASSWORD']}
						 FROM {$GLOBALS["USERS_TABLE"]}
						 WHERE LOWER({$GLOBALS["COLUMN_USER_EMAIL"]}) = '{$email}'");
		$result = fetch_next_row($result);
			
		disconnect();
			
		if($result[0] == $GLOBALS["NO_REQUEST_PASSWORD"])
			echo "<script>window.location.href = window.location.href.substring(0, window.location.href.indexOf('?'));</script>";
		
	}
	
	if(isset($result[0]) && $result[0] == "{$GLOBALS["REQUEST_PASSWORD"]}") {?>
		<script>
			$('#tag01').html('Passwort Änderung');
			$('#tag02').html('Passwort:');
			$('#tag03').html('Passwort(erneut):');
			$('#input01').attr('type', "password");
			$("#request_password_resubmission_button").click();
		</script> 
		<?php
	}
	
	if(isset($_GET["logout"])) {
		unset($_SESSION[$GLOBALS["SESSION_EMAIL"]]);
		?>
			<script>
				window.location.href = window.location.href.substring(0, window.location.href.indexOf('?'));		
			</script>
		<?php
	}
	
	if(getUserRole(getUserEmail()) > $GLOBALS["ROLES_MEMBER"]) {
		?>
			<script>
				$('#login_button').show();
				$('#logout_button').hide();
			</script>
		<?php
	} else if (getUserRole(getUserEmail()) <= $GLOBALS["ROLES_MEMBER"]) {
	?>
		<script>
			$('#login_button').hide();
			$('#logout_button').show();
			$("#logout_button").click(function() {
				console.log("logout requested");
				window.location.href += "?logout";
			});
		</script>
	<?php }

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
			?>
			<script>
				console.log('signed up as: {$email}');
				window.location.href = window.location.href.substring(0, window.location.href.indexOf('?')) + '?checkRequestPassword';
			</script>
			<?php
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