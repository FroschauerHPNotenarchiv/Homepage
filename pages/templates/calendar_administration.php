<!DOCTYPE html>
<html>

	<style>
	
		.administration {
			width: 20%;
			background: lightgray;
			border: 2px solid black;
			padding: 5px 15px;
		}
		
		.calendar-input {
			width: 90%;
		}
		
		.calendar-input:focus {
			width: 90%;
			background: lightyellow;
			text-decoration: none;
		}
		
		.submit {
			margin-top: 5px;
		}
	</style>
	<head>
		
	</head>
	
	<body>
		<div class="administration">
			
			<p>Interne Kalender-ID: </p>
			<input class="calendar-input" type="text"/>
			<p>Externe Kalender-ID: </p>
			<input class="calendar-input" type="text"/>
			<button class="submit">Speichern</button>
			
		</div>
	</body>
</html>