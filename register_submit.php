<?php
	session_start();	
	include 'config.php';
	if( isset($_POST['submit']) && $_POST['username'] != '' && $_POST['password'] != '' && $_POST['repassword'] != '')
	{
		date_default_timezone_set('Europe/Budapest');
		$d = date("Y-m-d");
		$t = date("H:i:s");
		$username = $_POST['username'];
		$password = ($_POST['password']);
		$repassword = ($_POST['repassword']);
        if ($password!==$repassword) 
		{
			$_SESSION["thongbao"] = "Repassword does not match";
            header("location:register.php");
			die();
        }
		$sql = "SELECT * FROM users WHERE username='$username'";
		$old = mysqli_query($conn,$sql);
		if(mysqli_num_rows($old) > 0)
		{
			$_SESSION["thongbao"] = "Username has existed";
			header("location:register.php");
			die();
		}
		else
		{		
			$password = password_hash($password, PASSWORD_DEFAULT);
			$sql = "INSERT INTO users (username, password, date, time) VALUES ('$username', '$password', '$d' , '$t')";
			mysqli_query($conn,$sql);
			$_SESSION["success"] = "Registered successfully";
			header("location:register.php");
			die();
		
		}		
		
						
	}
	else
	{
		$_SESSION["thongbao"] = "Please enter full information";
		header("location:register.php");
	}	
?>

