<?php

include_once "admin_constants.php";

$GLOBALS["SQL_INSERT"] = 0;
$GLOBALS["SQL_UPDATE"] = 1;
$GLOBALS["SQL_DELETE"] = 2;

function print_debug($message) {
	?><script>document.body.innerHTML = "<p class='internal_error'><?php echo $message ?></p>";</script><?php
}

function default_connect() {
	connect("mysql5.projekt-pollham.at", 3306, "db127631_3", "db127631_3", "Minecraft=0"); // mysql5.projekt-pollham.at, "db127631_3", "Minecraft=0"
}

function connect($host, $port, $dbname, $user, $password) {
	$connection = new PDO("mysql:host=" . $host . ";port=" . $port . ";dbname=" . $dbname . ";charset=utf8", $user, $password); // 
	$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	//$connection = /* mysqli_connect($host + ":" + $port, $user, $password, $dbname);*/ new mysqli($host + ":" + $port, $user, $password, $dbname);
	 
	if(!mysqli_connect_errno())
		$_SESSION[$GLOBALS["DB_CONNECTION"]] = $connection;
	else
		print_debug("The database connection was not successfully established! " . mysqli_connect_error());
}

function convertToSQL($stmType, $tableName,  $values, $conditions = array(1 => 1)) {
	$stm = "";
	switch($stmType) {
		case $GLOBALS["SQL_INSERT"]:
			$stm .= "insert into " . $tableName . " (";
			foreach($values as $key => $value) {
				$stm .= $key . ",";
			}
			$stm = substr($stm, 0, strlen($stm) - 1);
			$stm .= ") values (";
			foreach($values as $key => $value) {
				if (is_numeric($value))
					$stm .= $value . ",";
				else
					$stm .= "\"" . $value . "\"" . ",";
			}
			$stm = substr($stm, 0, strlen($stm) - 1);
			$stm .= ");";
			return $stm;
		case $GLOBALS["SQL_UPDATE"]:
			$stm .= "update " . $tableName . " set ";
			foreach($values as $key => $value) {
				$stm .= $key . " = ";
				if (is_numeric($value))
					$stm .= $value . ",";
				else
					$stm .= "\"" . $value . "\"" . ",";
			}
			$stm = substr($stm, 0, strlen($stm) - 1);
			$stm .= " where ";
			foreach($conditions as $key => $value) {
				$stm .= "convert(" . $key . ", char(500)) = \"" . $value . "\" and";
			}
			$stm = substr($stm, 0, strlen($stm) - 4);
			$stm .= ";";
			return $stm;
		case $GLOBALS["SQL_DELETE"]:
			$stm .= "delete from " . $tableName . " where ";
			foreach($conditions as $key => $value) {
				$stm .= "convert(" . $key . ", char(500)) = \"" . $value . "\" and";
			}
			$stm = substr($stm, 0, strlen($stm) - 4);
			$stm .= ";";
			return $stm;
	}
}

function insert($table_name, $values) {
	default_connect();
	$stm = $_SESSION[$GLOBALS["DB_CONNECTION"]]->prepare(convertToSQL($GLOBALS["SQL_INSERT"], $table_name, $values));
	$stm->execute();
		//print_debug("Insert failed! Try again with different table_name or values!");
	disconnect();
}

function delete_entries($table_name, $conditions) {
	default_connect();
	$stm = $_SESSION[$GLOBALS["DB_CONNECTION"]]->prepare(convertToSQL($GLOBALS["SQL_DELETE"], $table_name, null, $conditions));
	$stm->execute();
	//pg_delete($_SESSION[$GLOBALS["DB_CONNECTION"]], $table_name, $values);
	disconnect();
}

function update($table_name, $values, $conditions) {
	default_connect();
	$stm = $_SESSION[$GLOBALS["DB_CONNECTION"]]->prepare(convertToSQL($GLOBALS["SQL_UPDATE"], $table_name, $values, $conditions)); 
	$stm->execute();
	//pg_update($_SESSION[$GLOBALS["DB_CONNECTION"]], $table_name, $values, $conditions);
	disconnect();
	return $stm;
}

function query($sql) {
	default_connect();
	$stm = $_SESSION[$GLOBALS["DB_CONNECTION"]]->prepare($sql);
	$stm->execute(); // pg_query($_SESSION[$GLOBALS["DB_CONNECTION"]], $sql);
	disconnect();
	//if($result === null)
	//	print_debug("Query returned with no return values!");
	//else
	return $stm;
}

function getUserRole($email) {
	if($email === null)
		return 1000;
	
	$result =	query("SELECT {$GLOBALS["COLUMN_ROLES_ID"]}
					   FROM {$GLOBALS["USERS_TABLE"]}
			           WHERE {$GLOBALS["COLUMN_USER_EMAIL"]} = '{$email}'");
					   
	return fetch_next_row($result)[0];
}

function getUserEmail() {
	if(isset($_SESSION[$GLOBALS["SESSION_EMAIL"]]))
		return $_SESSION[$GLOBALS["SESSION_EMAIL"]];
	else
		return null;
}

function fetch_next_row($result_set) {
	return $result_set->fetch();
}

function disconnect() {
	if($_SESSION[$GLOBALS["DB_CONNECTION"]] === null)
		//print_debug("The database connection was closed prior to the invocation of the disconnect function!");
		return;
	else {
		//mysql_close($_SESSION[$GLOBALS["DB_CONNECTION"]]);
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