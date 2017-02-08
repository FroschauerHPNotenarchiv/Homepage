<!doctype html>

<?php
	session_start();
	require_once "templates/admin_user_administration_func.php";
	require_once "templates/admin_user_administration.php";
?>

<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Startseite</title>

<link href="css/bootstrap-3.3.6.css" rel="stylesheet" type="text/css">
<link href="css/main.css" rel="stylesheet" type="text/css">
<link href="css/startseite.css" rel="stylesheet" type="text/css">
<link href="css/mitglieder.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="css/admin_user_administration.css" />	

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<style>
	.columns {
		padding: 5px;
	}
	
	.modal-content {
		width: 60%;
		height: 0px;
	}
	
	.modal-dialog {
		width: 75%;
	}
</style>

</head>
<body>
	<div class="container">
	  <header>
		<div class="primary_header">
		  <h1 class="title"> <img class="placeholder" src="images/logo.png" alt="Logo"> </h1>
		</div>
		<nav class="secondary_header" id="menu">
		  <ul>
		   <li><a href="Startseite.php">Startseite</a></li>
			<li><a href="Mitglieder.php">Mitglieder</a></li>
			<li><a href="News.html">News/Termine</a></li>
			<li><a href="Benachrichtigungen.html">Benachrichtigungen</a></li>
			<li><a href="Administration.html">Administration</a></li>
		  </ul>
		</nav>
	  </header>
  <section>
    <h2 class="noDisplay">Main Content</h2>
    <article class="left_article left_article_mitglieder">
    <div>
      <h3 class="titel_startseite">Unser Kirchenchor:</h3>
	<?php 
		default_connect();
		if(getUserRole(getUserEmail()) == $GLOBALS["ROLES_ADMIN"]): ?>
		<button type="button" id="edit_button" class="btn btn-sm btn-default button_bearbeiten"><img class="icon_bearbeiten" src="images/bearbeiten.png" /></button>
    <?php endif; 
		disconnect();?>
	</div>
  
  <div class="row">
		<!-- php stuff insertion of members------------------------------------- -->
	<?php
		default_connect();
		
		$result = query("SELECT {$GLOBALS["COLUMN_USER_FIRSTNAME"]}, {$GLOBALS["COLUMN_USER_LASTNAME"]}, {$GLOBALS["COLUMN_VOICES_DISPLAY_NAME"]}, {$GLOBALS["COLUMN_PORTRAIT_PATH"]}, {$GLOBALS["COLUMN_USER_EMAIL"]}
						 FROM {$GLOBALS["USERS_TABLE"]} users
						 INNER JOIN {$GLOBALS["VOICES_TABLE"]} voices ON (users.{$GLOBALS["COLUMN_VOICES_ID"]} = voices.{$GLOBALS["COLUMN_VOICES_ID"]})");
		
		$row = null;
		while(($row = fetch_next_row($result))) {
			?>
				<div class="columns" value="<?php echo $row[4] ?>"> <!-- data-toggle="modal" data-target="#MemberModal" -->
			      <img src="<?php echo $row[3] ?>" alt="" class="thumbnail"/>
				  <h4><?php echo "{$row[0]} {$row[1]}" ?></h4>
				  <h5 class="mitglieder_infos">Stimme: <?php echo $row[2] ?></h5>
				  <h5 class="mitglieder_infos">Info: Mitglied seit 1.1.2017 </h5>
				</div>
			<?php
		}
		
		disconnect();
	?>
	
  </div>
  
    </article>
	</section>
	<div class="row blockDisplay"> </div>
		<footer class="secondary_header footer">
			<div class="copyright">&copy;Lukas Knoll | Niklas Graf | Sebastian Mandl</div>
		<div>
			<button type="button" class="btn btn-sm btn-default btn_login">Login</button>
		</div>
	  </footer>
	</div>
	
	<script type="text/javascript">
		<?php
			default_connect();
		?>
		if(window.location.href.includes("<?php echo $GLOBALS["PARAM_EMAIL"] ?>") && <?php echo getUserRole(getUserEmail()) ?> == <?php echo $GLOBALS["ROLES_ADMIN"] ?>)
			$("#MemberModal").modal("show");
		
		<?php
			disconnect();
		?>

		$("#edit_button").click(function() {
			$(".columns").hover(function() {
				$(this).css("background-color", "rgba(150, 15, 15, 0.1)");
			}, function() {
				$(this).css("background-color", "white");
			});
			$(".columns").click(function() {		
				window.location.href += "?<?php echo $GLOBALS["PARAM_EMAIL"] ?>=" + $(this).attr("value");		
			});
		});
	</script>
</body>
</html>
