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
		$ID = $_POST['ID_empty']; 
        if(isset($ID))
        {
            $sql = "DELETE FROM fingerprint_register";
	
            if ($conn->query($sql) === TRUE) 
            {
                echo "Connected to Database successfully!!!";
            } 
            else 
            {
                echo "Error";
            }
        }			
	}
	
	$conn->close();  
?>