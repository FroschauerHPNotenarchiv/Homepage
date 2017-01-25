<?php

include "admin_constants.php";

function print_debug($message) {
	?><script>document.body.innerHTML = "<p class='internal_error'><?php echo $message ?></p>";</script><?php
}

function default_connect() {
	connect("127.0.0.1", 5432, "Homepage");
}

function connect($host, $port, $dbname) {
	$connection = pg_connect("host={$host} port={$port} dbname={$dbname} user=postgres");
	if($connection)
		$_SESSION[$GLOBALS["DB_CONNECTION"]] = $connection;
	else
		print_debug("The database connection was not successfully established!");
}

function insert($table_name, $values) {
	pg_insert($_SESSION[$GLOBALS["DB_CONNECTION"]], $table_name, $values);
		//print_debug("Insert failed! Try again with different table_name or values!");
}

function delete_entries($table_name, $values) {
	pg_delete($_SESSION[$GLOBALS["DB_CONNECTION"]], $table_name, $values);
}

function update($table_name, $values, $conditions) {
	pg_update($_SESSION[$GLOBALS["DB_CONNECTION"]], $table_name, $values, $conditions);
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
		pg_close($_SESSION[$GLOBALS["DB_CONNECTION"]]);
		$_SESSION[$GLOBALS["DB_CONNECTION"]] = null;
	}
		
}

function request_voice_and_role_id() {
	$var = query("SELECT {$GLOBALS["COLUMN_VOICES_ID"]}
					 FROM {$GLOBALS["VOICES_TABLE"]}
					 WHERE {$GLOBALS["COLUMN_VOICES_DISPLAY_NAME"]} = '{$_POST['user_voice']}'");
	$voice_id = fetch_next_row($var)[0];
	
	$var = query("SELECT {$GLOBALS["COLUMN_ROLES_ID"]}
					 FROM {$GLOBALS["ROLES_TABLE"]}
					 WHERE {$GLOBALS["COLUMN_ROLES_DISPLAY_NAME"]} = '{$_POST['user_role']}'");
	$role_id = fetch_next_row($var)[0];
	return array($voice_id, $role_id);
}

?>