<?php
	session_start();
	$servername = "localhost"; 
    $username = "root";
    $password = "";        
    $dbname = "databaseesp";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	mysqli_set_charset($conn, "utf8");
	if (!$conn) 
	{
		die("Something went wrong;");
	}
	else
	{
			if( isset($_POST['submit']) && $_POST['username'] != '' && $_POST['password'] != '')
		{
			$username = $_POST['username'];
			$password = $_POST['password'];
			$remember = $_POST['rememberMe'];
			$sql = "SELECT * FROM users WHERE username='$username'" ;
			$result = mysqli_query($conn, $sql);
			$check_user = mysqli_num_rows($result);
			$user_data = mysqli_fetch_assoc($result);	
			if($check_user == 1)
			{	
				$checkpass= password_verify($password, $user_data['password']);
				if($user_data['password'] === md5($password) || $user_data['password'] === ($password))
				{
					if($remember ==1)
					{
						setcookie('username', $username, time()+60*60*24*10,"/");
						setcookie('password', $password , time()+60*60*24*10,"/");
					}
					$_SESSION['username'] = $user_data['username'];
					header("location:index_test.php");
					die();
				}
				if($checkpass)
				{
					if($remember ==1)
					{
						setcookie('username', $username, time()+60*60*24*10,"/");
						setcookie('password', $password , time()+60*60*24*10,"/");
					}
					$_SESSION['username'] = $user_data['username'];
					header("location:index_test.php");
					die();
				}
				else
				{
					$_SESSION["thongbao"] = "Incorrect password !";
					header("location:login.php");
					die();
				}
			}
			else{
			
				$_SESSION["thongbao"] = "Incorrect username !";
				header("location:login.php");
				die();
			}
			
				
		}
		else
		{
			$_SESSION["thongbao"] = "Please enter full information !";
			header("location:login.php");
		}	
	}
	
?>