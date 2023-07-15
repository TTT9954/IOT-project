<?php	
	$page = 'controll';
	include 'index_login.php';
?>

<!DOCTYPE html>

<html>

<head>
	<meta charset="utf-8"> 
	<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.js" type="text/javascript"></script>
	
</head>

<body>


<h1>
ĐÈN
<!--<button  class="abcd" onclick="DK_DEN()">ĐÈN</button> -->
<label class="switch">
    <input type="checkbox" id="ESP32_01_TogLED_01" onclick="GetTogBtnLEDState('ESP32_01_TogLED_01')">
    <div class="sliderTS"></div>
</label>
</h1>

<h1>
QUẠT
<!-- <button class="btn ash-grey" onclick="DK_QUAT()">QUẠT</button> -->
<label class="switch">
    <input type="checkbox" id="ESP32_01_TogLED_02" onclick="GetTogBtnLEDState('ESP32_01_TogLED_02')">
    <div class="sliderTS"></div>
</label>
</h1>

<select id="chon"  style="width: 60px; height: 40px;">
		
	
							<option value = "1" >Đèn</option>
							<option value = "2" >Quạt</option>
													
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
CÀI ĐẶT 1:
<input id="textcai1"></input>
<button onclick="SEND_C1()">Send</button>
</h1>

<h1>
CÀI ĐẶT 2:
<input id="textcai2"></input>
<button onclick="SEND_C2()">Send</button>
</h1>

<h1>
CÀI ĐẶT 1:
<label id="cai1">0</label>
</h1>

<h1>
CÀI ĐẶT 2:
<label id="cai2">0</label>
</h1>

<h1>
CÀI ĐẶT 2:
<label id="cai3">0</label>
</h1>


<h1>
TRẠNG THÁI ĐÈN:
<label id="trangthaiden">OFF</label>
<img id="den1" src="https://cdn-icons-png.flaticon.com/512/702/702814.png"  style="width:80px;height:80px;">
</h1>


<h1>
TRẠNG THÁI QUẠT:
<label id="trangthaiquat">OFF</label>
</h1>


<h1>
HẸN GIỜ ĐÈN:
<label id="henden">OFF</label>
</h1>


<h1>
HẸN GIỜ QUẠT:
<label id="henquat">OFF</label>
</h1>

</body>

</html>