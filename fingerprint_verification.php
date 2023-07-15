<?php
	
	$servername = "localhost"; 
    $username = "root";
    $password = "";        
    $dbname = "databaseesp";
	
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	
	if ($conn->connect_error) 
	{
        die("Không thể kết nối tới Database" . $conn->connect_error);
    }
	else
	{
			
		date_default_timezone_set('Europe/Budapest');
		$d = date("Y-m-d");
		$t = date("H:i:s");
		
		$id_fp = $_POST['ID_FP'];
		$name = $_POST['Name'];
	
		$sql = "INSERT INTO fingerprint_verification (ID_FP, Name, Date, Time) VALUES ('".$id_fp."', '".$name."', '".$d."', '".$t."')";
		

		if ($conn->query($sql) === TRUE) 
		{
		    echo "Connected to Database!!!";
		} 
		else 
		{
		    echo "Error";
		}			
	}
	
	$conn->close();  

?>