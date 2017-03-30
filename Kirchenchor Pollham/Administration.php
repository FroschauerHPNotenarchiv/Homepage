<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/main.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="css/administration.css" type="text/css">
<link href="css/bootstrap-3.3.6.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">
  <header class="header_layout">
    <div class="primary_header">
     <h1 class="title"> <img class="placeholder" src="images/logo.png" alt="Logo"> </h1>
    </div>
    <nav class="secondary_header" id="menu">
      <ul>
       <li><a href="Startseite.html">Startseite</a></li>
        <li><a href="Mitglieder.html">Mitglieder</a></li>
        <li><a href="News.html">News/Termine</a></li>
        <li><a href="Infos.html">Infos</a></li>
        <li><a href="Administration.html">Administration</a></li>
      </ul>
    </nav>
  </header>
 
 <section>
    <h2 class="noDisplay">Main Content</h2>
    <article class="article">
      
	  <!-- ---------------------------------------E-Mail--------------------------------------- -->
	  
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

			$mail->IsSMTP();                                      // Set mailer to use SMTP
			$mail->Host = "mail.gmx.net";						  // Specify main and backup server
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = getUserEmail();          			  // SMTP username
			$mail->Password = $_SESSION[$GLOBALS["PARAM_PASSWORD"]];                 // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted

			$mail->From = getUserEmail();
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
		}
	
	?>
	
	<?php
		/*if(!isset($_SESSION[$GLOBALS["PARAM_PASSWORD"]])):?>
			<form action="" method="post">
				<p>Email-Konto: <select name="email-host" required><?php
				$result = array();
				while($row = fetch_next_row($result)):?>
				
					<option value="<?php echo $row[2] ?>">
						<?php echo $row[0] . " " . $row[1] ?>
					</option>
					
				<?php endwhile;?></select></p>
				
				<p>Email-Passwort: <input required type="password" name="<?php echo $GLOBALS["PARAM_PASSWORD"] ?>" /></p>
				<button type="submit">Bestätigen</button>
			</form>
		<?php else:*/?>
		
	<form action="" method="post">
     <h3 class="titel_info">Nachricht verfassen:</h3> 
     
     <div class="email_layout">
		<p>Empfänger: <select name="recipient" required>
			<option selected>
				<!-- default select is void -->
			</option>
			<option value="all">
				Alle
			</option>
		<?php
			$result = array();
			while($row = fetch_next_row($result)):?>
				
				<option value="<?php echo $row[2] ?>">
					<?php echo $row[0] . " " . $row[1] ?>
				</option>
				
			<?php endwhile;
		?>
		</select></p>
		
		<p>Betreff: <input type="text" name="subject" required /></p>
		<p>Nachricht:</p>
        <textarea class="email" rows="15"cols="38" name="msg" required></textarea>
		<!--    <textarea class="email" rows="15" cols="65" name="msg" required></textarea>     -->
		<br />
		<button type="submit">Senden</button>
	</form>
	
	<?php //endif; ?>
	
	<hr />
		
	<h3 class="titel_info">Eingegangene Nachrichten:</h3> 
	<button class="email_aktualisieren" type="button" onclick="window.location.reload()">Aktualisieren</button> <!--  unset post -->
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
    </div>
    </div>
	  <!-- ---------------------------------------Ende E-Mail--------------------------------------- -->	  
	  

      
    </article>
</section>
 
  <footer class="footer">
    <div class="copyright">&copy;Lukas Knoll | Niklas Graf | Sebastian Mandl</div>
  </footer>
</div>
</body>
</html>
