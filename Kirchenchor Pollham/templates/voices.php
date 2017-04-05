<html>

<head>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<?php include_once "admin_user_administration_func.php" ?>

</head>

<body>

<?php
	
<<<<<<< HEAD
	$actionTaken = false;
	if(isset($_GET["update"]) && isset($_GET["name"])) {
		update($GLOBALS["VOICES_TABLE"], array($GLOBALS["COLUMN_VOICES_DISPLAY_NAME"] => $_GET["name"]), array($GLOBALS["COLUMN_VOICES_ID"] => $_GET["update"]));
		$actionTaken = true;
	} else if(isset($_GET["delete"])) {
		delete_entries($GLOBALS["VOICES_TABLE"], array($GLOBALS["COLUMN_VOICES_ID"] => $_GET["delete"]));
		$actionTaken = true;
	} else if(isset($_GET["name"])) {
		$result = query("SELECT MAX({$GLOBALS["COLUMN_VOICES_ID"]})
			             FROM {$GLOBALS["VOICES_TABLE"]}");
		$result = fetch_next_row($result);
		insert($GLOBALS["VOICES_TABLE"], array($GLOBALS["COLUMN_VOICES_ID"] => ($result[0] + 1), $GLOBALS["COLUMN_VOICES_DISPLAY_NAME"] => $_GET["name"]));
		$actionTaken = true;
	}
	
	if($actionTaken) {
		header("Location: " . substr($_SERVER["REQUEST_URI"], 0, strpos($_SERVER["REQUEST_URI"], "?")));
=======
	if(isset($_GET["update"]) && isset($_GET["name"])) {
		
	} else if(isset($_GET["delete"])) {
		
>>>>>>> b2082c116368a810923e4e87d8cb2650f2743565
	}

?>

<div>
	<p>Stimmgattungen</p>
	<ul>
		<li id="alter_button" style="border: 1px solid black;padding:2px">Bearbeitung</li>
		<li id="new_button" style="border: 1px solid black;padding:2px">Neu</li>
	</ul>
</div>

<div>
	<div id="tab_alter">
		<?php
			$result = query("SELECT {$GLOBALS["COLUMN_VOICES_DISPLAY_NAME"]}, {$GLOBALS["COLUMN_VOICES_ID"]}
<<<<<<< HEAD
							 FROM {$GLOBALS["VOICES_TABLE"]}
							 ORDER BY {$GLOBALS["COLUMN_VOICES_DISPLAY_NAME"]} ASC");
=======
							 FROM {$GLOBALS["VOICES_TABLE"]}");
>>>>>>> b2082c116368a810923e4e87d8cb2650f2743565
			
			while($row = fetch_next_row($result)):?>
				<div value="<?php echo $row[1] ?>">
					<p style="margin: 1px;padding 2px; width: 100px;float:left" contentEditable="true" class="item"><?php echo $row[0]; ?></p>
					<img class="deleteImg" src="../images/red cross.png" width="20" alt="delete" />
					<br />
				</div>
			<?php endwhile;
		?>
	</div>
	
	<div id="tab_create">
<<<<<<< HEAD
		<form action="" method="get">
			<input type="text" name="name" placeholder="Name" />
			<button>Anlegen</button>
		</form>
=======
		<br />
>>>>>>> b2082c116368a810923e4e87d8cb2650f2743565
	</div>
</div>

<script type="text/javascript">
	function vanishAllContainers() {
		$("#tab_alter").hide();
		$("#tab_create").hide();
	}
	
<<<<<<< HEAD
=======
	function toggleContainers() {
		if($("#tab_alter").is(':visible')) {
			$("#tab_alter").hide();
			$("#tab_create").show();
		} else {
			$("#tab_alter").show();
			$("#tab_create").hide();
		}
	}
	
>>>>>>> b2082c116368a810923e4e87d8cb2650f2743565
	vanishAllContainers();
	$("#tab_alter").show();
	
	$("#alter_button").click(function() {
<<<<<<< HEAD
		$("#tab_alter").show();
		$("#tab_create").hide();
	});
	
	$("#new_button").click(function() {
		$("#tab_alter").hide();
		$("#tab_create").show();
	});
	
	$(".item").keydown(function(event) {
=======
		toggleContainers();
	});
	
	$("#new_button").click(function() {
		toggleContainers();
	});
	
	$(".item").keydown(function(event) {
		
>>>>>>> b2082c116368a810923e4e87d8cb2650f2743565
		console.log(event);
		
		if(event.keyCode != 13)
			return;
		
		var id = $(this).parent().attr("value");
		var html = $(this).html();
		var location = window.location.href.substr(window.location.href, 0, window.location.href.indexOf('?'));
		window.location.href = location + "?update=" + id + "&name=" + html;
	});
	
<<<<<<< HEAD
	$(".deleteImg").dblclick(function() {
=======
	$(".deleteImg").click(function() {
>>>>>>> b2082c116368a810923e4e87d8cb2650f2743565
		var id = $(this).parent().attr("value");
		var location = window.location.href.substr(window.location.href, 0, window.location.href.indexOf('?'));
		window.location.href = location + "?delete=" + id;
	});
	
</script>

</body>
</html>