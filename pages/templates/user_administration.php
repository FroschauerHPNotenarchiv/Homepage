<?php
	include_once 'functions.php';
?>

<!doctype html>
<html>
	<head>
	
		<style>
			.menu_item {
				float: left;
				margin: 1px;
				width: 12.08%;
				list-style: none;
				
				color: white;
				
				border: 1px solid rgba(150, 15, 15, 0.8);
				background-color: rgba(150, 15, 15, 0.5);
				text-align: center;
				padding: 2px;
			}
			
			.menu_item:hover {
				background-color: rgba(150, 15, 15, 0.4);
				transition: background-color 0.1s ease-in;
			}
			
			.content {
				clear: both;
				margin-left: 42px;
				width: 24%;
				height: 420px;
				background-color: rgba(150, 150, 150, 0.1);
				border: 1px solid rgba(150, 150, 150, 0.8);
				padding: 2px;
			}
			
			.group_box {
				border: 1px solid rgb(200, 15, 15);
				background-color: white;
				margin: 2px;
			}
			
			.group_box .header {
				text-align: center;
				margin: 5px 0px 0px 0px;
				height: 30px;
				color: rgb(150, 15, 15);
				border-bottom: 1px solid rgb(200, 15, 15);
			}
			
			.group_box .item {
				width: 100%;
				height: 20px;
			}
			
			.group_box .item .header {
				text-align: right;
				width: 50%;
				margin: 5px 0px 0px 0px;
				height: 30px;
				color: rgb(150, 15, 15);
				border-bottom: none;
			}
			
			.group_box .item input {
				margin: 5px;
			}
			
			button {
				width: 100%;
				border: 1px solid rgb(150, 15, 15);
				background-color: white;
				color: rgb(150, 15, 15);
				padding: 2px;
			}
			
			button:hover {
				border: 4px solid rgb(150, 15, 15);
				background-color: white;
				color: rgb(150, 15, 15);
				padding: 2px;
				transition: border .2s ease-in;
			}
			
			.selection_box {
				width: 100%;
			}
			
		</style>
	
	</head>
	<body>

		<script src="../../res/jquery-3.1.1.min.js"></script>
	
		<ul>
			<li class="menu_item user_creation" >Benutzer Anlegen</li>
			<li class="menu_item user_alteration " >Benutzer Bearbeiten</li>
		</ul>
		
		<div class="content user_creation_content" >
			<form>
				<div class="group_box">
					<p class="header">Allgemeines</p>
					<table>
						<tr class="item">
							<td class="header">E-Mail:</td>
							<td><input type="text" name="user_email" /></td>
						</tr>
						<tr class="item">
							<td class="header">Passwort:</td>
							<td><input type="text" name="users_password" /></td>
						</tr>
						<tr class="item">
							<td class="header">Tel.:</td>
							<td><input type="text" name="user_phone" /></td>
						</tr>
					</table>
				</div>
				
				<div class="group_box">
					<p class="header">Adresse</p>
					
					<table>
						<tr class="item">
							<td class="header">Ort:</td>
							<td><input type="text" name="user_place" /></td>
						</tr>
						<tr class="item">
							<td class="header">PLZ:</td>
							<td><input type="text" name="user_postal_code" /></td>
						</tr>
						<tr class="item">
							<td class="header">Straße:</td>
							<td><input type="text" name="user_street" /></td>
						</tr>
						<tr class="item">
							<td class="header">Hausnummer:</td>
							<td><input type="text" name="user_house_number" /></td>
						</tr>
					</table>
				</div>
				
				<div class="group_box">
					<p class="header">Stimme</p>
					<table>
						<tr class="item">
							<td class="header">Stimmgattung:</td>
							<td>
								<select class="selection_box" name="user_voice" >
									<!-- insert php code here: voices are fetched from database -->
									<?php
									
										connect("127.0.0.1", 5432, "Homepage");
										
										$result = query("SELECT voice_display_name
											             FROM voices;");
										
										while($row = fetch_next_row($result)) {
											?>
												<option><?php echo $row[0] ?></option>
											<?php
										}
										
										disconnect();
									
									?>
								</select>
							</td>
						</tr>
					</table>
				</div>
				
				<button type="submit">Bestätigen</button>
			</form>
		</div>
		
		<div class="content user_alteration_content">
			benutzer bearbeiten
		</div>
		
		<script>
					
			function hideAllContentContainers() {
				$(".content").hide();
			}
			
			hideAllContentContainers();
			$(".user_creation_content").show(); // default setting ; display change page for users
			
		
			$(".user_creation").click(function() {
				hideAllContentContainers();
				$(".user_creation_content").show();
			});
			
			$(".user_alteration").click(function() {
				hideAllContentContainers();
				$(".user_alteration_content").show();
			});

		
		</script>
	</body>

</html>