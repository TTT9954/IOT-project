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
		
		$ID = $_POST['ID_enroll'];
		$Name = $_POST['Name'];

		$sql = "SELECT * FROM fingerprint_register WHERE ID_FP='$ID'";
		$old = mysqli_query($conn,$sql);
		if(mysqli_num_rows($old) > 0)
		{
			echo "ID has existed";
			die();
		}
		else
		{
			$sql = "INSERT INTO fingerprint_register (ID_FP, Name, date, time) VALUES ('".$ID ."', '".$Name ."', '".$d."', '".$t."')";
	
			if ($conn->query($sql) === TRUE) 
			{
		    	echo "Connected to Database successfully!!!";
			} 
			else 
			{
		    	echo "Error";
			}
			die();
		}
		
			
	}
	
	$conn->close();  
?>