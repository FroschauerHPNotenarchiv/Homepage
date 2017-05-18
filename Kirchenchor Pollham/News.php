<?php
	//@session_start();
	// Newsflash Logic:
	require_once "templates/google newsfeed func.php";
	require_once "templates/admin_user_administration_func.php";
	require_once "templates/admin_constants.php";
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
<link href="css/news_termine.css" rel="stylesheet" type="text/css">	


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>
<body>

<form action="" method="post">
			<div id="myModal" class="modal">
			

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
				<?php if(getUserRole(getUserEmail()) <= $GLOBALS["ROLES_MEMBER"]) : ?>
			<button id="deleteBtn" type="submit" value="" name="deletion">Eintrag löschen</button>
			<button id="alterBtn" type="submit" value="" name="alteration">Eintrag bearbeiten</button>
				<?php endif; ?>
					  
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
          	  <li><a href="Startseite.php">Startseite</a></li>
		  <li><a href="Mitglieder.php">Mitglieder</a></li>
		  <li><a href="News.php">News</a></li>
		  <li><a href="Musik.php">Medien</a></li>
       <?php
				if(getUserRole(getUserEmail()) <= $GLOBALS["ROLES_MEMBER"]):?>
					<li><a href="Infos.php">Infos</a></li>
				<?php endif;
				if(getUserRole(getUserEmail()) == $GLOBALS["ROLES_ADMIN"]):?>
					<li><a href="Administration.php">Admin</a></li>
				<?php endif;
			?>
      </ul>
    </nav>
  </header>
  <section>
    <h2 class="noDisplay">Main Content</h2>
    <article class="left_article">
	
	<div>
      <h3 class="titel_startseite">Unser Kirchenchor:</h3>
	  <button type="button" id="login_button" style="width: 100%; background-color: #717070; margin-right: 20px" class="btn btn-info btn-lg" data-toggle="modal" data-target="#Activities_Modal"><img class="icon_bearbeiten" src="images/bearbeiten.png" /></button>
    </div>
	
	<?php 
	
		$activitiesQuery = query("SELECT {$GLOBALS["ACTIVITIES_HEADLINE"]}, {$GLOBALS["ACTIVITIES_IMAGE"]}, {$GLOBALS["ACTIVITIES_DESCRIPTION"]}, {$GLOBALS["ACTIVITIES_DATE"]}
								  FROM {$GLOBALS["ACTIVITIES_TABLE"]}
								  ORDER BY {$GLOBALS["ACTIVITIES_DATE"]} ASC");
			
		$activity = fetch_next_row($activitiesQuery);
		if(!$activity)
			goto ending;
			
		do {?>
		
			<div class="list_elements">
				<h4 class="element_titel"><?php echo $activity[0] ?></h4>
				<img class="image_leftarticle" src="images/left_article_picture.jpg" alt="Picture">      
				<p class="format_leftarticle"><?php echo $activity[2] ?></p>
			</div>

		<?php } while($activity = fetch_next_row($activitiesQuery));
		
		goto skip_ending;
		
		ending:
			?><p>Keine kürzlichen Aktivitäten vorhanden!</p><?php
			
		skip_ending:
	
	?>
      
    </article>
    <aside class="right_article">
    <h3>Newsflash:</h3>
    <div class="list-group">
	  <div class="calendar" id="calendar">
			<?php 
			
				/* Fetching calendar events and displaying them */
					while(true) {
						foreach ($events->getItems() as $event) {
							$lol = $event->getStart()->getDateTime();
							$date = date("d.m.Y - h:i", strtotime($lol));
							$click = "onclick=\"calendarClick('" . $event->getId() . "', '" . $event->getSummary() . "', '" . $event->getDescription() . "', '" . $date . "')\"";
							?>
							<div <?php echo $click ?>>
								
								<h4>  <?php echo $event->getSummary(); ?> </h4>
								<?php $desc = $event->getDescription();
									if(strlen($desc) < 1) {
										$desc = "Keine Beschreibung";
									} else if(strlen($desc > 20)) {
										$desc = substr($desc, 0, 20);
									}
								?>
								<p><?php echo $desc ?></p>
								
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
<footer class="footer">
    <div class="copyright">&copy;Lukas Knoll | Niklas Graf| Sebastian Mandl</div>
    <div class="copyright"><?php include "templates/login_button.php" ?></div>
  </footer>
</div>
</body>

<script type="text/javascript" src="templates/google newsfeed modal.js"></script>
<?php include "templates/admin_login.php" ?>



</html>
