<?php	
	session_start();
	if( !isset($_SESSION['username']))
	{
		header("location:login.php");
		die();
	}
?>

<?php 

include 'print_data.php';
include 'sql.php';

$conn = ConnectDatabse();

?>

<!DOCTYPE html>

<html>

<head>
	<meta charset="utf-8"> 
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
</head>

<style>
body, html {
  margin: auto;
  font-family: Arial;
  background: gainsboro; 
  text-align: center;
}	
	
.title{
	margin: auto;
	text-align: center;
	font-size: 50px;
	min-width: 480px;
	max-width: 1920px;
	width: 100%;
	background-color: #50B8B4;
	overflow: hidden;
}

i{
	font-size: 30px;
}

.tablink {
  background-color: #555;
  color: white;
  display: border-box;
  border: none;
  outline: none;
  cursor: pointer;
  font-size: 17px;
  width: 15%; 
}

.tablink:hover {
  background-color: #777;
}

.tabcontent {
  display: none;
  padding: 10px 10px;
  height: 100%;
}

.container {margin: 20px auto; display: inline-block;}
.card {
	background-color: white; 
	box-shadow: 2px 2px 12px 1px rgba(140,140,140,.5);
	border-radius: 20px; 
}
.cards { 
	padding: 20px; margin: 20px auto;
	width: 300px; 
	height: 300px;
	line-height: 53px; 	  
	display: inline-block; 
}
.reading { font-size: 2rem; }
.timestamp { color: #bebebe; font-size: 1rem; }
.card-title{ font-size: 1.2rem; font-weight : bold; }
.card.temperature { color: #B10F2E; }
.card.humidity { color: #50B8B4; }
	
/* .card { 
	background-color: white; 
	box-shadow: 2px 2px 12px 1px rgba(140,140,140,.5); 
	text-align: center;	
	
}

.cards { 
		width: 300px; 
		height: 300px;
		line-height: 53px;	
		display: inline-block;		
}

    .reading { font-size: 1.5rem; }
	
    .timestamp { color: #A9A9A9; font-size: 1rem; }
	
    .card-title{ 	
	font-size: 1rem; 
	font-weight : bold; 
	padding: auto;
	}
	
    .card.temperature { 
	
	color: #B10F2E; 
	}
	
    .card.humidity { 
	color: #50B8B4; 
	}

	.card.fingerprint { 
	color: darkblue; 
	} */


.content-led {padding: 20px; text-align: center;}

.card-grid-led {  
	max-width: 800px;  
	margin: 0 auto;  
	display: grid;  
	grid-gap: 2rem;  
	grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
}
.card-led {  
	background-color: white;  
	box-shadow: 2px 2px 12px 1px rgba(140,140,140,.5);
	border-radius: 20px;
	padding: 20px;
}

.card-title-led {  
	font-size: 1.2rem;  
	font-weight: bold;  
	color: #034078
}

table.rounded-corners {
  border-spacing: 0;
  border-collapse: separate;
  border-radius: 10px;
}

table.rounded-corners th, table.rounded-corners td {
  border: 1px solid black;
}

.button1 {
  display: inline-block;
  padding: 15px 25px;
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

     height: 25px;
     width: 270px;
     font-size: 20px;
     margin: 10px auto;
 }

</style>




<body onload ="timedate()">


<div>
</div>

<h1 class="title">ESP8266</h1>
<div class="wrapper" align="center">
         <nav>
			<button class="tablink" onclick="openPage('Home', this, 'red')" id="defaultOpen" >Home</button>
			<button class="tablink" onclick="openPage('Setting', this, 'green')">Setting</button>
			<button class="tablink" onclick="openPage('Sensors', this, 'blue')">Sensors</button>
			<button class="tablink" onclick="openPage('Control', this, 'orange')">Control</button>
			<button class="tablink" onclick="window.open('new 1.php', '_blank');">Database</button>
         </nav>
</div>
<!--<div class="btn">
	<button class="tablink" onclick="openPage('Home', this, 'red')" id="defaultOpen" >Home</button>
	<button class="tablink" onclick="openPage('Setting', this, 'green')">Setting</button>
	<button class="tablink" onclick="openPage('Sensors', this, 'blue')">Sensors</button>
	<button class="tablink" onclick="openPage('Control', this, 'orange')">Control</button>
	<button class="tablink" onclick="openPage('table', this, 'Pink')">Database</button>
</div> -->





<div id="Home" class="tabcontent">

	<div>
		<h1>Date: <label id="date"></lable></h1>
	</div>
	  
	<div>
		<h1>Time: <label id="time"></lable></h1>
	</div>
	<h1><p class="logout">Welcome to <b><?php echo $_SESSION['username'] ?>'s house</b> </p></h1>
	<a href="logout.php" title="logout" style="font-size:30px;">Logout</a>
</div>


<div id="Setting" class="tabcontent">
	<div class="dashboard container">
			<table width="1000" height="600" border="6" cellpadding="10" align="center" >	 
			 <tr>
				  <th bgcolor="#E5E4E2"  colspan="5"> <center> <font size="10"> <span style = "color: black"> MQTT SERVER SETTINGS</span></font></center></th>
			 </tr>	 
			 <tr>
				<td bgcolor="white" colspan="1"> <center> <span style = "color: Blue; font-size:20px"> <b>  SERVER </b>  </span> </center> </td>
				<td bgcolor="#B041FF" colspan="4" align="center" >  
						<input type = "text" id="input_server" value = "ngoinhaiot.com">
				</td>
						
			 </tr>
			 
			 <tr>
				<td bgcolor="white" colspan="1"> <center> <span style = "color: Blue; font-size:20px"> <b>  PORT </b>  </span> </center> </td>
				<td bgcolor="#B041FF" colspan="4" align="center">  
					  <input type = "text" id="input_port" value =2222>	  
				</td>
						
			 </tr>
			 <tr>
				<td bgcolor="white" colspan="1"> <center> <span style = "color: Blue; font-size:20px"> <b>  USERNAME </b>  </span> </center> </td>
				<td bgcolor="#B041FF" colspan="4" align="center" >  
						<input type = "text" id="input_username" value = "thanhtu54">
				</td>
						
			 </tr>
			 <tr>
				<td bgcolor="white" colspan="1"> <center> <span style = "color: Blue; font-size:20px"> <b>  PASSWORD </b>  </span> </center> </td>
				<td bgcolor="#B041FF" colspan="4" align="center" >  
						<input type = "password" id="input_password" value = "BD6085B020774167">
				</td>
						
			 </tr>	
			 			 
			 <tr>
				<td bgcolor="white" colspan="1"> <center> <span style = "color: Blue; font-size:20px"> <b>  CONFIRM </b>  </span> </center> </td>
				<td bgcolor="#B041FF" colspan="4" align="center" >  
						<br>
						<button class="button1" onclick="SETSERVER()" id="btn_connect">Connect</button>
						<button class="button1" onclick="DISSERVER()" id="btn_connect">Disconnect</button>
						<br>
						<br>
				</td>
						
			 </tr>	
			 
			 <tr>
				<td bgcolor="white" colspan="1"> <center> <span style = "color: Blue; font-size:20px"> <b>  CONNECTION NOTICE </b>  </span> </center> </td>
				<td bgcolor="#B041FF" colspan="4" align="center" >  
						<br>
					     <b><label id="thongbao" style = "font-size:30px">Disconnected</label></b>
						<br>
						<br>
				</td>
						
			 </tr>
			
			 </table>    
        </div>
</div>

<div id="Sensors" class="tabcontent" align="center">
<div class="container">

	<div class="cards livingroom">
	<h3> LIVING ROOM </h3>
	  	<div class="card temperature">
			<p class="card-title"><i class="fas fa-thermometer-half"></i> TEMPERATURE</p>
			<p><span class="reading"><span id="t1"></span> &deg;C</span></p>
			<p class="timestamp">Last Reading: <span id="rt1"></span></p>
      	</div>
      	<div class="card humidity">
			<p class="card-title"><i class="fas fa-tint"></i> HUMIDITY</p>
			<p><span class="reading"><span id="h1"></span> &percnt;</span></p>
			<p class="timestamp">Last Reading: <span id="rh1"></span></p>
		</div>
	</div>

	
	<div class="cards kitchen">
	<h3> KITCHEN </h3>
	  	<div class="card temperature">
			<p class="card-title"><i class="fas fa-thermometer-half"></i> TEMPERATURE</p>
			<p><span class="reading"><span id="t1"></span> &deg;C</span></p>
			<p class="timestamp">Last Reading: <span id="rt1"></span></p>
      	</div>
      	<div class="card humidity">
			<p class="card-title"><i class="fas fa-tint"></i> HUMIDITY</p>
			<p><span class="reading"><span id="h1"></span> &percnt;</span></p>
			<p class="timestamp">Last Reading: <span id="rh1"></span></p>
		</div>
	</div>

	<div class="cards bedroom">
	<h3> BEDROOM </h3>
	  	<div class="card temperature">
			<p class="card-title"><i class="fas fa-thermometer-half"></i> TEMPERATURE</p>
			<p><span class="reading"><span id="t2"></span> &deg;C</span></p>
			<p class="timestamp">Last Reading: <span id="rt2"></span></p>
      	</div>
      	<div class="card humidity">
			<p class="card-title"><i class="fas fa-tint"></i> HUMIDITY</p>
			<p><span class="reading"><span id="h2"></span> &percnt;</span></p>
			<p class="timestamp">Last Reading: <span id="rh2"></span></p>
		</div>
	</div>	
</div>

<!-- <div class="card fingerprint">
	<p class="card-title"> <i class="fa-solid fa-fingerprint"></i>FINGERPRINT </p>
	<p><b><span id="vantay" class="reading"></span></b></p>
	<p><span id="ID" class="reading"></span></p>
	<br>
</div>  -->
	
</div>


<div id="Control" class="tabcontent">

<!--<button  class="abcd" onclick="DK_DEN()">ĐÈN</button> -->

<!-- <label class="switch">
<input type="checkbox" id="ESP32_01_TogLED_01" onclick="GetTogBtnLEDState('ESP32_01_TogLED_01')">
<div class="sliderTS"></div>
</label> -->

<!--<button onclick = "DK_DEN_ON()">ON</button>
<button onclick = "DK_DEN_OFF()">OFF</button> -->


<div class="content-led"> 
	 
	<div class="card-grid-led"> 

		<div class="card-led">
		<h1>Light living room </h1>                 
			<p>State: <strong ><img style="width: 50px; height: 60px;" id="trangthaiden" src="images/loff.jpg"></strong></p>        
			<button onclick = "DK_DEN_ON()">ON</button>          
			<button onclick = "DK_DEN_OFF()">OFF</button>        
		    </p> 
			
			<p> Timer state: <label id="henden">OFF</label> </p> 
			<select id="chon"  style="width: 60px; height: 40px;">		
	
			<option value = "1" >L1</option>
			<option value = "2" >L2</option>
							
			</select>

			<select id="chon_onoff"  style="width: 60px; height: 40px;">

			<option value = "1" >ON</option>
			<option value = "2" >OFF</option>

			</select>

		<select id="gioduoi"  style="width: 50px; height: 40px;">
		   <option value = "00" >00</option>
			<option value = "01" >01</option>
			<option value = "02" >02</option>
			<option value = "03" >03</option>
			<option value = "04" >04</option>
			<option value = "05" >05</option>
			<option value = "06" >06</option>
			<option value = "07" >07</option>
			<option value = "08" >08</option>
			<option value = "09" >09</option>
			<option value = "10" >10</option>
			<option value = "11" >11</option>
			<option value = "12" >12</option>
			<option value = "13" >13</option>
			<option value = "14" >14</option>
			<option value = "15" >15</option>
			<option value = "16" >16</option>
			<option value = "17" >17</option>
			<option value = "18" >18</option>
			<option value = "19" >19</option>
			<option value = "20" >20</option>
			<option value = "21" >21</option>
			<option value = "22" >22</option>
			<option value = "23" >23</option>
		</select>
		 <label>:</label>
		<select id="phutduoi" style="width: 50px; height: 40px;">
				<option value = "00" >00</option>
			<option value = "01" >01</option>
			<option value = "02" >02</option>
			<option value = "03" >03</option>
			<option value = "04" >04</option>
			<option value = "05" >05</option>
			<option value = "06" >06</option>
			<option value = "07" >07</option>
			<option value = "08" >08</option>
			<option value = "09" >09</option>
			<option value = "10" >10</option>
			<option value = "11" >11</option>
			<option value = "12" >12</option>
			<option value = "13" >13</option>
			<option value = "14" >14</option>
			<option value = "15" >15</option>
			<option value = "16" >16</option>
			<option value = "17" >17</option>
			<option value = "18" >18</option>
			<option value = "19" >19</option>
			<option value = "20" >20</option>
			<option value = "21" >21</option>
			<option value = "22" >22</option>
			<option value = "23" >23</option>
			<option value = "24" >24</option>
			<option value = "25" >25</option>
			<option value = "26" >26</option>
			<option value = "27" >27</option>
			<option value = "28" >28</option>
			<option value = "29" >29</option>
			<option value = "30" >30</option>
			<option value = "31" >31</option>
			<option value = "32" >32</option>
			<option value = "33" >33</option>
			<option value = "34" >34</option>
			<option value = "35" >35</option>
			<option value = "36" >36</option>
			<option value = "37" >37</option>
			<option value = "38" >38</option>
			<option value = "39" >39</option>
			<option value = "40" >40</option>
			<option value = "41" >41</option>
			<option value = "42" >42</option>
			<option value = "43" >43</option>
			<option value = "44" >44</option>
			<option value = "45" >45</option>
			<option value = "46" >46</option>
			<option value = "47" >47</option>
			<option value = "48" >48</option>
			<option value = "49" >49</option>
			<option value = "50" >50</option>
			<option value = "51" >51</option>
			<option value = "52" >52</option>
			<option value = "53" >53</option>
			<option value = "54" >54</option>
			<option value = "55" >55</option>
			<option value = "56" >56</option>
			<option value = "57" >57</option>
			<option value = "58" >58</option>
			<option value = "59" >59</option>
		</select>
	   

		<label>-</label>
		  <select id="giotren"  style="width: 50px; height: 40px;">
			<option value = "00" >00</option>
			<option value = "01" >01</option>
			<option value = "02" >02</option>
			<option value = "03" >03</option>
			<option value = "04" >04</option>
			<option value = "05" >05</option>
			<option value = "06" >06</option>
			<option value = "07" >07</option>
			<option value = "08" >08</option>
			<option value = "09" >09</option>
			<option value = "10" >10</option>
			<option value = "11" >11</option>
			<option value = "12" >12</option>
			<option value = "13" >13</option>
			<option value = "14" >14</option>
			<option value = "15" >15</option>
			<option value = "16" >16</option>
			<option value = "17" >17</option>
			<option value = "18" >18</option>
			<option value = "19" >19</option>
			<option value = "20" >20</option>
			<option value = "21" >21</option>
			<option value = "22" >22</option>
			<option value = "23" >23</option>
		</select>
		 <label>:</label>
		<select id="phuttren" style="width: 50px; height: 40px;">
			<option value = "00" >00</option>
			<option value = "01" >01</option>
			<option value = "02" >02</option>
			<option value = "03" >03</option>
			<option value = "04" >04</option>
			<option value = "05" >05</option>
			<option value = "06" >06</option>
			<option value = "07" >07</option>
			<option value = "08" >08</option>
			<option value = "09" >09</option>
			<option value = "10" >10</option>
			<option value = "11" >11</option>
			<option value = "12" >12</option>
			<option value = "13" >13</option>
			<option value = "14" >14</option>
			<option value = "15" >15</option>
			<option value = "16" >16</option>
			<option value = "17" >17</option>
			<option value = "18" >18</option>
			<option value = "19" >19</option>
			<option value = "20" >20</option>
			<option value = "21" >21</option>
			<option value = "22" >22</option>
			<option value = "23" >23</option>
			<option value = "24" >24</option>
			<option value = "25" >25</option>
			<option value = "26" >26</option>
			<option value = "27" >27</option>
			<option value = "28" >28</option>
			<option value = "29" >29</option>
			<option value = "30" >30</option>
			<option value = "31" >31</option>
			<option value = "32" >32</option>
			<option value = "33" >33</option>
			<option value = "34" >34</option>
			<option value = "35" >35</option>
			<option value = "36" >36</option>
			<option value = "37" >37</option>
			<option value = "38" >38</option>
			<option value = "39" >39</option>
			<option value = "40" >40</option>
			<option value = "41" >41</option>
			<option value = "42" >42</option>
			<option value = "43" >43</option>
			<option value = "44" >44</option>
			<option value = "45" >45</option>
			<option value = "46" >46</option>
			<option value = "47" >47</option>
			<option value = "48" >48</option>
			<option value = "49" >49</option>
			<option value = "50" >50</option>
			<option value = "51" >51</option>
			<option value = "52" >52</option>
			<option value = "53" >53</option>
			<option value = "54" >54</option>
			<option value = "55" >55</option>
			<option value = "56" >56</option>
			<option value = "57" >57</option>
			<option value = "58" >58</option>
			<option value = "59" >59</option>
		</select>
			<button onclick="hengio()">Send hẹn giờ</button>
					
			<button onclick="tathengioden()">Tắt hẹn giờ đèn </button>
					
			<button onclick="tathengioquat()">Tắt hẹn giờ quạt</button>

		</div>   
	</div>  
</div>

<div class="content-led"> 
	 
	<div class="card-grid-led"> 

		<div class="card-led">
		<h1>Light bedroom </h1>                 
			<p>State: <strong ><img style="width: 50px; height: 60px;" id="trangthaiden" src="images/loff.jpg"></strong></p>        
			<button onclick = "DK_DEN_ON()">ON</button>          
			<button onclick = "DK_DEN_OFF()">OFF</button>        
		    </p>      
		</div>   
	</div>  
</div>
<h1>
Light 2
<!-- <button class="btn ash-grey" onclick="DK_QUAT()">QUẠT</button> -->
<!-- <label class="switch">
    <input type="checkbox" id="ESP32_01_TogLED_02" onclick="GetTogBtnLEDState('ESP32_01_TogLED_02')">
    <div class="sliderTS"></div>
</label> -->
<button onclick = "DK_QUAT_ON()">ON</button>
<button onclick = "DK_QUAT_OFF()">OFF</button>
</h1>

<select id="chon"  style="width: 60px; height: 40px;">		
	
							<option value = "1" >L1</option>
							<option value = "2" >L2</option>
													
</select>

<select id="chon_onoff"  style="width: 60px; height: 40px;">
     
  <option value = "1" >ON</option>
  <option value = "2" >OFF</option>
              
</select>

</select>
	
		
		
								<select id="gioduoi"  style="width: 50px; height: 40px;">
                                   <option value = "00" >00</option>
                                    <option value = "01" >01</option>
                                    <option value = "02" >02</option>
                                    <option value = "03" >03</option>
                                    <option value = "04" >04</option>
                                    <option value = "05" >05</option>
                                    <option value = "06" >06</option>
                                    <option value = "07" >07</option>
                                    <option value = "08" >08</option>
                                    <option value = "09" >09</option>
                                    <option value = "10" >10</option>
                                    <option value = "11" >11</option>
                                    <option value = "12" >12</option>
                                    <option value = "13" >13</option>
                                    <option value = "14" >14</option>
                                    <option value = "15" >15</option>
                                    <option value = "16" >16</option>
                                    <option value = "17" >17</option>
                                    <option value = "18" >18</option>
                                    <option value = "19" >19</option>
                                    <option value = "20" >20</option>
                                    <option value = "21" >21</option>
                                    <option value = "22" >22</option>
                                    <option value = "23" >23</option>
								</select>
                                 <label>:</label>
                                <select id="phutduoi" style="width: 50px; height: 40px;">
										<option value = "00" >00</option>
                                    <option value = "01" >01</option>
                                    <option value = "02" >02</option>
                                    <option value = "03" >03</option>
                                    <option value = "04" >04</option>
                                    <option value = "05" >05</option>
                                    <option value = "06" >06</option>
                                    <option value = "07" >07</option>
                                    <option value = "08" >08</option>
                                    <option value = "09" >09</option>
                                    <option value = "10" >10</option>
                                    <option value = "11" >11</option>
                                    <option value = "12" >12</option>
                                    <option value = "13" >13</option>
                                    <option value = "14" >14</option>
                                    <option value = "15" >15</option>
                                    <option value = "16" >16</option>
                                    <option value = "17" >17</option>
                                    <option value = "18" >18</option>
                                    <option value = "19" >19</option>
                                    <option value = "20" >20</option>
                                    <option value = "21" >21</option>
                                    <option value = "22" >22</option>
                                    <option value = "23" >23</option>
                                    <option value = "24" >24</option>
                                    <option value = "25" >25</option>
                                    <option value = "26" >26</option>
                                    <option value = "27" >27</option>
                                    <option value = "28" >28</option>
                                    <option value = "29" >29</option>
                                    <option value = "30" >30</option>
                                    <option value = "31" >31</option>
                                    <option value = "32" >32</option>
                                    <option value = "33" >33</option>
                                    <option value = "34" >34</option>
                                    <option value = "35" >35</option>
                                    <option value = "36" >36</option>
                                    <option value = "37" >37</option>
                                    <option value = "38" >38</option>
                                    <option value = "39" >39</option>
                                    <option value = "40" >40</option>
                                    <option value = "41" >41</option>
                                    <option value = "42" >42</option>
                                    <option value = "43" >43</option>
                                    <option value = "44" >44</option>
                                    <option value = "45" >45</option>
                                    <option value = "46" >46</option>
                                    <option value = "47" >47</option>
                                    <option value = "48" >48</option>
                                    <option value = "49" >49</option>
                                    <option value = "50" >50</option>
                                    <option value = "51" >51</option>
                                    <option value = "52" >52</option>
                                    <option value = "53" >53</option>
                                    <option value = "54" >54</option>
                                    <option value = "55" >55</option>
                                    <option value = "56" >56</option>
                                    <option value = "57" >57</option>
                                    <option value = "58" >58</option>
                                    <option value = "59" >59</option>
                                </select>
                               

								<label>-</label>
                                  <select id="giotren"  style="width: 50px; height: 40px;">
									<option value = "00" >00</option>
                                    <option value = "01" >01</option>
                                    <option value = "02" >02</option>
                                    <option value = "03" >03</option>
                                    <option value = "04" >04</option>
                                    <option value = "05" >05</option>
                                    <option value = "06" >06</option>
                                    <option value = "07" >07</option>
                                    <option value = "08" >08</option>
                                    <option value = "09" >09</option>
                                    <option value = "10" >10</option>
                                    <option value = "11" >11</option>
                                    <option value = "12" >12</option>
                                    <option value = "13" >13</option>
                                    <option value = "14" >14</option>
                                    <option value = "15" >15</option>
                                    <option value = "16" >16</option>
                                    <option value = "17" >17</option>
                                    <option value = "18" >18</option>
                                    <option value = "19" >19</option>
                                    <option value = "20" >20</option>
                                    <option value = "21" >21</option>
                                    <option value = "22" >22</option>
                                    <option value = "23" >23</option>
                                </select>
                                 <label>:</label>
                                <select id="phuttren" style="width: 50px; height: 40px;">
                                    <option value = "00" >00</option>
                                    <option value = "01" >01</option>
                                    <option value = "02" >02</option>
                                    <option value = "03" >03</option>
                                    <option value = "04" >04</option>
                                    <option value = "05" >05</option>
                                    <option value = "06" >06</option>
                                    <option value = "07" >07</option>
                                    <option value = "08" >08</option>
                                    <option value = "09" >09</option>
                                    <option value = "10" >10</option>
                                    <option value = "11" >11</option>
                                    <option value = "12" >12</option>
                                    <option value = "13" >13</option>
                                    <option value = "14" >14</option>
                                    <option value = "15" >15</option>
                                    <option value = "16" >16</option>
                                    <option value = "17" >17</option>
                                    <option value = "18" >18</option>
                                    <option value = "19" >19</option>
                                    <option value = "20" >20</option>
                                    <option value = "21" >21</option>
                                    <option value = "22" >22</option>
                                    <option value = "23" >23</option>
                                    <option value = "24" >24</option>
                                    <option value = "25" >25</option>
                                    <option value = "26" >26</option>
                                    <option value = "27" >27</option>
                                    <option value = "28" >28</option>
                                    <option value = "29" >29</option>
                                    <option value = "30" >30</option>
                                    <option value = "31" >31</option>
                                    <option value = "32" >32</option>
                                    <option value = "33" >33</option>
                                    <option value = "34" >34</option>
                                    <option value = "35" >35</option>
                                    <option value = "36" >36</option>
                                    <option value = "37" >37</option>
                                    <option value = "38" >38</option>
                                    <option value = "39" >39</option>
                                    <option value = "40" >40</option>
                                    <option value = "41" >41</option>
                                    <option value = "42" >42</option>
                                    <option value = "43" >43</option>
                                    <option value = "44" >44</option>
                                    <option value = "45" >45</option>
                                    <option value = "46" >46</option>
                                    <option value = "47" >47</option>
                                    <option value = "48" >48</option>
                                    <option value = "49" >49</option>
                                    <option value = "50" >50</option>
                                    <option value = "51" >51</option>
                                    <option value = "52" >52</option>
                                    <option value = "53" >53</option>
                                    <option value = "54" >54</option>
                                    <option value = "55" >55</option>
                                    <option value = "56" >56</option>
                                    <option value = "57" >57</option>
                                    <option value = "58" >58</option>
                                    <option value = "59" >59</option>
                                </select>
<button onclick="hengio()">Send hẹn giờ</button>
								
<button onclick="tathengioden()">Tắt hẹn giờ đèn </button>
								
<button onclick="tathengioquat()">Tắt hẹn giờ quạt</button>

<h1>
Light 1 status:
<img id="trangthaiden" src="images/off.jpg">
</h1>


<h1>
Light 2 status:
<img id="trangthaiquat" src="images/off.jpg">
</h1>


<h1>
Timer Light 1:
<label id="henden">OFF</label>
</h1>


<h1>
Timer Light 2:
<label id="henquat">OFF</label>
</h1>

<h1>
Number of saved fingerprints: 
<label id="number_FP"></label>
</h1>


<h1>
Enroll:
<label id="enroll"></label>
<br>
<input id="text_ID" placeholder="Enter your ID"></input>
<input id="text_name" placeholder="Enter your name"></input>
<button onclick="Enroll()">Enroll</button>
</h1>

<h1>
Delete: <span id = "del_ID"></span>
<br>
<input id="text_ID_del" placeholder="Enter your ID"></input>
<button onclick="Delete()">Delete</button>
</h1>

<h1>
Empty data:
<button onclick="Empty()">Empty</button>
</h1>
</div>

<div id="table"  class="tabcontent">

</div>
</body>

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
		table();
		setInterval(function(){table();}, 2000);
		
	}
	
}

function table(){
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function(){
          document.getElementById("table").innerHTML = this.responseText;
        }
        xhttp.open("GET", "new 1.php");
        xhttp.send();		
}



// topic nhận dữ liệu => esp gửi dữ liệu ở topic nào để web subscribe
var topicpub = "thanhtu54/receive";
		// topic ESP nhận dữ liệu
var topicsub = "thanhtu54/send";

var topicpub_bedroom = "thanhtu54/receive";
		// topic ESP nhận dữ liệu
var topicsub_bedroom = "thanhtu54/send";

var topicpub_fingerprint = "thanhtu54/receive_fingerprint";
		// topic ESP nhận dữ liệu
var topicsub_fingerprint = "thanhtu54/send_fingerprint";

function ConnectMQTT()
{
		//var hostname = "mqtt.ngoinhaiot.com";
		// kết nối  port websockets
		//var port = 2222;

		//var user = "toannv10291";
		//var pass = "toannv10291";
		
		
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
		var topicpub1 = "thanhtu54/receive";
		// topic ESP nhận dữ liệu
		var topicsub1 = "thanhtu54/send";

		var topicpub2 = "thanhtu54/receive_bedroom";
		// topic ESP nhận dữ liệu
		var topicsub2 = "thanhtu54/send_bedroom";

		var topicpub3 = "thanhtu54/receive_fingerprint";
		// topic ESP nhận dữ liệu
		var topicsub3 = "thanhtu54/send_fingerprint";
		// khai báo 
		mqttClient = new Paho.MQTT.Client(hostname, parseInt(port), clientId);

		// hàm đọc dữ liệu
		mqttClient.onMessageArrived = MessageArrived;

		// hàm check lỗi
		mqttClient.onConnectionLost = ConnectionLost;

		// connect
		Connect();

		/*

		*/

		/*Initiates a connection to the MQTT broker*/
		function Connect(){
			mqttClient.connect({
			useSSL: false,
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
			alert('Connected to MQTT Broker ngoinhaiot.com');
			document.getElementById("thongbao").innerHTML = "Connected";						
		}

		/*Callback for failed connection*/
		function ConnectionFailed(res) {
			console.log("Connect failed:" + res.errorMessage);
			alert('Unable to connect please check server parameters');
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
	   		var  DataVDK = message.payloadString;
			  
	   		var DataJson = JSON.parse(DataVDK); 
						   
/* 			if(DataJson.ND != null)
			{
				document.getElementById("t1").innerHTML = DataJson.ND;
				document.getElementById("rt1").innerHTML = getDateTime();
			}
			
			if(DataJson.DA != null)
			{
				document.getElementById("h1").innerHTML = DataJson.DA;
				document.getElementById("rh1").innerHTML = getDateTime();
			} */

			if(DataJson.ND_bedroom != null)
			{
				document.getElementById("t2").innerHTML = DataJson.ND_bedroom;
				document.getElementById("rt2").innerHTML = getDateTime();
			}
			
			if(DataJson.DA_bedroom != null)
			{
				document.getElementById("h2").innerHTML = DataJson.DA_bedroom;
				document.getElementById("rh2").innerHTML = getDateTime();
			}
			
			
/* 			if(DataJson.ID != null || DataJson.Name != null)
			{
				if(DataJson.ID == -2)
				{
					document.getElementById("vantay").innerHTML = "Enrolling !";
					document.getElementById("ID").innerHTML = "";
				}      
				else if(DataJson.ID == -1)
				{
					document.getElementById("vantay").innerHTML = "Scanning";
					document.getElementById("ID").innerHTML = "";
				}
				else if(DataJson.ID == 0)
				{
					
					document.getElementById("vantay").innerHTML = "Not Valid Fingerprint";
					document.getElementById("ID").innerHTML = "";
				}
				else
				{
					document.getElementById("vantay").innerHTML = "Welcome " + DataJson.Name + "!" ;
					document.getElementById("ID").innerHTML = "(#ID: " + DataJson.ID + ")";
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
					var ID_del = DataJson.saved_fingerprint - 1;
					document.getElementById("number_FP").innerHTML = DataJson.saved_fingerprint;
					if(ID_del > 0)
					{
						document.getElementById("del_ID").innerHTML = "Deleted successfully";
					}
										
				}
					
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
					document.getElementById("enroll").innerHTML = "#ID " + DataJson.ID_enroll + " stored !";
				}
				
			}
	  
			if(DataJson.TB1 != null)
			{
				//document.getElementById("cai3").innerHTML = DataJson.C3;
				
				if(DataJson.TB1 == 0)
				{				
					document.getElementById("trangthaiden").src = "images/off.jpg";
					
				
				}
				else if(DataJson.TB1 == 1)
				{
										
					document.getElementById("trangthaiden").src = "images/on.jpg";					
				}
				
			}
						
			if(DataJson.TB2 != null)
			{
				//document.getElementById("cai3").innerHTML = DataJson.C3;
				
				if(DataJson.TB2 == 0)
				{
					document.getElementById("trangthaiquat").src = "images/off.jpg";
				}
				else if(DataJson.TB2 == 1)
				{
					document.getElementById("trangthaiquat").src = "images/on.jpg";
				}
				
			}
			
			
			if(DataJson.H1 != null)
			{
				
				
				if(DataJson.H1 == 0)
				{
					document.getElementById("henden").innerHTML = "OFF";
				}
				else if(DataJson.H1 == 1)
				{
					document.getElementById("henden").innerHTML = "ON";
				}
				
			}
			
			if(DataJson.H2 != null)
			{
				
				
				if(DataJson.H2 == 0)
				{
					document.getElementById("henquat").innerHTML = "OFF";
				}
				else if(DataJson.H2 == 1)
				{
					document.getElementById("henquat").innerHTML = "ON";
				}
				
				
			} */
		}
}

function DisconnectMQTT()
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
		var topicpub1 = "thanhtu54/receive";
		// topic ESP nhận dữ liệu
		var topicsub1 = "thanhtu54/send";
		// khai báo 
		mqttClient = new Paho.MQTT.Client(hostname, parseInt(port), clientId);
		
		disconnect();
		
		function disconnect()
		{
			mqttClient.disconnect({
			useSSL: false,
			userName: user,
			password: pass,
			keepAliveInterval: 10,
			});	
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


function DK_DEN_ON()
{
	console.log("Button đèn onclick!!!");
 
 const image = document.getElementById("trangthaiden");
 var DataSend = "{\"TB1\":\"1\"}";
 mqttClient.send(topicpub, DataSend);

  
}

function DK_DEN_OFF()
{  
  console.log("Button đèn onclick!!!");
 
  const image = document.getElementById("trangthaiden");
  var DataSend = "{\"TB1\":\"0\"}";
  mqttClient.send(topicpub, DataSend);
}



function DK_QUAT_ON()
{
	console.log("Button quạt onclick!!!");
	
 
    const image = document.getElementById("trangthaiquat");
    var DataSend = "{\"TB2\":\"1\"}";
     mqttClient.send(topicpub, DataSend);
}

function DK_QUAT_OFF()
{
	console.log("Button quạt onclick!!!");
	
 
     const image = document.getElementById("trangthaiquat");
    var DataSend = "{\"TB2\":\"0\"}";
       mqttClient.send(topicpub, DataSend);
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
  
  var DataSend = "{\"Empty\":\"1\"}";
  //mqttClient.send(topicpub, DataSend);
  mqttClient.send(topicpub_fingerprint, DataSend);
  if(dataID == 1)
  {
	alert("Sensor doesn't contain any fingerprint data.");
  }
}

function hengio()
{
	var selectchon = document.getElementById("chon");
    var chon = selectchon.options[selectchon.selectedIndex].value;
	
	var select_onoff = document.getElementById("chon_onoff");
	var onoff = select_onoff.options[select_onoff.selectedIndex].value;
  
	var trangthaihengioden = document.getElementById("henden").innerHTML;
	var trangthaiden_onoff = document.getElementById("trangthaiden");

	var trangthaihengioquat = document.getElementById("henquat").innerHTML;
	var trangthaiquat_onoff = document.getElementById("trangthaiquat");
	
	if(chon == "1")
	{
		if(onoff == "1")
		{     
			console.log("Chọn hẹn giờ ON đèn !!");
			if(trangthaiden_onoff.src.match("images/on.jpg"))
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
					var DataSend = "{\"Mode_on_den\":\"1\"\}";
					mqttClient.send(topicpub, DataSend);
					HenDen();
				}		
			}
		}
		else if(onoff == "2")
		{      
			console.log("Chọn hẹn giờ OFF đèn !!");
			if(trangthaiden_onoff.src.match("images/off.jpg"))
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
					var DataSend = "{\"Mode_off_den\":\"1\"\}";
					mqttClient.send(topicpub, DataSend);  
					HenDen();
				}
			}
      
		}     
	}
	else if(chon == "2")
	{
		if(onoff == "1")
		{     
			console.log("Chọn hẹn giờ ON quạt !!");
			if(trangthaiquat_onoff.src.match("images/on.jpg"))
			{
				alert("Quạt của bạn đang bật, vui lòng chọn chế độ hẹn giờ OFF quạt!!!");
				return;
			}
			else
			{
				if(trangthaihengioquat == "ON")
				{
				  alert("Tắt hẹn giờ đi rùi cài đặt nha!!!");
						return;
				}
				else if(trangthaihengioquat == "OFF")    
				{       
					var DataSend = "{\"Mode_on_quat\":\"1\"\}";
					mqttClient.send(topicpub, DataSend);
					HenQuat();
				}		
			}
		}
		else if(onoff == "2")
		{      
			console.log("Chọn hẹn giờ OFF đèn !!");
			if(trangthaiquat_onoff.src.match("images/off.jpg"))
			{
				alert("Quạt của bạn đang tắt, vui lòng chọn chế độ hẹn giờ ON quat!!!");
				return;
			}
			else
			{
				if(trangthaihengioquat == "ON")
				{
					alert("Tắt hẹn giờ đi rùi cài đặt nha!!!");
					return;
				}
				else if(trangthaihengioquat == "OFF")    
				{     
					var DataSend = "{\"Mode_off_quat\":\"1\"\}";
					mqttClient.send(topicpub, DataSend);  
					HenQuat();
				}
			}
      
		} 		        
	}					 
}

function HenDen()
{
					var selectgioduoi = document.getElementById("gioduoi");
                    var textgioduoi = selectgioduoi.options[selectgioduoi.selectedIndex].value;
                    console.log("textgioduoi: "+textgioduoi);

                    var selectphutduoi = document.getElementById("phutduoi");
                    var textphutduoi = selectphutduoi.options[selectphutduoi.selectedIndex].value;
                    console.log("textphutduoi: "+textphutduoi);


                    var selectgiotren = document.getElementById("giotren");
                    var textgiotren = selectgioduoi.options[selectgiotren.selectedIndex].value;
                    console.log("textgiotren: "+textgiotren);

                     var selectphuttren = document.getElementById("phuttren");
                     var textphuttren = selectphutduoi.options[selectphuttren.selectedIndex].value;
                    console.log("textphuttren: "+textphuttren);
									
					
						 if(textgiotren == textgioduoi)
                    {
						
                         if(textphuttren > textphutduoi)
                        {
                            
							 var DataSend = "{\"den\":\"1\",\"GD\":"+textgioduoi+",\"PD\":"+textphutduoi+",\"GT\":"+textgiotren+",\"PT\":"+textphuttren+"}";
							
							console.log("txden: "+DataSend);	
							
							mqttClient.send(topicpub, DataSend);
													
                        }
                        else if(textphuttren <= textphutduoi)
                        {
                            alert("Vui lòng chọn phút dưới nhỏ hơn phút trên");
                             return;
                        }
                      
                    }
                    else if(textgiotren > textgioduoi)
                    {
                      var DataSend = "{\"den\":\"1\",\"GD\":"+textgioduoi+",\"PD\":"+textphutduoi+",\"GT\":"+textgiotren+",\"PT\":"+textphuttren+"}";
							
							console.log("txden: "+DataSend);	
							
							mqttClient.send(topicpub, DataSend);
								
                    }
                    else if(textgiotren < textgioduoi)
                    {
						
                          alert("Vui lòng chọn giờ dưới lớn hơn hoặc bằng giờ trên");
                         return;
                    }
					
					
}

function HenQuat()
{
					var selectgioduoi = document.getElementById("gioduoi");
                    var textgioduoi = selectgioduoi.options[selectgioduoi.selectedIndex].value;
                    console.log("textgioduoi: "+textgioduoi);

                    var selectphutduoi = document.getElementById("phutduoi");
                    var textphutduoi = selectphutduoi.options[selectphutduoi.selectedIndex].value;
                    console.log("textphutduoi: "+textphutduoi);


                    var selectgiotren = document.getElementById("giotren");
                    var textgiotren = selectgioduoi.options[selectgiotren.selectedIndex].value;
                    console.log("textgiotren: "+textgiotren);

                     var selectphuttren = document.getElementById("phuttren");
                     var textphuttren = selectphutduoi.options[selectphuttren.selectedIndex].value;
                    console.log("textphuttren: "+textphuttren);

					
                     if(textgiotren == textgioduoi)
                    {
						
                         if(textphuttren > textphutduoi)
                        {
                            
							 var DataSend = "{\"quat\":\"1\",\"GD\":"+textgioduoi+",\"PD\":"+textphutduoi+",\"GT\":"+textgiotren+",\"PT\":"+textphuttren+"}";
							
							console.log("txden: "+DataSend);	
							
							mqttClient.send(topicpub, DataSend);
													
                        }
                        else if(textphuttren <= textphutduoi)
                        {
                            alert("Vui lòng chọn phút dưới nhỏ hơn phút trên");
                             return;
                        }
                      
                    }
                    else if(textgiotren > textgioduoi)
                    {
                      var DataSend = "{\"quat\":\"1\",\"GD\":"+textgioduoi+",\"PD\":"+textphutduoi+",\"GT\":"+textgiotren+",\"PT\":"+textphuttren+"}";
							
							console.log("txden: "+DataSend);	
							
							mqttClient.send(topicpub, DataSend);
								
                    }
                    else if(textgiotren < textgioduoi)
                    {
						
                          alert("Vui lòng chọn giờ dưới lớn hơn hoặc bằng giờ trên");
                         return;
                    }
}

function tathengioden()
{
		var DataSend = "{\"den\":\"0\"}";
	
	    mqttClient.send(topicpub, DataSend);
		console.log("Button tắt hẹn Giờ Đèn!!!");
  
}

function tathengioquat()
{
	console.log("Button tắt hẹn Giờ Quạt!!!");
	var DataSend = "{\"quat\":\"0\"}";
	mqttClient.send(topicpub, DataSend);
	 
}


function DISSERVER()
{
	alert('Đã ngắt kết nối tới MQTT Broker ngoinhaiot.com');
	DisconnectMQTT();
	document.getElementById("thongbao").innerHTML = "Disconnected";
}



function openPage(pageName,elmnt,color) 
{
	
		var i, tabcontent, tablinks;
		tabcontent = document.getElementsByClassName("tabcontent");
		for (i = 0; i < tabcontent.length; i++) {
			tabcontent[i].style.display = "none";
		}
		tablinks = document.getElementsByClassName("tablink");
		for (i = 0; i < tablinks.length; i++) {
			tablinks[i].style.backgroundColor = "";
		}
		document.getElementById(pageName).style.display = "block";
		elmnt.style.backgroundColor = color;

}

document.getElementById("defaultOpen").click();


</script>



</html>