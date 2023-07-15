
<?php	
	session_start();
	if( !isset($_SESSION['username']))
	{
		header("location:login.php");
		die();
	}
?>


<!DOCTYPE html>

<html>

<head>
	<meta charset="utf-8"> 
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.js" type="text/javascript"></script>
	<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
</head>

<style>

body{
  font-family: Arial;
  margin: auto;
  text-align: center;
  background-color: #D7DBDD ;
  
  background-image: url('https://www.realestate.com.au/blog/wp-content/uploads/2019/08/19163753/beechwood-home.jpg');
  background-size: cover;
  background-attachment: fixed;
  background-repeat: no-repeat;
  background-size: 100% 100%;
}	

/* .title{
	margin: auto;
	text-align: center;
	font-size: 50px;
	min-width: 480px;
	max-width: 1920px;
	width: 100%;
	height: 100px;
	line-height: 100px;
	background-color: #50B8B4;
	overflow: hidden;
} */

.title{
	background-color: #2C3E50; 
	margin: auto;
	padding: 20px;
    text-align: center;
    text-transform: uppercase;
    font-family: verdana;
    font-size: 50px;
    color: #f5f5f5;
	height: 80px;
	line-height: 70px;
    text-shadow: 1px 1px 1px #919191,
        1px 2px 1px #919191,
        1px 3px 1px #919191,
        1px 4px 1px #919191,
        1px 5px 1px #919191,
        1px 6px 1px #919191,
        1px 7px 1px #919191,
        1px 8px 1px #919191,
        1px 9px 1px #919191,
        1px 10px 1px #919191,
    1px 18px 6px rgba(16,16,16,0.4),
    1px 22px 10px rgba(16,16,16,0.2),
    1px 25px 35px rgba(16,16,16,0.2),
    1px 30px 60px rgba(16,16,16,0.4);
}

#wrapper {  
    margin: auto;
	height: 100%;
	background-color: #F9E79F;
	border-bottom: 3px solid black;
}
a{
	font-size: 25px;
}

#logout{
	position: absolute;
	right: 0px;
} 

.nav_item {
  display: flex;
  height: 100%;
  
}

.nav-tabs {	
    display: inline-flex;
    list-style: none;
    margin: 0px;
    padding: 0px;
    
    
}

.nav-tabs li
{
    margin-right: 10px;

}
.nav-tabs li a {
    display: block;
    padding: 6px 10px;
    text-decoration: none;
    position: relative;
}

.nav-tabs li a::after {
    content: "";
    height: 3px;
    width: 100%;
    position:absolute;
    left: 0px;
	right: 0px;
    bottom: -3px;
    background: transparent;
}

.nav-tabs li.active a::after, .nav-tabs li:hover a::after{
    background: red;
}

i{
	font-size: 30px;
}

#Home, #Setting, #Sensor, #Control
{
    text-align: center;
}

#Home{
	background-color: rgba(133, 146, 158, 0.8);
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
	height: 250px;
	text-align: center;
	color: white;
}


.container 
{ 
	margin: auto;
    text-align: center;
}
.card { 
	display: inline-block;
	width: 400px;
	margin: 30px;
	line-height: 40px; 
	background-color: white;
	box-shadow: 2px 2px 12px 1px rgba(140,140,140,.5);
	border-radius: 20px; 	
}

.card.fingerprint { 
	display: inline-block;
	width: 500px;
	margin: 50px;
	line-height: 53px; 
	background-color: white;
	box-shadow: 2px 2px 12px 1px rgba(140,140,140,.5);
	border-radius: 20px; 	
}

.room{ 
	font-size: 1.5rem;
	border-bottom: 2px solid black;
}

.reading { font-size: 1.5rem; font-weight : bold;}
.timestamp { color: #bebebe; font-size: 1rem; }
.card-title{ font-size: 1.5rem; font-weight : bold; }
.temperature { color: #B10F2E; }
.humidity { color: #50B8B4; }
.PPM{color: #8E44AD;}

.content
{
	text-align: center;
	padding: 20px;
}

.card-grid-led {  
	width: 500px;
	margin-left: 40px;	
	margin-right: 40px;
	margin-bottom: 40px;	
	display: inline-block; 
}
.card-led {  
	background-color: white;  
	box-shadow: 2px 2px 12px 1px rgba(140,140,140,.5);
	border-radius: 20px;
	padding: 5px;
	font-size: 1.2rem;
	font-weight : bold;
}

/* .card-led h3{
	padding: 10px 5px;
	border-bottom: 2px solid black;
} */ 
.card-title-led {  
	font-size: 1.2rem;  
	font-weight: bold;  
	color: #034078;
}

/* table{
	border: 3px solid black;
	border-radius: 20px;
	border-collapse: collapse;
	box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
} */

.styled-table {
        border-collapse: collapse;
        margin-left: auto; 
        margin-right: auto;
        font-size: 0.9em;
        font-family: sans-serif;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        border-radius: 0.5em;
        overflow: hidden;
        width: 800px;		
      }

      .styled-table thead tr {
        color: #ffffff;
        text-align: center;
      }

      .styled-table th {
        padding: 12px 15px;
        text-align: center;
		border: 1px solid black;
      }

      .styled-table td {
        padding: 12px 15px;
        text-align: center;
		border: 1px solid black;
		
      }



select, option {
	font-size: 1rem;
}

button{
	cursor: pointer;
}

button{
  display: inline-block;
  padding: 5px 15px;
  cursor: pointer;
  text-align: center;
  text-decoration: none;
  outline: none;
  color: #fff;
  background-color: #4CAF50;
  border: none;
  border-radius: 15px;
  box-shadow: inset 0px 0px 0px #999, 0px 5px 0px 0px #999, 0px 10px 5px #999;
}

button:active {
	box-shadow: inset 0px 1px 0px #999, 0px 2px 0px 0px #999, 0px 5px 3px #999;
}

.button1{
  display: inline-block;
  padding: 10px 20px;
  font-size: 24px;
  cursor: pointer;
  text-align: center;
  text-decoration: none;
  outline: none;
  color: #fff;
  background-color: #4CAF50;
  border: none;
  border-radius: 15px;
  box-shadow: 0 9px #999;
}

.button1:hover {background-color: #3e8e41}

.button1:active {
  background-color: #3e8e41;
  box-shadow: 0 5px #666;
  transform: translateY(4px);
}

input {
     font-size: 20px;
     margin: auto;
 }

input#text_ID,  input#text_name, input#text_ID_del{
  width: 100px;
  margin: auto;
}
 .tablink {
  margin: 30px auto;
  background-color: #555;
  color: white;
  border: none;
  outline: none;
  cursor: pointer;
  font-size: 20px;
  padding: 20px 20px;
  width: 15%; 
  display: block;
  border-radius: 15px;
}

.tablink:hover {
  background-color: #777;
}

.tabs-database{
	text-align: center;

}

.tabrecord{
	margin: 0 auto;
	margin-top: 30px;
	display: border-box;
	width: 400px;
	height: 100px;
	background-color: white;
	box-shadow: 2px 2px 12px 1px rgba(140,140,140,.5);
	border-radius: 20px; 
	list-style: none;
	text-decoration: none;
	text-align: center;	
}

.tabrecord li a {
	display: inline-flex;
    text-decoration: none;
	color: #B10F2E;
	font-size: 30px;
	font-weight : bold;
	text-align: center;
	padding-top: 30px;
	height: 100%;
}


.tabrecord:hover{
	opacity: 0.8;
} 

/* .tabrecord.livingroom{
	background-image: url('https://cdn.home-designing.com/wp-content/uploads/2022/05/white-formal-living-room.jpg');
	background-size: cover;
}

.tabrecord.kitchen{
	background-image: url('https://dam.thdstatic.com/content/production/ErpKrNeDPM5QN3Pa1xIhHw/e-oeoq_si605dgSRsi5ZeQ/Original%20file/1u6GiAgiU41AqIV5oPCHHg_Hero-Kitchens-ReSize.jpg');
	background-size: cover;

}

.tabrecord.bedroom{
	background-image: url('https://cdn.shopify.com/s/files/1/1740/0017/articles/modern-bedroom-decor-francesca-tosolini-hCU4fimRW-c-unsplash-img_1365x.jpg?v=1588152906');
	background-size: cover;

}

.tabrecord.fingerprint{
	background-image: url('https://joy.videvo.net/videvo_files/video/premium/partners0315/thumbnails/BB_51934a9c-7df1-4f3c-9495-1bf1b09fa5fb_small.jpg');
	background-size: cover;

} */
</style>

<body onload ="timedate()">

<h1 class="title">SMART HOME</h1>
<div id = "wrapper">
    <div class="tabs">
        <ul class="nav-tabs">
            <li class="active"><a href="#Home">Home</a></li>
            <li><a href="#Setting">Setting</a></li>
            <li><a href="#Sensor">Sensors</a></li>
			<li><a href="#Control">Control</a></li>
            <li><a href="#Database">Database</a></li>
			<li id="logout"><a href="logout.php" title="logout">Logout</a></li>
        </ul>   
    </div>
</div>

<div class="tabs-content">
    <div id="Home" class="tab-content-item">
		<p id="data" class="styleIt"></p>
        <h1 style="font-size: 35px;">Date: <label id="date"></lable></h1>            
        <h1 style="font-size: 35px;">Time: <label id="time"></lable></h1>
        <h1 style="font-size: 35px;"><p class="logout">Welcome to <b><?php echo $_SESSION['username'] ?>'s house</b> </p></h1>                
    </div>

    <div id="Setting" class="tab-content-item">
	    <div class="dashboard container">
		
			<table class="styled-table" id= "table_id" align="center">
				<br>
				<tr>
				    <th bgcolor="#D6EAF8"  colspan="5"> <center> <font size="10"> <span style = "color: black"> MQTT SERVER CONNECTION</span></font></center></th>
			    </tr>
				<tbody id="tbody_table_record">
					<tr>
						<td bgcolor="#EBDEF0" colspan="1"> <center> <span style = "color: Blue; font-size:20px"> <b>  SERVER </b>  </span> </center> </td>
						<td bgcolor="#FBEEE6" colspan="4" align="center" >  
						<input type = "text" id="input_server" value = "ngoinhaiot.com">
					</td>						
			    	</tr>
					<tr>
				    <td bgcolor="#EBDEF0" colspan="1"> <center> <span style = "color: Blue; font-size:20px"> <b>  PORT </b>  </span> </center> </td>
				    <td bgcolor="#FBEEE6" colspan="4" align="center">  
					<input type = "text" id="input_port" value =2222>	  
				    </td>
						
			    </tr>

			    <tr>
				    <td bgcolor="#EBDEF0" colspan="1"> <center> <span style = "color: Blue; font-size:20px"> <b>  USERNAME </b>  </span> </center> </td>
				    <td bgcolor="#FBEEE6" colspan="4" align="center" >  
					<input type = "text" id="input_username" value = "thanhtu54">
				    </td>
						
			    </tr>

			    <tr>
				    <td bgcolor="#EBDEF0" colspan="1"> <center> <span style = "color: Blue; font-size:20px"> <b>  PASSWORD </b>  </span> </center> </td>
				    <td bgcolor="#FBEEE6" colspan="4" align="center" >  
					<input type = "password" id="input_password" value = "BD6085B020774167">
				    </td>						
			    </tr>	
			 			 
			     <tr>
				    <td bgcolor="#EBDEF0" height="70" colspan="1"> <center> <span style = "color: Blue; font-size:20px"> <b>  CONFIRM </b>  </span> </center> </td>
				    <td bgcolor="#FBEEE6" colspan="4" align="center" >  
					<button class="button1" onclick="SETSERVER()" id="btn_connect">Connect</button>

				    </td>
						
			    </tr>	
			 
			    <tr>
				    <td bgcolor="#EBDEF0" colspan="1"> <center> <span style = "color: Blue; font-size:20px"> <b>  CONNECTION NOTICE </b>  </span> </center> </td>
				    <td bgcolor="#FBEEE6" colspan="4" align="center" >  
					<br>
					<b><label id="thongbao" style = "font-size:30px">Disconnected</label></b>
					<br>
					<br>
				    </td>						
			    </tr>
				</tbody>   
			</table>
        </div>
    </div>

    <div id="Sensor" class="tab-content-item">
        <div class="container">
            <div class="card livingroom">
                <div class="room">
					<h3> <i class="fa-solid fa-couch"></i> LIVING ROOM</h3>
				</div>

                <div class="temperature">
                    <p class="card-title"><i class="fas fa-thermometer-half"></i> 
						TEMPERATURE: 
						<span class="reading"><span id="t1"></span> &deg;C</span>
					</p>
                     <!-- <p class="timestamp">Last Reading: <span id="rt3"></span></p> -->
                </div>
                <div class="humidity">
                    <p class="card-title"><i class="fas fa-tint"></i> 
						HUMIDITY: 
						<span class="reading"><span id="h1"></span> &percnt;</span>
					</p>
                   	<!-- <p class="timestamp">Last Reading: <span id="rh3"></span></p> -->
                </div> 
				<div class="PPM">
                    <p class="card-title"> 
						PPM: 
						<span class="reading"><span id="p1"></span></span>
					</p>
                   	<!-- <p class="timestamp">Last Reading: <span id="rh3"></span></p> -->
                </div>
            </div>
	
            <div class="card kitchen">
				<div class="room"><h3> <i class="fa-solid fa-kitchen-set"></i> KITCHEN </h3></div>
				<div class="temperature">
                    <p class="card-title"><i class="fas fa-thermometer-half"></i> 
						TEMPERATURE: 
						<span class="reading"><span id="t2"></span> &deg;C</span>
					</p>
                     <!-- <p class="timestamp">Last Reading: <span id="rt3"></span></p> -->
                </div>
                <div class="humidity">
                    <p class="card-title"><i class="fas fa-tint"></i> 
						HUMIDITY: 
						<span class="reading"><span id="h2"></span> &percnt;</span>
					</p>
                   	<!-- <p class="timestamp">Last Reading: <span id="rh3"></span></p> -->
                </div> 
				<div class="PPM">
                    <p class="card-title"> 
						PPM: 
						<span class="reading"><span id="p2"></span></span>
					</p>
                   	<!-- <p class="timestamp">Last Reading: <span id="rh3"></span></p> -->
                </div>
            </div>

            <div class="card bedroom">
				<div class="room"><h3> <i class="fa-solid fa-bed"></i> BEDROOM </h3></div>
                <div class="temperature">
                    <p class="card-title"><i class="fas fa-thermometer-half"></i> 
						TEMPERATURE: 
						<span class="reading"><span id="t3"></span> &deg;C</span>
					</p>
                     <!-- <p class="timestamp">Last Reading: <span id="rt3"></span></p> -->
                </div>
                <div class="humidity">
                    <p class="card-title"><i class="fas fa-tint"></i> 
						HUMIDITY: 
						<span class="reading"><span id="h3"></span> &percnt;</span>
					</p>
                   	<!-- <p class="timestamp">Last Reading: <span id="rh3"></span></p> -->
                </div>
				<div class="PPM">
                    <p class="card-title"> 
						PPM: 
						<span class="reading"><span id="p3"></span></span>
					</p>
                   	<!-- <p class="timestamp">Last Reading: <span id="rh3"></span></p> -->
                </div> 
			</div>                 
		</div>

        <div class="card fingerprint">
			<p class="card-title"> <i class="fa-sharp fa-solid fa-door-closed"></i> DOOR LOCK SYSTEM</p>
			<p  style="padding: 5px; border-bottom: 2px solid black"></p>
            <p class="card-title"> <i class="fa-solid fa-fingerprint"></i>FINGERPRINT :
            <span id="vantay" class="reading"></span><span id="ID" class="reading"></span>
			<p class="card-title">DOOR:  <img style="width: 50px; height: 70px;" id="Door_status" src="images/door-close.jpg" id="Door_status"></p>
			</p>
        </div> 
    </div>

	<div id="Control" class="tab-content-item">
		<div class="content"> 	 
	 		<div class="card-grid-led"> 
		 		<div class="card-led">
		 			<h3> Living room light </h3>      
					<div  style="padding: 7px; border-bottom: 2px solid black;"></div>           
			 		<p>Light state: <strong ><img style="width: 50px; height: 60px;" id="Light_livingroom_status" src="images/loff.jpg"></strong></p>        
			 		<button onclick = "LIGHT_ON_livingroom()" style="font-size: 1.2rem; background-color:#21DE4F">ON</button>          
			 		<button onclick = "LIGHT_OFF_livingroom()" style="font-size: 1.2rem; background-color:red">OFF</button>        
			 	
					 <div  style="padding: 7px; border-bottom: 2px solid black;"></div>
					<p style="font-size: 1.2rem;"> Timer state: <label id="Timer_livingroom_status">OFF</label> </p>
					<p>
						<select id="mode_onoff_livingroom"  style="width: 80px; height: 40px; font-size: 20px">
							<option value="select">Mode</option>
							<option value = "1" >ON</option>
							<option value = "2" >OFF</option>
						</select>
						<input id="gioduoi_livingroom" style="width: 50px; height: 34px;" value="00">
						<label>:</label>
						<input id="phutduoi_livingroom" style="width: 50px; height: 34px;" value="00">
						<label>-</label>
						<input id="giotren_livingroom" style="width: 50px; height: 34px;" value="00">
						<label>:</label>
						<input id="phuttren_livingroom" style="width: 50px; height: 34px;" value="00">
					</p>
					<p>
						<button onclick="TIMER_ON_livingroom()" style="font-size: 1.2rem;">TIMER ON</button>					
						<button onclick="TIMER_OFF_livingroom()" style="font-size: 1.2rem;">TIMER OFF</button>		
					</p>			
				</div>
			</div>

			<div class="card-grid-led"> 
		 		<div class="card-led">
		 			<h3> Kitchen light </h3> 
					 <div  style="padding: 7px; border-bottom: 2px solid black;"></div>           
                
			 		<p>Light state: <strong ><img style="width: 50px; height: 60px;" id="Light_kitchen_status" src="images/loff.jpg"></strong></p> 
			     
			 		<button onclick = "LIGHT_ON_kitchen()" style="font-size: 1.2rem; background-color:#21DE4F">ON</button>          
			 		<button onclick = "LIGHT_OFF_kitchen()" style="font-size: 1.2rem;background-color:red">OFF</button>        
			 	
					 <div  style="padding: 7px; border-bottom: 2px solid black"></div>
					<p> Timer state: <label id="Timer_kitchen_status">OFF</label> </p> 
					<p>
					<select id="mode_onoff_kitchen"  style="width: 80px; height: 40px; font-size: 20px">
						<option value="select">Mode</option>
						<option value = "1" >ON</option>
						<option value = "2" >OFF</option>
					</select>
 					<input id="gioduoi_kitchen" style="width: 50px; height: 34px;" value="00">
					<label>:</label>
					<input id="phutduoi_kitchen" style="width: 50px; height: 34px;" value="00">
					<label>-</label>
					<input id="giotren_kitchen" style="width: 50px; height: 34px;" value="00">
					<label>:</label>
					<input id="phuttren_kitchen" style="width: 50px; height: 34px;" value="00">
					</p>
					<p>
						<button onclick="TIMER_ON_kitchen()" style="font-size: 1.2rem;">TIMER ON</button>											
						<button onclick="TIMER_OFF_kitchen()" style="font-size: 1.2rem;">TIMER OFF</button>	 
					</p>
				</div>
			</div>

			<div class="card-grid-led"> 
		 		<div class="card-led">
		 			<h3> Bedroom light</h3>  
					 <div  style="padding: 7px; border-bottom: 2px solid black;"></div>           
               
			 		<p>Light state: <strong ><img style="width: 50px; height: 60px;" id="Light_bedroom_status" src="images/loff.jpg"></strong></p>        
			 		<button onclick = "LIGHT_ON_bedroom()" style="font-size: 1.2rem;background-color:#21DE4F">ON</button>          
			 		<button onclick = "LIGHT_OFF_bedroom()" style="font-size: 1.2rem;background-color:red">OFF</button> 
					<div  style="padding: 7px; border-bottom: 2px solid black"></div>        
					<p> Timer state: <label id="Timer_bedroom_status" style="font-size: 1.2rem;">OFF</label> </p>
 					<p>
						<select id="mode_onoff_bedroom"  style="width: 80px; height: 40px; font-size: 20px">
							<option value="select">Mode</option>
							<option value = "1" >ON</option>
							<option value = "2" >OFF</option>
						</select>
						<input id="gioduoi_bedroom" style="width: 50px; height: 34px;" value="00">
						<label>:</label>
						<input id="phutduoi_bedroom" style="width: 50px; height: 34px;" value="00"> 
						<label>-</label>
						<input id="giotren_bedroom" style="width: 50px; height: 34px;" value="00">
						<label>:</label>
						<input id="phuttren_bedroom" style="width: 50px; height: 34px;" value="00">
					</p>
					<p>
						<button onclick="TIMER_ON_bedroom()" style="font-size: 1.2rem;">TIMER ON</button>					
						<button onclick="TIMER_OFF_bedroom()" style="font-size: 1.2rem;">TIMER OFF</button>	 
					</p>	
				</div>
			</div>

			<div class="card-grid-led"> 
		 		<div class="card-led">
		 			<h3>Fingerprint sensor</h3>
					 <div  style="padding: 5px; border-bottom: 2px solid black;"></div>           

					<p tyle="font-size: 1.2rem;">
						Number of saved fingerprints: <label id="number_FP"></label>
					</p>                 
			 		<p style="font-size: 1.2rem;">
					 	<p id="enroll"></p>
						Register:
						<input id="text_ID" placeholder="your ID"></input>
						<input id="text_name" placeholder="your name"></input>
						<button onclick="Enroll()" style="font-size: 1.2rem; background-color: #50B8B4;">Enroll</button>
				
					</p>

					<p style="font-size: 1.2rem;">
						Delete ID: <span id = "del_ID"></span>
					
						<input id="text_ID_del" placeholder="your ID"></input>
						<button onclick="Delete()" style="font-size: 1.2rem; background-color: #50B8B4;">Delete</button>
				
					</p>

					<p style="font-size: 1.2rem;">
						Delete all: <span id = "del_all"></span>
						<button onclick="Empty()" style="font-size: 1.2rem; background-color: #50B8B4;">Empty</button>																																																								 
					</p>
				</div>
			</div>
		</div>
	</div>

	<div id="Database" class="tab-content-item">
		<div class="btn">
			<div class="tabs-database">
				<ul class="nav-tabs-database">
					<div class="tabrecord livingroom" > 
						<li><a href="Livingroom_record.php" target="_blank">Living room record</a></li>
					</div>
					<div class="tabrecord kitchen"> 
						<li><a href="Kitchen_record.php" target="_blank">Kitchen record</a></li>
					</div>
					<div class="tabrecord bedroom"> 
						<li><a href="Bedroom_record.php" target="_blank">Bedroom record</a></li>
					</div>
					<div class="tabrecord fingerprint"> 
						<li><a href="Fingerprint_record.php" target="_blank"> Fingerprint sensor record </a></li>
					</div>					
				</ul>   
    		</div>
		</div>             
    </div>
	
</div>

</body>

<script src="https://code.jquery.com/jquery-3.6.4.js"></script>
<script>


function getDateTime() {
  var currentdate = new Date();
  
  const hour = currentdate.getHours() < 10 ? "0" + currentdate.getHours() : currentdate.getHours();
  const minute = currentdate.getMinutes() < 10 ? "0" +currentdate.getMinutes() : currentdate.getMinutes();
  const second = currentdate.getSeconds() < 10 ? "0" + currentdate.getSeconds() : currentdate.getSeconds();
	
  var datetime = currentdate.getDate() + "/"
  + (currentdate.getMonth()+1) + "/"
  + currentdate.getFullYear() + " at "
  + hour + ":" + minute + ":" + second;
  return datetime;
}


var TTTB1;
// khai báo hàm , biến kết nối
var dataID;

var hostname;
var port;
var user;
var pass;

// Get the element with id="defaultOpen" and click on it
function SETSERVER()
{
	
	console.log(document.getElementById("btn_connect").innerHTML);
	if(document.getElementById("btn_connect").innerHTML = "Connect")
	{		
		ConnectMQTT();			
	}	
}

// topic nhận dữ liệu => esp gửi dữ liệu ở topic nào để web subscribe
var topicpub_livingroom = "thanhtu54/livingroom_from_Web";
		// topic ESP nhận dữ liệu
var topicsub_livingroom = "thanhtu54/livingroom_from_ESP";

var topicpub_kitchen = "thanhtu54/kitchen_from_Web";
		// topic ESP nhận dữ liệu
var topicsub_kitchen = "thanhtu54/kitchen_from_ESP";

var topicpub_bedroom = "thanhtu54/bedroom_from_Web";
		// topic ESP nhận dữ liệu
var topicsub_bedroom = "thanhtu54/bedroom_from_ESP";

var topicpub_fingerprint = "thanhtu54/fingerprint_from_Web";
		// topic ESP nhận dữ liệu
var topicsub_fingerprint = "thanhtu54/fingerprint_from_ESP";

function ConnectMQTT()
{
		hostname = document.getElementById("input_server").value;
		port = document.getElementById("input_port").value;
		user = document.getElementById("input_username").value;
		pass = document.getElementById("input_password").value;
		
		console.log(hostname);
		console.log(port);
		console.log(user);
		console.log(pass);
		
		// id client => random => giúp hệ thống không bị reset
		//var clientId = String(parseInt(Math.random() * 100, 10)); 
			var clientId = "Web";
		clientId += new Date().getUTCMilliseconds();;
		// topic nhận dữ liệu => esp gửi dữ liệu ở topic nào để web subscribe
		var topicsub1 = "thanhtu54/livingroom_from_ESP";


		var topicsub2 = "thanhtu54/kitchen_from_ESP";

	
		var topicsub3 = "thanhtu54/bedroom_from_ESP";


		var topicsub4 = "thanhtu54/fingerprint_from_ESP";
 
		var  Message_received;
		var DataJson;

		mqttClient = new Paho.MQTT.Client(hostname, parseInt(port), clientId);

		// hàm đọc dữ liệu
		
		// connect
		Connect();

		mqttClient.onMessageArrived = MessageArrived;

		// hàm check lỗi
		mqttClient.onConnectionLost = ConnectionLost;

		/*Initiates a connection to the MQTT broker*/
		function Connect(){
			mqttClient.connect({
			useSSL: true,
			userName: user,
			password: pass,
			onSuccess: Connected,
			onFailure: ConnectionFailed,
			keepAliveInterval: 10,
		});
		}

		/*Callback for successful MQTT connection */
		function Connected() {
			console.log("Connected MQTT broker ngoinhaiot.com"); // println => monitor => để thấy nó có kết nối hay không
			// Kết nối xong thì đăng kí topic nhận dữ liệu
			mqttClient.subscribe(topicsub1);
			mqttClient.subscribe(topicsub2);
			mqttClient.subscribe(topicsub3);
			mqttClient.subscribe(topicsub4);
			//alert('Connected to MQTT Broker ngoinhaiot.com');
			document.getElementById("thongbao").innerHTML = "Connected";						
		}

		/*Callback for failed connection*/
		function ConnectionFailed(res) {
			console.log("Connect failed:" + res.errorMessage);
			//alert('Unable to connect please check server parameters');
			document.getElementById("thongbao").innerHTML = "Connection errors";
			
		}

		/*Callback for lost connection*/
		function ConnectionLost(res) {
			if (res.errorCode !== 0) 
			{
				console.log("Connection lost:" + res.errorMessage);
				Connect();
			}
		}	 

		function MessageArrived(message) 
		{  
			console.log("Data ESP:" + message.payloadString);
			var inputChecked;
      		var outputStateM;

			Message_received = message.payloadString;
			  
	   		DataJson = JSON.parse(Message_received); 
						   
			if(DataJson.Temp_livingroom != null)
			{
				document.getElementById("t1").innerHTML = DataJson.Temp_livingroom;
			}
			
			if(DataJson.Hum_livingroom != null)
			{
				document.getElementById("h1").innerHTML = DataJson.Hum_livingroom;
			}

			if(DataJson.PPM_livingroom != null)
			{
				document.getElementById("p1").innerHTML = DataJson.PPM_livingroom;
			}

			if(DataJson.Temp_kitchen != null)
			{
				document.getElementById("t2").innerHTML = DataJson.Temp_kitchen;
			}
			
			if(DataJson.Hum_kitchen != null)
			{
				document.getElementById("h2").innerHTML = DataJson.Hum_kitchen;
			}

			if(DataJson.PPM_kitchen != null)
			{
				document.getElementById("p2").innerHTML = DataJson.PPM_kitchen;
			}

			if(DataJson.Temp_bedroom != null)
			{
				document.getElementById("t3").innerHTML = DataJson.Temp_bedroom;
			}
			
			if(DataJson.Hum_bedroom != null)
			{
				document.getElementById("h3").innerHTML = DataJson.Hum_bedroom;
			}

			if(DataJson.PPM_bedroom != null)
			{
				document.getElementById("p3").innerHTML = DataJson.PPM_bedroom;
			}
			
			if(DataJson.Enroll != null)
			{
				if(DataJson.Enroll == 0)
				{
					document.getElementById("enroll").innerHTML = " ";
				}
				else if(DataJson.Enroll == 1)
				{
					document.getElementById("enroll").innerHTML = "Put finger to enroll !";
				}
				else if(DataJson.Enroll == 2)
				{
					document.getElementById("enroll").innerHTML = "Remove finger";
				}
				else if(DataJson.Enroll == 3)
				{
					document.getElementById("enroll").innerHTML = "Place same finger again";
				}
				else if(DataJson.Enroll == 4)
				{
					document.getElementById("enroll").innerHTML = "Fingerprints did not match";
				}
				else if(DataJson.Enroll == 5)
				{
					document.getElementById("enroll").innerHTML = "Stored !";
				}
				
			}

			if(DataJson.ID != null || DataJson.Name != null)
			{
				if(DataJson.ID == -2)
				{
					document.getElementById("vantay").innerHTML = "Enrolling !";
					document.getElementById("ID").innerHTML = "";
					document.getElementById("Door_status").src = "images/door-close.jpg";
				}      
				else if(DataJson.ID == -1)
				{
					document.getElementById("vantay").innerHTML = "Scanning";
					document.getElementById("ID").innerHTML = "";
					document.getElementById("Door_status").src = "images/door-close.jpg";
				}
				else if(DataJson.ID == 0)
				{
					
					document.getElementById("vantay").innerHTML = "Not Valid Fingerprint";
					document.getElementById("ID").innerHTML = "";
					document.getElementById("Door_status").src = "images/door-close.jpg";
				}
				else
				{
					document.getElementById("vantay").innerHTML = "Welcome " + DataJson.Name + "!" ;
					document.getElementById("ID").innerHTML = " (#ID: " + DataJson.ID + ")";
					document.getElementById("Door_status").src = "images/door-open.jpg";
				}
				 				
			}
						
			if(DataJson.saved_fingerprint != null)
			{
				if(DataJson.saved_fingerprint == 0)
				{

					dataID = 1;
					document.getElementById("number_FP").innerHTML = "0";
				} 
				else 
				{	

					dataID = 0;
					document.getElementById("number_FP").innerHTML = DataJson.saved_fingerprint;										
				}
					
			}

	  
			if(DataJson.Light_livingroom != null)
			{
				//document.getElementById("cai3").innerHTML = DataJson.C3;
				
				if(DataJson.Light_livingroom == 0)
				{				
					document.getElementById("Light_livingroom_status").src = "images/loff.jpg";
					
				
				}
				else if(DataJson.Light_livingroom == 1)
				{
										
					document.getElementById("Light_livingroom_status").src = "images/lon.jpg";					
				}				
			}

			if(DataJson.Light_kitchen != null)
			{
				//document.getElementById("cai3").innerHTML = DataJson.C3;
				
				if(DataJson.Light_kitchen == 0)
				{				
					document.getElementById("Light_kitchen_status").src = "images/loff.jpg";
					
				
				}
				else if(DataJson.Light_kitchen == 1)
				{
										
					document.getElementById("Light_kitchen_status").src = "images/lon.jpg";					
				}				
			}
						
			if(DataJson.Light_bedroom != null)
			{
				//document.getElementById("cai3").innerHTML = DataJson.C3;
				
				if(DataJson.Light_bedroom == 0)
				{
					document.getElementById("Light_bedroom_status").src = "images/loff.jpg";
				}
				else if(DataJson.Light_bedroom == 1)
				{
					document.getElementById("Light_bedroom_status").src = "images/lon.jpg";
				}				
			}
			
			
			if(DataJson.Light_livingroom_timer != null)
			{
				
				
				if(DataJson.Light_livingroom_timer == 0)
				{
					document.getElementById("Timer_livingroom_status").innerHTML = "OFF";
				}
				else if(DataJson.Light_livingroom_timer == 1)
				{
					document.getElementById("Timer_livingroom_status").innerHTML = "ON";
				}				
			}

			if(DataJson.Light_kitchen_timer != null)
			{				
				if(DataJson.Light_kitchen_timer == 0)
				{
					document.getElementById("Timer_kitchen_status").innerHTML = "OFF";
				}
				else if(DataJson.Light_kitchen_timer == 1)
				{
					document.getElementById("Timer_kitchen_status").innerHTML = "ON";
				}				
			}

			if(DataJson.Light_bedroom_timer != null)
			{
				
				
				if(DataJson.Light_bedroom_timer == 0)
				{
					document.getElementById("Timer_bedroom_status").innerHTML = "OFF";
				}
				else if(DataJson.Light_bedroom_timer == 1)
				{
					document.getElementById("Timer_bedroom_status").innerHTML = "ON";
				}
				
			}
		}

}


function timedate() 
{
  const today = new Date();

    // get time components
    const hours = today.getHours();
    const minutes = today.getMinutes();
    const seconds = today.getSeconds();


    //add '0' to hour, minute & second when they are less 10
    const hour = hours < 10 ? "0" + hours : hours;
    const minute = minutes < 10 ? "0" + minutes : minutes;
    const second = seconds < 10 ? "0" + seconds : seconds;

    const month = today.getMonth();
    const year = today.getFullYear();
    const day = today.getDate();

    //declaring a list of all months in  a year
    const monthList = [
      "January",
      "February",
      "March",
      "April",
      "May",
      "June",
      "July",
      "August",
      "September",
      "October",
      "November",
      "December"
    ];

    //get current date and time
    const date = monthList[month] + " " + day + ", " + year;
  
    const time = hour + ":" + minute + ":" + second;

    //print current date and time to the DOM
    document.getElementById("date").innerHTML = date;

    document.getElementById("time").innerHTML = time;
	  
    setTimeout(function(){ timedate()  }, 1000);
}


function LIGHT_ON_livingroom()
{
	console.log("Button đèn onclick!!!");
 	var DataSend = "{\"Light_livingroom\":\"1\"}";
 	mqttClient.send(topicpub_livingroom, DataSend);
}

function LIGHT_ON_kitchen()
{
	console.log("Button đèn onclick!!!");
	var DataSend = "{\"Light_kitchen\":\"1\"}";
 	mqttClient.send(topicpub_kitchen, DataSend);
}

function LIGHT_ON_bedroom()
{
	console.log("Button đèn onclick!!!");
	var DataSend = "{\"Light_bedroom\":\"1\"}";
 	mqttClient.send(topicpub_bedroom, DataSend);
}

function LIGHT_OFF_livingroom()
{  
  console.log("Button đèn onclick!!!");
  var DataSend = "{\"Light_livingroom\":\"0\"}";
  mqttClient.send(topicpub_livingroom, DataSend);
}

function LIGHT_OFF_kitchen()
{  
  console.log("Button đèn onclick!!!");
  var DataSend = "{\"Light_kitchen\":\"0\"}";
  mqttClient.send(topicpub_kitchen, DataSend);
}

function LIGHT_OFF_bedroom()
{
	console.log("Button đèn onclick!!!");
	var DataSend = "{\"Light_bedroom\":\"0\"}";
 	mqttClient.send(topicpub_bedroom, DataSend);
}

function Enroll()
{
  console.log("Enrolling !!!");
  var tx = document.getElementById("text_ID").value;
  var tx_name = document.getElementById("text_name").value;
  console.log("Tx: " + tx);
  console.log("Tx_name: " + tx_name);
  var DataSend = "{\"Enroll\":\"1\",\"enroll_ID\":\"" + String(tx) + "\",\"enroll_name\":\"" + String(tx_name) + "\"}";
  //mqttClient.send(topicpub, DataSend);
  mqttClient.send(topicpub_fingerprint, DataSend);
}

function Delete()
{
	if(dataID == 1)
  {
	alert("Sensor doesn't contain any fingerprint data.");
  }
  
	console.log("Deleting !!!");
  	var tx = document.getElementById("text_ID_del").value;
  	console.log("Tx: " + tx);
	if(tx != null)
	{
		var DataSend = "{\"Delete\":\"1\",\"delete_ID\":"+tx+"}";
		mqttClient.send(topicpub_fingerprint, DataSend);
	}
  	else
	{
		alert("Please enter your ID to delete.");
	}
  	//mqttClient.send(topicpub, DataSend);   
}

function Empty()
{
  console.log("Empty !!!");
  let text = "Are you sure you want to delete all fingerprint ?";
  if (confirm(text) == true) {
    var DataSend = "{\"Empty\":\"1\"}";
  	mqttClient.send(topicpub_fingerprint, DataSend);
  } 
  if(dataID == 1)
  {
	alert("Sensor doesn't contain any fingerprint data.");
  }
}

function TIMER_ON_livingroom()
{	
	var select_onoff = document.getElementById("mode_onoff_livingroom");
	var onoff = select_onoff.options[select_onoff.selectedIndex].value;
  
	var trangthaihengioden = document.getElementById("Timer_livingroom_status").innerHTML;
	var trangthaiden_onoff = document.getElementById("Light_livingroom_status");

		if(onoff == "select")
		{
			alert("Please choose Mode ON or OFF !");
		}
	
		if(onoff == "1")
		{     
			console.log("Chọn hẹn giờ ON đèn !!");
			if(trangthaiden_onoff.src.match("images/lon.jpg"))
			{
				alert("Your light is on, please select OFF mode for setting the timer to turn off the light!!!");
				return;
			}
			else
			{
				if(trangthaihengioden == "ON")
				{
				  alert("Tắt hẹn giờ đi rùi cài đặt nha!!!");
						return;
				}
				else if(trangthaihengioden == "OFF")    
				{       					
					var selectgioduoi = document.getElementById("gioduoi_livingroom").value;
                    console.log("textgioduoi: "+ selectgioduoi);
					
					var selectphutduoi = document.getElementById("phutduoi_livingroom").value;
                    console.log("textphutduoi: "+selectphutduoi);

					var selectgiotren = document.getElementById("giotren_livingroom").value;
                    console.log("textgiotren: "+selectphutduoi);

					var selectphuttren = document.getElementById("phuttren_livingroom").value;
                    console.log("textphuttren: "+selectphuttren);

					if(selectgiotren == selectgioduoi)
                    {
						
                        if(selectphuttren > selectphutduoi)
                        {
                            
							var DataSend = "{\"Light_livingroom_timer\":\"1\",\"Mode_on\":\"1\",\"GD\":"+selectgioduoi+",\"PD\":"+selectphutduoi+",\"GT\":"+selectgiotren+",\"PT\":"+selectphuttren+"}";
							
							console.log("txden: "+DataSend);	
							
							mqttClient.send(topicpub_livingroom, DataSend);
													
                        }
                        else if(selectphuttren <= selectphutduoi)
                        {
                            alert("Vui lòng chọn phút dưới nhỏ hơn phút trên");
                             return;
                        }                    
                    }
                    else if(selectgiotren > selectgioduoi)
                    {
						var DataSend = "{\"Light_livingroom_timer\":\"1\",\"Mode_on\":\"1\",\"GD\":"+selectgioduoi+",\"PD\":"+selectphutduoi+",\"GT\":"+selectgiotren+",\"PT\":"+selectphuttren+"}";							
						console.log("txden: "+DataSend);						
						mqttClient.send(topicpub_livingroom, DataSend);
								
                    }
                    else if(selectgiotren < selectgioduoi)
                    {
						
                          alert("Vui lòng chọn giờ dưới lớn hơn hoặc bằng giờ trên");
                         return;
                    }
/* 					var DataSend = "{\"Mode_on_den\":\"1\"\}";
					mqttClient.send(topicpub_livingroom, DataSend); */
				}		
			}
		}
		else if(onoff == "2")
		{      
			console.log("Chọn hẹn giờ OFF đèn !!");
			if(trangthaiden_onoff.src.match("images/loff.jpg"))
			{
				alert("Your light is off, please select ON mode for setting the timer to turn on the light!!!");
				return;
			}
			else
			{
				if(trangthaihengioden == "ON")
				{
					alert("Tắt hẹn giờ đi rùi cài đặt nha!!!");
					return;
				}
				else if(trangthaihengioden == "OFF")    
				{     	
/* 					var DataSend = "{\"Mode_off_den\":\"1\"\}";
					mqttClient.send(topicpub_livingroom, DataSend); */			  
					var selectgioduoi = document.getElementById("gioduoi_livingroom").value;
                    console.log("textgioduoi: "+ selectgioduoi);
					
					var selectphutduoi = document.getElementById("phutduoi_livingroom").value;
                    console.log("textphutduoi: "+selectphutduoi);

					var selectgiotren = document.getElementById("giotren_livingroom").value;
                    console.log("textgiotren: "+selectphutduoi);

					var selectphuttren = document.getElementById("phuttren_livingroom").value;
                    console.log("textphuttren: "+selectphuttren);

					if(selectgiotren == selectgioduoi)
                    	{
						
                         if(selectphuttren > selectphutduoi)
                        {
                            
							var DataSend = "{\"Light_livingroom_timer\":\"1\",\"Mode_off\":\"1\",\"GD\":"+selectgioduoi+",\"PD\":"+selectphutduoi+",\"GT\":"+selectgiotren+",\"PT\":"+selectphuttren+"}";
							
							console.log("txden: "+DataSend);	
							
							mqttClient.send(topicpub_livingroom, DataSend);
													
                        }
                        else if(selectphuttren <= selectphutduoi)
                        {
                            alert("Vui lòng chọn phút dưới nhỏ hơn phút trên");
                             return;
                        }
                      
                    }
                    else if(selectgiotren > selectgioduoi)
                    {
						var DataSend = "{\"Light_livingroom_timer\":\"1\",\"Mode_off\":\"1\",\"GD\":"+selectgioduoi+",\"PD\":"+selectphutduoi+",\"GT\":"+selectgiotren+",\"PT\":"+selectphuttren+"}";							
						console.log("txden: "+DataSend);						
						mqttClient.send(topicpub_livingroom, DataSend);
								
                    }
                    else if(selectgiotren < selectgioduoi)
                    {
						
                          alert("Vui lòng chọn giờ dưới lớn hơn hoặc bằng giờ trên");
                         return;
                    }
					
				}
			}
      
		}     
					 
}

function TIMER_ON_kitchen()
{	
	var select_onoff = document.getElementById("mode_onoff_kitchen");
	var onoff = select_onoff.options[select_onoff.selectedIndex].value;
  
	var trangthaihengioden = document.getElementById("Timer_kitchen_status").innerHTML;
	var trangthaiden_onoff = document.getElementById("Light_kitchen_status");

		if(onoff == "1")
		{     
			console.log("Chọn hẹn giờ ON đèn !!");
			if(trangthaiden_onoff.src.match("images/lon.jpg"))
			{
				alert("Đèn của bạn đang bật, vui lòng chọn chế độ hẹn giờ OFF đèn!!!");
				return;
			}
			else
			{
				if(trangthaihengioden == "ON")
				{
				  alert("Tắt hẹn giờ đi rùi cài đặt nha!!!");
						return;
				}
				else if(trangthaihengioden == "OFF")    
				{       					
					var selectgioduoi = document.getElementById("gioduoi_kitchen").value;
                    console.log("textgioduoi: "+ selectgioduoi);
					
					var selectphutduoi = document.getElementById("phutduoi_kitchen").value;
                    console.log("textphutduoi: "+selectphutduoi);

					var selectgiotren = document.getElementById("giotren_kitchen").value;
                    console.log("textgiotren: "+selectphutduoi);

					var selectphuttren = document.getElementById("phuttren_kitchen").value;
                    console.log("textphuttren: "+selectphuttren);

					if(selectgiotren == selectgioduoi)
                    {						
                        if(selectphuttren > selectphutduoi)
                        {
                            
							 var DataSend = "{\"Light_kitchen_timer\":\"1\",\"Mode_on\":\"1\",\"GD\":"+selectgioduoi+",\"PD\":"+selectphutduoi+",\"GT\":"+selectgiotren+",\"PT\":"+selectphuttren+"}";
							
							console.log("Timer: "+DataSend);	
							
							mqttClient.send(topicpub_kitchen, DataSend);
													
                        }
                        else if(selectphuttren <= selectphutduoi)
                        {
                            alert("Vui lòng chọn phút dưới nhỏ hơn phút trên");
                             return;
                        }
                      
                    }
                    else if(selectgiotren > selectgioduoi)
                    {
                      var DataSend = "{\"Light_kitchen_timer\":\"1\",\"Mode_on\":\"1\",\"GD\":"+selectgioduoi+",\"PD\":"+selectphutduoi+",\"GT\":"+selectgiotren+",\"PT\":"+selectphuttren+"}";
							
							console.log("Timer: "+DataSend);	
							
							mqttClient.send(topicpub_kitchen, DataSend);
								
                    }
                    else if(selectgiotren < selectgioduoi)
                    {
						
                          alert("Vui lòng chọn giờ dưới lớn hơn hoặc bằng giờ trên");
                         return;
                    }
/* 					var DataSend = "{\"Mode_on_den\":\"1\"\}";
					mqttClient.send(topicpub_bedroom, DataSend); */
				}		
			}
		}
		else if(onoff == "2")
		{      
			console.log("Chọn hẹn giờ OFF đèn !!");
			if(trangthaiden_onoff.src.match("images/loff.jpg"))
			{
				alert("Đèn của bạn đang tắt, vui lòng chọn chế độ hẹn giờ ON đèn!!!");
				return;
			}
			else
			{
				if(trangthaihengioden == "ON")
				{
					alert("Tắt hẹn giờ đi rùi cài đặt nha!!!");
					return;
				}
				else if(trangthaihengioden == "OFF")    
				{     
					  
					var selectgioduoi = document.getElementById("gioduoi_kitchen").value;
                    console.log("textgioduoi: "+ selectgioduoi);
					
					var selectphutduoi = document.getElementById("phutduoi_kitchen").value;
                    console.log("textphutduoi: "+selectphutduoi);

					var selectgiotren = document.getElementById("giotren_kitchen").value;
                    console.log("textgiotren: "+selectphutduoi);

					var selectphuttren = document.getElementById("phuttren_kitchen").value;
                    console.log("textphuttren: "+selectphuttren);

					if(selectgiotren == selectgioduoi)
                    	{
						
                         if(selectphuttren > selectphutduoi)
                        {
                            
							 var DataSend = "{\"Light_kitchen_timer\":\"1\",\"Mode_off\":\"1\",\"GD\":"+selectgioduoi+",\"PD\":"+selectphutduoi+",\"GT\":"+selectgiotren+",\"PT\":"+selectphuttren+"}";
							
							console.log("txden: "+DataSend);	
							
							mqttClient.send(topicpub_kitchen, DataSend);
													
                        }
                        else if(selectphuttren <= selectphutduoi)
                        {
                            alert("Vui lòng chọn phút dưới nhỏ hơn phút trên");
                             return;
                        }
                      
                    }
                    else if(selectgiotren > selectgioduoi)
                    {
                      var DataSend = "{\"Light_kitchen_timer\":\"1\",\"Mode_off\":\"1\",\"GD\":"+selectgioduoi+",\"PD\":"+selectphutduoi+",\"GT\":"+selectgiotren+",\"PT\":"+selectphuttren+"}";
							
							console.log("txden: "+DataSend);	
							
							mqttClient.send(topicpub_kitchen, DataSend);
								
                    }
                    else if(selectgiotren < selectgioduoi)
                    {
						
                          alert("Vui lòng chọn giờ dưới lớn hơn hoặc bằng giờ trên");
                         return;
                    }

				}
			}
      
		}     
					 
}

function TIMER_ON_bedroom()
{	
	var select_onoff = document.getElementById("mode_onoff_bedroom");
	var onoff = select_onoff.options[select_onoff.selectedIndex].value;
  
	var trangthaihengioden = document.getElementById("Timer_bedroom_status").innerHTML;
	var trangthaiden_onoff = document.getElementById("Light_bedroom_status");

		if(onoff == "1")
		{     
			console.log("Chọn hẹn giờ ON đèn !!");
			if(trangthaiden_onoff.src.match("images/lon.jpg"))
			{
				alert("Đèn của bạn đang bật, vui lòng chọn chế độ hẹn giờ OFF đèn!!!");
				return;
			}
			else
			{
				if(trangthaihengioden == "ON")
				{
				  alert("Tắt hẹn giờ đi rùi cài đặt nha!!!");
						return;
				}
				else if(trangthaihengioden == "OFF")    
				{       					
					var selectgioduoi = document.getElementById("gioduoi_bedroom").value;
                    console.log("textgioduoi: "+ selectgioduoi);
					
					var selectphutduoi = document.getElementById("phutduoi_bedroom").value;
                    console.log("textphutduoi: "+selectphutduoi);

					var selectgiotren = document.getElementById("giotren_bedroom").value;
                    console.log("textgiotren: "+selectphutduoi);

					var selectphuttren = document.getElementById("phuttren_bedroom").value;
                    console.log("textphuttren: "+selectphuttren);

					if(selectgiotren == selectgioduoi)
                    {						
                        if(selectphuttren > selectphutduoi)
                        {
                            
							 var DataSend = "{\"Light_bedroom_timer\":\"1\",\"Mode_on\":\"1\",\"GD\":"+selectgioduoi+",\"PD\":"+selectphutduoi+",\"GT\":"+selectgiotren+",\"PT\":"+selectphuttren+"}";
							
							console.log("Timer: "+DataSend);	
							
							mqttClient.send(topicpub_bedroom, DataSend);
													
                        }
                        else if(selectphuttren <= selectphutduoi)
                        {
                            alert("Vui lòng chọn phút dưới nhỏ hơn phút trên");
                             return;
                        }
                      
                    }
                    else if(selectgiotren > selectgioduoi)
                    {
                      var DataSend = "{\"Light_bedroom_timer\":\"1\",\"Mode_on\":\"1\",\"GD\":"+selectgioduoi+",\"PD\":"+selectphutduoi+",\"GT\":"+selectgiotren+",\"PT\":"+selectphuttren+"}";
							
							console.log("Timer: "+DataSend);	
							
							mqttClient.send(topicpub_bedroom, DataSend);
								
                    }
                    else if(selectgiotren < selectgioduoi)
                    {
						
                          alert("Vui lòng chọn giờ dưới lớn hơn hoặc bằng giờ trên");
                         return;
                    }
/* 					var DataSend = "{\"Mode_on_den\":\"1\"\}";
					mqttClient.send(topicpub_bedroom, DataSend); */
				}		
			}
		}
		else if(onoff == "2")
		{      
			console.log("Chọn hẹn giờ OFF đèn !!");
			if(trangthaiden_onoff.src.match("images/loff.jpg"))
			{
				alert("Đèn của bạn đang tắt, vui lòng chọn chế độ hẹn giờ ON đèn!!!");
				return;
			}
			else
			{
				if(trangthaihengioden == "ON")
				{
					alert("Tắt hẹn giờ đi rùi cài đặt nha!!!");
					return;
				}
				else if(trangthaihengioden == "OFF")    
				{     
					  
					var selectgioduoi = document.getElementById("gioduoi_bedroom").value;
                    console.log("textgioduoi: "+ selectgioduoi);
					
					var selectphutduoi = document.getElementById("phutduoi_bedroom").value;
                    console.log("textphutduoi: "+selectphutduoi);

					var selectgiotren = document.getElementById("giotren_bedroom").value;
                    console.log("textgiotren: "+selectphutduoi);

					var selectphuttren = document.getElementById("phuttren_bedroom").value;
                    console.log("textphuttren: "+selectphuttren);

					if(selectgiotren == selectgioduoi)
                    	{
						
                         if(selectphuttren > selectphutduoi)
                        {
                            
							 var DataSend = "{\"Light_bedroom_timer\":\"1\",\"Mode_off\":\"1\",\"GD\":"+selectgioduoi+",\"PD\":"+selectphutduoi+",\"GT\":"+selectgiotren+",\"PT\":"+selectphuttren+"}";
							
							console.log("txden: "+DataSend);	
							
							mqttClient.send(topicpub_bedroom, DataSend);
													
                        }
                        else if(selectphuttren <= selectphutduoi)
                        {
                            alert("Vui lòng chọn phút dưới nhỏ hơn phút trên");
                             return;
                        }
                      
                    }
                    else if(selectgiotren > selectgioduoi)
                    {
                      var DataSend = "{\"Light_bedroom_timer\":\"1\",\"Mode_off\":\"1\",\"GD\":"+selectgioduoi+",\"PD\":"+selectphutduoi+",\"GT\":"+selectgiotren+",\"PT\":"+selectphuttren+"}";
							
							console.log("txden: "+DataSend);	
							
							mqttClient.send(topicpub_bedroom, DataSend);
								
                    }
                    else if(selectgiotren < selectgioduoi)
                    {
						
                          alert("Vui lòng chọn giờ dưới lớn hơn hoặc bằng giờ trên");
                         return;
                    }

				}
			}
      
		}     
					 
}

function TIMER_OFF_livingroom()
{
		var DataSend = "{\"Light_livingroom_timer\":\"0\"}";
	
	    mqttClient.send(topicpub_livingroom, DataSend);
		console.log("Button tắt hẹn Giờ Đèn livingroom!!!"); 
}

function TIMER_OFF_kitchen()
{
		var DataSend = "{\"Light_kitchen_timer\":\"0\"}";
	
	    mqttClient.send(topicpub_kitchen, DataSend);
		console.log("Button tắt hẹn Giờ Đèn Kitchen!!!"); 
}

function TIMER_OFF_bedroom()
{
		var DataSend = "{\"Light_bedroom_timer\":\"0\"}";
	
	    mqttClient.send(topicpub_bedroom, DataSend);
		console.log("Button tắt hẹn Giờ Đèn Bedroom!!!"); 
}


$(document).ready(function()
{
    $('.tab-content-item').hide();
    $('.tab-content-item:first-child').fadeIn();
    $('.nav-tabs li').click(function(){
        $('.nav-tabs li').removeClass('active');
        $(this).addClass('active');

        let id_tab_content = $(this).children('a').attr('href');
        $('.tab-content-item').hide();
        $(id_tab_content).fadeIn();
    });
});
</script>



</html>