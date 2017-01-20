<?php
	// Newsflash Logic:
	require_once "templates/google newsfeed func.php";
	require_once "templates/startseite_logic.php";
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Startseite</title>

<link href="css/main.css" rel="stylesheet" type="text/css">
<link href="css/bootstrap-3.3.6.css" rel="stylesheet" type="text/css">
<link href="css/startseite.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="css/calendar-style.css">
<link rel="stylesheet" href="css/modal-style.css">	
</head>
<body>
<form action="" method="post">
			<div id="myModal" class="modal" style="">
			

		  <!-- Modal content -->
		  <div class="modal-content">
			<div class="modal-header">
			  <span class="close">×</span>
			  <h2 id="modalHeader">Headline</h2>
			</div>
			<div class="modal-body">
			  <p id="modalContent">Text loading ... </p>
			</div>
			<div class="modal-footer">
			  <h3 id="modalFooter">Modal Footer</h3>
			</div>
				<!-- if (isAdministrator) ... -->
				<?php if(isLoggedIn()) : ?>
			<button id="deleteBtn" type="submit" value="" name="deletion">Eintrag löschen</button>
			<button id="alterBtn" type="submit" value="" name="alteration">Eintrag bearbeiten</button>
				<?php endif; ?>
					  
		  </div>

		</div>
		</form>
		
		<form method="post">
		<div id="editModal" class="editbg">
	
		  <!-- Modal content -->
		  <div class="edit-content">
			<span class="editclose">&times;</span>
			<h3>STARTSEITE BEARBEITEN</h3>
			<h4>Überschrift</h4>
			<input style="width: 50%;" type="text" name="editHeader" id="editHeader"/>
			</br>
			<h4>Startseitenbild</h4>
			<input type="file" name="editImage"/>
			</br>
			<h4>Startseitentext</h4>
			<textarea id="editText" style="width: 90%; clear: both;" rows="10" cols="50" name="editText"></textarea>
			</br>
			<button style="btn btn-sm btn-default" name="action-edit" 
				type="submit">Bestätigen</button>
		  </div>
		</div>
		</form>

<div class="container">
  <header>
    <div class="primary_header">
      <h1 class="title"> <img class="placeholder" src="images/logo.png" alt="Logo"> </h1>
    </div>
    <nav class="secondary_header" id="menu">
      <ul>
        <li>Startseite</li>
        <li>Mitglieder</li>
		
        <li>News/Termine</li>
		
		<?php if(isLoggedIn()) : ?>
        <li>Benachrichtigungen</li>
		<?php endif; ?>
        <li>Administration</li>
      </ul>
    </nav>
  </header>
  <section>
    <h2 class="noDisplay">Main Content</h2>
    <article class="left_article">
    <div>
      <h3 class="titel_startseite"><?php echo $title ?></h3>
      <button id="showEdit" onclick="editClicked(<?php echo "'" . $title . "', '" . $text . "'" ?>)"
	  type="button" class="btn btn-sm btn-default button_bearbeiten"><img class="icon_bearbeiten" src="images/bearbeiten.png" /></button>
    </div>
      
      <img class="image_leftarticle" src="images/left_article_picture.jpg" alt="Picture">
      
      <p><?php echo $text ?></p>
  
      
      
    </article>
    <aside class="right_article">
    <h3>Newsflash:</h3>
	<?php if(isLoggedIn()) : ?>
	<a href="templates/google_add_entry.php">Tragen Sie etwas ein ...</a>
	<?php endif;?>
    <div class="list-group" style="width:200%;">
	
	  <div class="calendar" id="calendar">
			<?php 
			if(count($events->getItems()) < 1) {
				echo "Derzeit ist leider nichts eingetragen, sorry!";
			}
				/* Fetching calendar events and displaying them */
					while(true) {
						
						foreach ($events->getItems() as $event) {
							$lol = $event->getStart()->getDateTime();
							$date = date("d.m.Y - H:i", strtotime($lol));
							$click = "onclick=\"calendarClick('" . $event->getId() . "', '" . $event->getSummary() . "', '" . $event->getDescription() . "', '" . $date . "')\"";
							?>
							<div class="calendar-entry" <?php echo $click ?>>
								
								<h4 class="list-group-item-heading" style="width: 100%;">  <?php echo $event->getSummary(); ?> </h4>
								<?php $desc = $event->getDescription();
									if(strlen($desc) < 1) {
										$desc = "Keine Beschreibung";
									} else if(strlen($desc) > 100) {
										$desc = substr($desc, 0, 100);
										$desc = $desc . '...';
									}
								?>
								<p class="list-group-item-text"><?php echo $date ?></p>
								<p style="text-transform: none;"class="list-group-item-text"><?php echo $desc ?></p>
								
								
							</div>
							<?php
						}
				
				
					$pageToken = $events->getNextPageToken();
					if ($pageToken) {
						$optParams = array('pageToken' => $pageToken);
						$events = $service->events->listEvents($_PUBLIC_CALENDAR_ID, $optParams);
						
					} else {
						break;
					}
		}
			?>
			</div>
	  
     </div>
    </aside>
  </section>
<div class="row blockDisplay"> </div>
<footer class="secondary_header footer">
    <div class="copyright">&copy;Lukas Knoll | Niklas Graf | Sebastian Mandl</div>
    <div>
      <button type="button" class="btn btn-sm btn-default btn_login">Login</button>
    </div>
  </footer>
</div>
</body>

<script type="text/javascript" src="templates/google newsfeed modal.js"></script>
<script type="text/javascript" src="scripts/indexpage-edit.js"></script>

</html>
