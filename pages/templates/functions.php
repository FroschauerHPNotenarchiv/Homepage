<?php

$GLOBALS["DB_CONNECTION"] = "DB_CONNECTION";

function print_debug($message) {
	?><script>document.body.innerHTML = "<p class='internal_error'><?php echo $message ?></p>";</script><?php
}

function connect($host, $port, $dbname) {
	$connection = pg_connect("host={$host} port={$port} dbname={$dbname} user=postgres");
	if($connection)
		$_SESSION[$GLOBALS["DB_CONNECTION"]] = $connection;
	else
		print_debug("The database connection was not successfully established!");
}

function query($sql) {
	$result = pg_query($_SESSION[$GLOBALS["DB_CONNECTION"]], $sql);
	if($result === null)
		print_debug("Query returned with no return values!");
	else
		return $result;
}

function fetch_next_row($result_set) {
	return pg_fetch_row($result_set);
}

function disconnect() {
	if($_SESSION[$GLOBALS["DB_CONNECTION"]] === null)
		print_debug("The database connection was closed prior to the invocation of the disconnect function!");
	else {
		pg_close($connection);
		$_SESSION[$GLOBALS["DB_CONNECTION"]] = null;
	}
		
}

?>