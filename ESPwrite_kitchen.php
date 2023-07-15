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

		
		$temp = $_POST['Temp'];
		$hum = $_POST['Hum'];
		$ppm = $_POST['PPM'];
		$light = $_POST['Light'];
		$timer = $_POST['Timer'];
	
		$sql = "INSERT INTO dbsensor_kitchen (Temp, Hum, PPM, Light, Timer, date, time) VALUES ('".$temp."', '".$hum."', '".$ppm."', '".$light."', '".$timer."','".$d."', '".$t."')";

		if (mysqli_multi_query($conn, $sql)) 
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