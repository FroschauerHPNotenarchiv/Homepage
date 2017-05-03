
<?php session_start(); 
	  include "templates/admin_user_administration_func.php";
	  include "templates/admin_constants.php";
?><!doctype html>

<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/main.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="css/musikstücke.css" type="text/css">

<link rel="stylesheet" href="css/infos.css" type="text/css">

<link rel="stylesheet" href="css/startseite.css" type="text/css">
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
    <article class="article">
	 <h3 class="titel_startseite">Unser Kirchenchor:</h3>
		<?php
		include "templates/music_func.php";
		include "templates/admin_user_administration_func.php";
		include "templates/google_drive_func.php";
		include "templates/admin_constants.php";
		
		$email = getUserEmail();
		$role = getUserRole($email);
			if($role <= $GLOBALS["ROLES_SUBADMIN"]) {
				include "templates/upload_music.php";
			}
		?>
		<?php 

			$client = getClient();
			$service = new Google_Service_Drive($client);

			
			if(isset($_GET['id'])) {
				$path = 'music/' . $_GET['id'] . '.mp3';
				downloadMp3($service, $_GET['id'] , $path);
				if(isset($_GET['action']) && $_GET['action'] == 'download') {
					saveMp3ToClient($path, false);
				} if(isset($_GET['action']) && $_GET['action'] == 'listen') {
					saveMp3ToClient($path, true);
				} if(isset($_GET['action']) && $_GET['action'] == 'delete') {
					if($role > $GLOBALS["ROLES_SUBADMIN"]) {
						header("Location: Musik.php");
					}
					try {
						$service->files->delete($_GET["id"]);
					} catch(Exception $e) {
						echo "Error";
					}
				}
				header("Musik.php");
				
			}

			$musicFolder = $config = json_decode(file_get_contents("scripts/drive_config.json"), true)["mp3_folder"];
			$musicFiles = retrieveAllFiles($service, "'" . $musicFolder . "' in parents");

			if(count($musicFiles) == 0) 
			{
				?> <p>Es wurde leider keine Musik gefunden.</p> <?php
			} else {
				foreach($musicFiles as $file) {
					//$service->files->delete($file->getId());
					?> 

						<div class="columns">
						  <img src="images/music.jpg" alt="" class="thumbnail"/>
						  <a href="Musik.php?id=<?php echo $file->getId()?>&action=download"><h4 class="action-link"><?php echo $file->getName()?></h4></a>
						  <?php if($role <= $GLOBALS["ROLES_SUBADMIN"]) : ?>
						  <a onclick="return confirm('Wollen Sie diese Datei wirklich entfernen?');" href="Musik.php?id=<?php echo $file->getId()?>&action=delete"><h4 class="action-link">Diese Datei entfernen</h4></a>
						  <?php endif; ?>
						  <a href="Musik.php?id=<?php echo $file->getId()?>&action=listen"><h4 class="action-link">Musikstück anhören</h4></a>
						</div>

					<?php
				}
			}


		?>
    </article>
</section>
 
  <footer class="footer">
    <div class="copyright">&copy;Lukas Knoll | Niklas Graf| Sebastian Mandl</div>
    <div class="copyright"><?php include "templates/login_button.php" ?></div>
  </footer>
</div>
</body>
</html>
