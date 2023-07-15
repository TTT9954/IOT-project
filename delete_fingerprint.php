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
		//echo "Đã kết nối Database!!!";
		
		date_default_timezone_set('Europe/Budapest');
		$d = date("Y-m-d");
		$t = date("H:i:s");
		
		$ID = $_POST['ID_delete'];
		$sql = "DELETE FROM fingerprint_register WHERE ID_FP = '".$ID ."' ";

		if ($conn->query($sql) === TRUE) 
		{
		    echo "Connected to Database successfully!!!";
		} 
		else 
		{
		    echo "Error";
		}
			
	}
	$conn->close();  
?>