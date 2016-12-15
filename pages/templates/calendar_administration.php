<!DOCTYPE html>
<html>

	<style>
	
		.administration {
			width: 30%;
			background: lightgray;
			border: 2px solid black;
			padding: 5px 10px;
		}
		
		.calendar-input {
			width: 100%;
		}
		
		p {
			margin-bottom: 10px;
		}
		
		.calendar-input:focus {
			background: #8ef78c;
			text-decoration: none;
		}
		
		.submit {
			margin-top: 5px;
			width: 100%;
			text-align: center;
		}
		
		.submit:hover {
			font-weight: bold;
			cursor: pointer;
		}
	</style>
	<head>
		
	</head>
	
	<body>
		<div class="administration">
			
			<p>Interne Google-Kalender-ID: </p>
			<input class="calendar-input" type="text"/>
			<p>Externe Google-Kalender-ID: </p>
			<input class="calendar-input" type="text"/>
			<button class="submit">Speichern</button>
			
		</div>
	</body>
</html>