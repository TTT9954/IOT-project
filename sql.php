<?php 

define("serverName", "localhost");
define("userName", "root");
define("password", "");
define("dbName", "databaseesp");

function ConnectToSql() {
	$conn = mysqli_connect(serverName, userName, password);
	if (!$conn)
	    die("Connection failed: " . mysqli_connect_error()) . PHP_EOL;
	else;
		// echo "Connection to mysql success !" . PHP_EOL;

	return $conn;
}

function CloseSql($conn) {
	mysqli_close($conn);
}

// Create connection to database 'home'
function ConnectDatabse() {
	$conn = mysqli_connect(serverName, userName, password, dbName);
	// Check connection
	if (!$conn)
	    die("Connection failed: " . mysqli_connect_error());
	else;
		// echo "Connection database success !\n";
	return $conn;
}

// Disconnect to database
function CloseDatabase($conn) {
	mysqli_close($conn);
}

?>