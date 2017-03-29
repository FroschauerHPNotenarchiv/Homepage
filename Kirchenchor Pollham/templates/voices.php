<html>

<head>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<?php include_once "admin_user_administration_func.php" ?>

</head>

<body>

<?php
	
	if(isset($_GET["update"]) && isset($_GET["name"])) {
		
	} else if(isset($_GET["delete"])) {
		
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
							 FROM {$GLOBALS["VOICES_TABLE"]}");
			
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
		<br />
	</div>
</div>

<script type="text/javascript">
	function vanishAllContainers() {
		$("#tab_alter").hide();
		$("#tab_create").hide();
	}
	
	function toggleContainers() {
		if($("#tab_alter").is(':visible')) {
			$("#tab_alter").hide();
			$("#tab_create").show();
		} else {
			$("#tab_alter").show();
			$("#tab_create").hide();
		}
	}
	
	vanishAllContainers();
	$("#tab_alter").show();
	
	$("#alter_button").click(function() {
		toggleContainers();
	});
	
	$("#new_button").click(function() {
		toggleContainers();
	});
	
	$(".item").keydown(function(event) {
		
		console.log(event);
		
		if(event.keyCode != 13)
			return;
		
		var id = $(this).parent().attr("value");
		var html = $(this).html();
		var location = window.location.href.substr(window.location.href, 0, window.location.href.indexOf('?'));
		window.location.href = location + "?update=" + id + "&name=" + html;
	});
	
	$(".deleteImg").click(function() {
		var id = $(this).parent().attr("value");
		var location = window.location.href.substr(window.location.href, 0, window.location.href.indexOf('?'));
		window.location.href = location + "?delete=" + id;
	});
	
</script>

</body>
</html>