<?php
	session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
    <title>Login</title>
</head>

<style>
* {
	box-sizing: border-box;
}
body {
	font-family: poppins;
	font-size: 16px;
	color: white;
	background-image: url('smart_home.jpg');
  	background-attachment: fixed;
  	background-repeat: no-repeat;
  	background-size: 100% 100%;
}
.form-box {
	background-color: rgba(0,0,0, 0.8);
	margin: auto auto;
	padding: 40px;
	border-radius: 5px;
	box-shadow: 0 0 10px #000;
	position: absolute;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	width: 500px;
	height: 430px;
	text-align: center;
}
.form-box:before {
	background-image: none;
	width: 100%;
	height: 100%;
	background-size: cover;
	content: "";
	position: fixed;
	right: 0px;
	bottom: 0px;
	z-index: -1;
	display: block;
	filter: blur(1px);
}
.form-box .header-text {
	font-size: 32px;
	font-weight: 600;
	text-align: center;
	margin-bottom: 18px;
}
.form-box input {
	margin: 10px 0px;
	border: none;
	padding: 10px;
	border-radius: 5px;
	width: 100%;
	font-size: 18px;
	font-family: poppins;
}
.form-box input[type=checkbox] {
	display: none;
}
.form-box label {
	position: relative;
	margin-left: 5px;
	margin-right: 10px;
	top: 5px;
	display: inline-block;
	width: 20px;
	height: 20px;
	cursor: pointer;
}
.form-box label:before {
	content: "";
	display: inline-block;
	width: 20px;
	height: 20px;
	border-radius: 5px;
	position: absolute;
	left: 0;
	bottom: 1px;
	background-color: #ddd;
}
.form-box input[type=checkbox]:checked+label:before {
	content: "\2713";
	font-size: 20px;
	color: #000;
	text-align: left;
	line-height: 20px;
}

.form-box span {
	font-size: 14px;
}

.form-box button {
	background-color: deepskyblue;
	color: #fff;
	border: none;
	border-radius: 5px;
	cursor: pointer;
	width: 100%;
	font-size: 18px;
	padding: 10px;
	margin: 20px 0px;
}

.form-box button:hover {
	background-color: #3498DB;
}


.form-box .session
{
	padding: 10px;
	text-align:center;
}

.form-box input:hover{
	background-color: #D5DBDB;
}

a{
	color: #19C3E8 ;	
}

a:hover{
	color: #F2F3F4;
}

</style>

<body>	

	<div class="form-box">
		<b><div class="header-text">Login</div></b>
		<span style="color: red; font-size: 22px" class="session"><b>
					<?php
						if( isset($_SESSION["thongbao"]))
						{
							echo $_SESSION["thongbao"];
							session_unset();
						}

					?>
		</b></span>
		<form action="login_submit.php" method="POST">
			
			<input type="text" name="username"  placeholder="Username" value="<?php if(isset($_COOKIE['username'])) echo $_COOKIE['username'] ?>">
			
			<input type="password" name="password" placeholder="Password" value="<?php if(isset($_COOKIE['password'])) echo $_COOKIE['password'] ?>"> 
				
			<input id="terms" type="checkbox" name="rememberMe" id="rememberMe" value="1"> 
			
			<label for="terms" for="rememberMe"></label><span style="font-size: 20px;">Remember me</span> 
			
			<button type="submit" name="submit">Login</button>
		
		</form>
		<span style="font-size: 20px; text-align:center"><b> Don't have an account ? </b></span> 
		<a href="register.php" style="font-size: 20px"><b>Register now</b></a>
	</div>
</body>

</html>