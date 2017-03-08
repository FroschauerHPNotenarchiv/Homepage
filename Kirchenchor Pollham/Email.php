<!doctype html>
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
	</body>
</html>