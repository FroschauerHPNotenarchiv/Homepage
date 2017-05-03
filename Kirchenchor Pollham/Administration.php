<?php
	@session_start();
	include "templates/admin_constants.php";
	include "templates/admin_user_administration_func.php";
	
	default_connect();
	$userrole = getUserRole(getUserEmail());
	disconnect();
	
	if($userrole != $GLOBALS["ROLES_ADMIN"]) {
		header("Location: Startseite.php");
	}
?>
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
       <li><a href="Startseite.php">Startseite</a></li>
        <li><a href="Mitglieder.php">Mitglieder</a></li>
        <li><a href="News.php">News</a></li>
        <li><a href="Musik.php">Medien</a></li>
        <li><a href="Infos.php">Infos</a></li>
        <li><a href="Administration.php">Admin</a></li>
      </ul>
    </nav>
  </header>
 
 <section>
    <h2 class="noDisplay">Main Content</h2>
    <article class="article">
      <?php include_once "Email.php" ?>
    </article>
</section>
 
  <footer class="footer">
    <div class="copyright">&copy;Lukas Knoll | Niklas Graf| Sebastian Mandl</div>
  </footer>
</div>
</body>
</html>
