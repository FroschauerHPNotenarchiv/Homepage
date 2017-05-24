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
      <button type="button" class="btn btn-sm btn-default button_bearbeiten"><img class="icon_bearbeiten" src="images/bearbeiten.png" /></button>
    </div>

     <div class="list_elements">
       <h4 class="element_titel">Ereignis</h4>
       <img class="image_leftarticle" src="images/left_article_picture.jpg" alt="Picture">
       <p class="format_leftarticle">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
      				 Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in .</p>
     </div>

     <div class="list_elements">

			 <?php
			 		INCLUDE_ONCE "templates/news_func.php";
					require_once "templates/NewsEntry.php";

					/*
					foreach(NewsEntry::getEntryList() as $e) {
						echo $e->title;
					}
					*/

			  ?>

     </div>





    </article>
    <aside class="right_article">
    <h3>Newsflash:</h3>


	<?php if(getUserRole(getUserEmail()) <= $GLOBALS["ROLES_SUBADMIN"]) :  ?>
	<a href="templates/google_add_entry.php">Tragen Sie etwas ein ...</a>
	<?php endif;?>
    <div class="list-group" style="width:325%;">

	   <div class="calendar" id="calendar">

    <div class="list-group">
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
							$click = "onclick=\"calendarClick('" . $event->getId() . "', '" . $event->getSummary() . "', '" . str_replace("\r\n", "</br>", $event->getDescription()) . "', '" . $date . "', '" . $event->getLocation() . "')\"";
							?>
							<div class="calendar-entry" <?php echo $click ?>>

								<h4 class="list-group-item-heading" style="width: 100%;">  <?php echo $event->getSummary(); ?> </h4>
								<?php $desc = str_replace("\r\n", "</br>", $event->getDescription());
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
<footer class="footer">
    <div class="copyright">&copy;Lukas Knoll | Niklas Graf| Sebastian Mandl</div>
    <div class="copyright"><?php include "templates/login_button.php" ?></div>
  </footer>
</div>
</body>

<script type="text/javascript" src="templates/google newsfeed modal.js"></script>
<?php include "templates/admin_login.php" ?>

</html>
