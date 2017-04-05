<!doctype html>
<<<<<<< HEAD
<html>
	<head>
		<title>Email2You</title>
	</head>
	<body>
		<!-- bool mail ( string $to , string $subject , string $message [, string $additional_headers [, string $additional_parameters ]] ) -->
		
		<?php
			if(mail("sebastian-mandl@gmx.net", "PHP-Mail-Test", "<p>Hallo</p><hr />"))
				print "sent";
			else
				print "sorry! Didn't work!";
		?>
=======
<?php
	session_start();

	
	require_once "templates/admin_user_administration_func.php";
	require_once "templates/mailing/class.pop3.php";
	require_once "templates/mailing/class.smtp.php";
	require_once "templates/mailing/class.phpmailer.php";
?>
<html>
	<head>
		<title>Mailbox</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	</head>
	<body>
	
	<h2 id="popup" style="display:none; width: 400px; margin: 50px auto;color: rgb(40, 180, 40)"></h2>
	<?php
		if(isset($_POST["email-host"]) && isset($_POST[$GLOBALS["PARAM_PASSWORD"]])) {
			$_SESSION["email-host"] = $_POST["email-host"];
			$_SESSION[$GLOBALS["PARAM_PASSWORD"]] = $_POST[$GLOBALS["PARAM_PASSWORD"]];
			$_POST = array();
		}
		
		if(isset($_POST["recipient"]) && isset($_POST["msg"]) && isset($_POST["subject"]) && isset($_SESSION[$GLOBALS["PARAM_PASSWORD"]])) {
			try {
			
			$mail = new PHPMailer;
			
			if($_POST["recipient"] == "all") {
				$result = query("SELECT {$GLOBALS["COLUMN_USER_EMAIL"]}
							 FROM {$GLOBALS["USERS_TABLE"]};");
				$recipients = "";
				while($row = fetch_next_row($result)) {
					$mail->AddAddress($row[0]);
				}
			} else {
				$mail->AddAddress($_POST["recipient"]); 
			}

			// smtp.gmail.com, smtp.a1.net, smtp.live.com, smtp.web.de, smtp.mail.yahoo.com
			
			// int preg_match ( string $pattern , string $subject [, array &$matches [, int $flags = 0 [, int $offset = 0 ]]] )

			// Host: Specify main and backup server
			if(preg_match("#.+\\@gmail\\.com#", $_SESSION["email-host"]))
				$mail->Host = "smtp.gmail.com";
			else if(preg_match("#.+\\@a1\\.net#", $_SESSION["email-host"]))
				$mail->Host = "smtp.a1.net";
			else if(preg_match("#.+\\@live\\.com#", $_SESSION["email-host"]))
				$mail->Host = "smtp.live.com";
			else if(preg_match("#.+\\@web\\.de#", $_SESSION["email-host"]))
				$mail->Host = "smtp.web.de";
			else if(preg_match("#.+\\@yahoo\\.com#", $_SESSION["email-host"]))
				$mail->Host = "smtp.mail.yahoo.com";
			else if(preg_match("#.+\\@gmx\\.net#", $_SESSION["email-host"]))
				$mail->Host = "mail.gmx.net";
			
			$mail->IsSMTP();                                      // Set mailer to use SMTP
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = $_SESSION["email-host"];            // SMTP username
			$mail->Password = $_SESSION[$GLOBALS["PARAM_PASSWORD"]];                 // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted

			$mail->From = $_SESSION["email-host"];
			$mail->FromName = 'Sebastian Mandl';

			$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
			$mail->IsHTML(true);                                  // Set email format to HTML

			$mail->Subject = "Kirchenchor Pollham: " . $_POST["subject"];
			$mail->Body    = "<!doctype html><html>" . str_replace("\r\n", "<br />", $_POST["msg"]) . "</html>";
			//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

			if($mail->Send()) {
				
				?>
					<script>
						$("#popup").html("Email wurde versant!");
						$("#popup").show();
						$("#popup").fadeIn(2500, function() {
							setTimeout(function() {
								$("#popup").fadeOut(2500);
							}, 5000);
						});
						console.log("success");
					</script>
				<?php
			} else {
				?>
					<script>
						$("#popup").html("Email konnte nicht versendet werden!");
						$("#popup").css("color", "rgb(180, 40, 40)");
						$("#popup").show();
						$("#popup").fadeIn(2500, function() {
							setTimeout(function() {
								$("#popup").fadeOut(2500);
							}, 5000);
						});
						console.log("failure");
					</script>
				<?php
			}
			
			} catch(Exception $e) {
				echo $e->errorMessage();
			}
		}
	
	?>
	
	<?php
		if(!isset($_SESSION[$GLOBALS["PARAM_PASSWORD"]])):?>
			<form action="" method="post">
				<p>Email-Konto: <select name="email-host" required><?php
				$result = query("SELECT {$GLOBALS["COLUMN_USER_FIRSTNAME"]}, {$GLOBALS["COLUMN_USER_LASTNAME"]}, {$GLOBALS["COLUMN_USER_EMAIL"]}
							     FROM {$GLOBALS["USERS_TABLE"]};");
								 
				while($row = fetch_next_row($result)):?>
				
					<option value="<?php echo $row[2] ?>">
						<?php echo $row[0] . " " . $row[1] ?>
					</option>
					
				<?php endwhile;?></select></p>
				
				<p>Email-Passwort: <input required type="password" name="<?php echo $GLOBALS["PARAM_PASSWORD"] ?>" /></p>
				<button type="submit">Bestätigen</button>
			</form>
		<?php else:?>
		
	<form action="" method="post">
		<h2>Nachricht verfassen</h2>
		
		<p>Empfänger: <select name="recipient" required>
			<option selected>
				<!-- default select is void -->
			</option>
			<option value="all">
				Alle
			</option>
		<?php
			$result = query("SELECT {$GLOBALS["COLUMN_USER_FIRSTNAME"]}, {$GLOBALS["COLUMN_USER_LASTNAME"]}, {$GLOBALS["COLUMN_USER_EMAIL"]}
							 FROM {$GLOBALS["USERS_TABLE"]};");
			while($row = fetch_next_row($result)):?>
				
				<option value="<?php echo $row[2] ?>">
					<?php echo $row[0] . " " . $row[1] ?>
				</option>
				
			<?php endwhile;
		?>
		</select></p>
		
		<p>Betreff: <input type="text" name="subject" required /></p>
		<p>Nachricht:</p>
		<textarea rows="15" cols="65" name="msg" required></textarea>
		<br />
		<button type="submit">Senden</button>
	</form>
	
	<?php endif; ?>
	
	<hr />
		
	<h2>Benachrichtigungen</h2>
	<button type="button" onclick="window.location.reload()">Aktualisieren</button> <!--  unset post -->
	<br />
		
	<?php
	
	if(!isset($_SESSION[$GLOBALS["PARAM_PASSWORD"]]))
		goto ending;
	
	$handle = imap_open("{imap.gmx.net/imap2/ssl}INBOX", "sebastian-mandl@gmx.net", $_SESSION[$GLOBALS["PARAM_PASSWORD"]]);

	$msgbox = imap_check($handle);

	// NMSGS = number of messages available :{$msgbox->Nmsgs}

	// object imap_headerinfo ( resource $imap_stream , int $msg_number [, int $fromlength = 0 [, int $subjectlength = 0 [, string $defaulthost = NULL ]]] )
	foreach (imap_fetch_overview($handle, "1:{$msgbox->Nmsgs}") as $overview):
		if(!preg_match("@^Kirchenchor Pollham: @", $overview->subject))
			continue;
		?>
			<h3><?php
				$subject = $overview->subject;
				if(empty($subject))
					echo "Kein Betreff";
				else
					echo imap_utf8($subject);
			?></h3>
			<p><?php 
				$header = imap_headerinfo($handle, $overview->msgno);
				echo imap_utf8($overview->from ). " [" .  imap_utf8($header->from[0]->mailbox) . "@" . imap_utf8($header->from[0]->host) . "]";
			?></p>
			<p><?php echo imap_utf8(imap_body($handle, $overview->msgno)) ?></p>
			<hr />
		<?php
	endforeach;?>
	
	<?php imap_close($handle); ending: ?>

>>>>>>> b2082c116368a810923e4e87d8cb2650f2743565
	</body>
</html>