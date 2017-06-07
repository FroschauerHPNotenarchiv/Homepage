<?php
	//@session_start();
	// Newsflash Logic:
	require_once "templates/google newsfeed func.php";
	require_once "templates/admin_user_administration_func.php";
	require_once "templates/admin_constants.php";
	INCLUDE_ONCE "templates/news_func.php";
	require_once "templates/NewsEntry.php";

	if(getUserRole(getUserEmail()) <= $GLOBALS["ROLES_SUBADMIN"]) {
		if(isset($_GET["id"]) && isset($_GET["action"])) {
			if($_GET["action"] == "delete") {
				$event = NewsEntry::getEntry($_GET["id"]);
				if($event != null) {
					$event->delete();
					if(file_exists($event->imagePath)) {
						unlink($event->imagePath);
					}
					header("Refresh:0; url=News.php");
				}
			}
		}

		if(isset($_POST["editEvent"])) {
			$event = NewsEntry::getEntry($_POST["inId"]);
			if($event != null) {
				$event->title = $_POST["inTitle"];
				if(!empty($_FILES["inFile"]["tmp_name"])) {
					if(file_exists($event->imagePath)) {
						unlink($event->imagePath);
					}
					$event->imagePath = handle_file_upload();
				}
				$event->description = $_POST["inDescription"];
				$event->edit();

			}
		}

		if(isset($_POST["newEvent"])) {
			$event = new NewsEntry();
			$event->title = $_POST["inTitle"];
			$event->imagePath = handle_file_upload();
			$event->description = $_POST["inDescription"];
			$event->save();
		}
	}


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
<link href="css/news_termine.css" rel="stylesheet"> <!-- type="text/css" -->


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
			<?php if(getUserRole(getUserEmail()) <= $GLOBALS["ROLES_SUBADMIN"]) : ?>
      <button onclick="onNew()" type="button" class="btn btn-sm btn-default button_bearbeiten"><img class="icon_bearbeiten" src="images/bearbeiten.png" /></button>
		<?php endif; ?>
    </div>


		<?php if(getUserRole(getUserEmail()) <= $GLOBALS["ROLES_SUBADMIN"]) : ?>
		 	<form action="" method="post" enctype="multipart/form-data">
		 		<div id="eventForm" style="display: none; clear: both">
		 			<h1 id="heading">Neuer Eintrag</h1>
					<p>Titel: <input type="text" id="title" name="inTitle" required/></p>
					<p>Bild: <input type="file" name="inFile"/></p>
					<input type="text" id="desc" name="inDescription"></input>
					<input type="text" id="hiddenId" style="display: none" name="inId"/>
					<button id="btnNew" type="submit" name="newEvent">Erstellen</button>
		 		</div>
		 	</form>
			<br/>
			<br/>
		<?php endif; ?>
     <div class="list_elements">

			 <?php
					foreach(NewsEntry::getEntryList() as $entry) {
					foreach(array_reverse(NewsEntry::getEntryList()) as $entry) {
						?>
						<h4 class="element_titel"><?php echo $entry->title?></h4>
					<?php if(getUserRole(getUserEmail()) <= $GLOBALS["ROLES_SUBADMIN"]) : ?>
						<a href="#eventForm" onclick="onEdit('<?php echo $entry->title?>','<?php echo $entry->description?>','<?php echo $entry->id?>')"><img class="editbtn" src="images/bearbeiten.png"></a>
						<a onclick="return confirm('Wollen Sie dieses Event wirklich entfernen?')" href="News.php?id=<?php echo $entry->id?>&action=delete"><img class="editbtn" src="images/red cross.png"></a>
					<?php endif; ?>
					<?php if($entry->imagePath != null) : ?>
						<img class="image_leftarticle" src="<?php echo $entry->imagePath?>" alt="Picture">
					<?php endif; ?>
						<p class="format_leftarticle"><?php echo $entry->description?></p>

						<?php
					}

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

<script>
	function onEdit(title, description, id) {
		document.getElementById("eventForm").style.display = "inline";
		document.getElementById("hiddenId").value = id;
		document.getElementById("title").value = title;
		document.getElementById("desc").value = description;
		document.getElementById("heading").innerHTML = "Event bearbeiten";
		document.getElementById("btnNew").name = "editEvent";
		document.getElementById("btnNew").innerHTML = "Bearbeiten";
	}

	function onNew() {
		var display = document.getElementById("eventForm").style.display;
		if(display === "none") {
			document.getElementById("eventForm").style.display = "inline";
			document.getElementById("heading").innerHTML = "Neuer Eintrag";
			document.getElementById("btnNew").name = "newEvent";
			document.getElementById("btnNew").innerHTML = "Erstellen";
			document.getElementById("title").value = "";
			document.getElementById("desc").value = "";
		} else {
			document.getElementById("eventForm").style.display = "none";
		}
	}
</script>
</html>
