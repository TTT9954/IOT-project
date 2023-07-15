
<!DOCTYPE HTML>
<html>
  <head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <style>
      /* ----------------------------------- TOPNAV STYLE */
      
      .topnav {

        overflow: hidden; 
        background-color: #0c6980; 
        background-size: 50%;
        color: white; 
        font-size: 1.2rem;
        text-align: center;

      }
      
      h3{
        text-align: center;
      }
      /* ----------------------------------- */
      
      /* ----------------------------------- TABLE STYLE */
      .styled-table {
        border-collapse: collapse;
        margin-left: auto; 
        margin-right: auto;
        font-size: 0.9em;
        font-family: sans-serif;
        /* min-width: 300px; */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        border-radius: 0.5em;
        overflow: hidden;
        width: 600px;
      }

      .styled-table thead tr {
        background-color: #0c6980;
        color: #ffffff;
        text-align: center;
      }

      .styled-table th {
        padding: 12px 15px;
        text-align: center;
      }

      .styled-table td {
        padding: 12px 15px;
        text-align: center;
      }

      .styled-table tbody tr:nth-of-type(even) {
        background-color: #f3f3f3;
      }

      .styled-table tbody tr.active-row {
        font-weight: bold;
        color: #009879;
      }

      .bdr {
        border-right: 1px solid #e3e3e3;
        border-left: 1px solid #e3e3e3;
      }
      
      td:hover {background-color: rgba(12, 105, 128, 0.21);}
      tr:hover {background-color: rgba(12, 105, 128, 0.15);}
      .styled-table tbody tr:nth-of-type(even):hover {background-color: rgba(12, 105, 128, 0.15);}
      /* ----------------------------------- */
      
      /* ----------------------------------- BUTTON STYLE */
      .btn-group
      {
        text-align: center;
      }
      .btn-group .button {
        background-color: #0c6980; /* Green */
        border: 1px solid #e3e3e3;
        color: white;
        padding: 5px 8px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
        cursor: pointer;
      }

      .btn-group .button:not(:last-child) {
        border-right: none; /* Prevent double borders */
      }

      .btn-group .button:hover {
        background-color: #094c5d;
      }

      .btn-group .button:active {
        background-color: #0c6980;
        transform: translateY(1px);
      }

      .btn-group .button:disabled,
      .button.disabled{
        color:#fff;
        background-color: #a0a0a0; 
        cursor: not-allowed;
        pointer-events:none;
      }
      /* ----------------------------------- */
    
    </style>
  </head>
  
  <body>
   <!-- <div class="topnav">
      <h3 style="text-align: center">Real Time Data Display</h3>
    </div> -->
    
    <div class="topnav">
      <h3>FINGERPRINT REGISTRATION RECORD</h3>
    </div>
    <table class="styled-table" id= "table_id_1">
      <br>
      <thead>
	  	<tr>
          <th>ID registered</th>
          <th>Name registered</th>
          <th>TIME</th>
          <th>DATE</th>
        </tr>
      </thead>
      <tbody id="tbody_table_record">
	  <?php
         
		 $conn = mysqli_connect("localhost", "root", "", "databaseesp");
		 
		 if ($conn->connect_error) {
		 die("Connection failed: " . $conn->connect_error);
		 } 
		 $sql = "SELECT ID_FP, Name, date, time FROM fingerprint_register order by id desc";
		 $result = $conn->query($sql);
		 $number_of_result = mysqli_num_rows($result);
		 if ($result->num_rows > 0) {
 
		 while($row = $result->fetch_assoc()) {
			 echo '<tr>';
			 echo '<td class="bdr">'. $row['ID_FP'] . '</td>';
			 echo '<td class="bdr">'. $row['Name'] . '</td>';
			 echo '<td class="bdr">'. $row['time'] . '</td>';
			 echo '<td class="bdr">'. $row['date'] . '</td>';
			 echo '</tr>';
		 }
			 
		 } else { echo "0 results"; }
		 $conn->close();
		 ?>
      </tbody>
    </table>
    <br>
    
    <div class="btn-group">
      <button class="button" id="btn_prev_1" onclick="prevPage()">Prev</button>
      <button class="button" id="btn_next_1" onclick="nextPage()">Next</button>
      <div style="display: inline-block; position:relative; border: 0px solid #e3e3e3; margin-left: 2px;">
        <p style="position:relative; font-size: 14px;"> Table : <span id="page_1"></span></p>
      </div>
      <select name="number_of_rows_1" id="number_of_rows_1">
        <option value="10">10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="100">100</option>
		
      </select>
      <button class="button" id="btn_apply_1" onclick="apply_Number_of_Rows()">Apply</button>
    </div>
    <div style="border: both">
    <br> 
<!-- ///////////////////////////////////////////////////////////////////////////// -->
    <br> 
    <br> 
    <div class="topnav">
      <h3>FINGERPRINT VERIFICATION RECORD</h3>
    </div>
    <table class="styled-table" id= "table_id_2">
      <br>
      <thead>
	  	<tr>
          <th>ID verification</th>
          <th>Name verification</th>
          <th>TIME</th>
          <th>DATE</th>
        </tr>
      </thead>
      <tbody id="tbody_table_record">
	  <?php
         
		 $conn = mysqli_connect("localhost", "root", "", "databaseesp");
		 
		 if ($conn->connect_error) {
		 die("Connection failed: " . $conn->connect_error);
		 } 
		 $sql = "SELECT ID_FP, Name, date, time FROM fingerprint_verification order by id desc";
		 $result = $conn->query($sql);
		 $number_of_result = mysqli_num_rows($result);
		 if ($result->num_rows > 0) {
 
		 while($row = $result->fetch_assoc()) {
			 echo '<tr>';
			 echo '<td class="bdr">'. $row['ID_FP'] . '</td>';
			 echo '<td class="bdr">'. $row['Name'] . '</td>';
			 echo '<td class="bdr">'. $row['time'] . '</td>';
			 echo '<td class="bdr">'. $row['date'] . '</td>';
			 echo '</tr>';
		 }
			 
		 } else { echo "0 results"; }
		 $conn->close();
		 ?>
      </tbody>
    </table>
    <br>
    
    <div class="btn-group">
      <button class="button" id="btn_prev_2" onclick="prevPage()">Prev</button>
      <button class="button" id="btn_next_2" onclick="nextPage()">Next</button>
      <div style="display: inline-block; position:relative; border: 0px solid #e3e3e3; margin-left: 2px;">
        <p style="position:relative; font-size: 14px;"> Table : <span id="page_2"></span></p>
      </div>
      <select name="number_of_rows_2" id="number_of_rows_2">
        <option value="10">10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="100">100</option>
		
      </select>
      <button class="button" id="btn_apply_2" onclick="apply_Number_of_Rows()">Apply</button>
    </div>
    <div style="border: both">
    <br> 

</body>

<script>
      var current_page = 1;
      var current_page1 = 1;
      var records_per_page = 10;
      var records_per_page1 = 10;
      var l = document.getElementById("table_id_1").rows.length
      var l1 = document.getElementById("table_id_2").rows.length
      //------------------------------------------------------------
      
      //------------------------------------------------------------
      function apply_Number_of_Rows() {
        var x = document.getElementById("number_of_rows_1").value;
        var x1 = document.getElementById("number_of_rows_2").value;
        records_per_page = x;   
        records_per_page1 = x1;    
        changePage(current_page);
        changePage1(current_page1);
      }

      //------------------------------------------------------------
      
      //------------------------------------------------------------
      function prevPage() {
        if (current_page > 1) {
            current_page--;
            changePage(current_page);
        }
        if (current_page1 > 1) {
            current_page1--;
            changePage1(current_page1);
        }
      }
      //------------------------------------------------------------
      
      //------------------------------------------------------------
      function nextPage() {
        if (current_page < numPages()) {
            current_page++;
            changePage(current_page);
        }
        if (current_page1 < numPages1()) {
            current_page1++;
            changePage1(current_page1);
        }
        
      }
      //------------------------------------------------------------
      
      //------------------------------------------------------------
      function changePage(page) {
        var btn_next = document.getElementById("btn_next_1");
        var btn_prev = document.getElementById("btn_prev_1");
        var listing_table = document.getElementById("table_id_1");
        var page_span = document.getElementById("page_1");
       
        // Validate page
        if (page < 1) page = 1;
        if (page > numPages()) page = numPages();

        [...listing_table.getElementsByTagName('tr')].forEach((tr)=>{
            tr.style.display='none'; // reset all to not display
        });

        listing_table.rows[0].style.display = ""; // display the title row

        for (var i = (page-1) * records_per_page + 1; i < (page * records_per_page) + 1; i++) {
          if (listing_table.rows[i]) {
            listing_table.rows[i].style.display = ""
          } else {
            continue;
          }
        }

        page_span.innerHTML = page + "/" + numPages() + " (Total Number of Rows = " + (l-1) + ") | Number of Rows : ";

        if (page == 0 && numPages() == 0) {
          btn_prev.disabled = true;
          btn_next.disabled = true;
          return;
        }

        if (page == 1) {
          btn_prev.disabled = true;
        } else {
          btn_prev.disabled = false;
        }

        if (page == numPages()) {
          btn_next.disabled = true;
        } else {
          btn_next.disabled = false;
        }
      }

      function changePage1(page) {
        var btn_next = document.getElementById("btn_next_2");
        var btn_prev = document.getElementById("btn_prev_2");
        var listing_table = document.getElementById("table_id_2");
        var page_span = document.getElementById("page_2");
       
        // Validate page
        if (page < 1) page = 1;
        if (page > numPages1()) page = numPages1();

        [...listing_table.getElementsByTagName('tr')].forEach((tr)=>{
            tr.style.display='none'; // reset all to not display
        });

        listing_table.rows[0].style.display = ""; // display the title row

        for (var i = (page-1) * records_per_page1 + 1; i < (page * records_per_page1) + 1; i++) {
          if (listing_table.rows[i]) {
            listing_table.rows[i].style.display = ""
          } else {
            continue;
          }
        }

        page_span.innerHTML = page + "/" + numPages1() + " (Total Number of Rows = " + (l1-1) + ") | Number of Rows : ";

        if (page == 0 && numPages1() == 0) {
          btn_prev.disabled = true;
          btn_next.disabled = true;
          return;
        }

        if (page == 1) {
          btn_prev.disabled = true;
        } else {
          btn_prev.disabled = false;
        }

        if (page == numPages1()) {
          btn_next.disabled = true;
        } else {
          btn_next.disabled = false;
        }
      }
      //------------------------------------------------------------
      
      //------------------------------------------------------------
      function numPages() {
        return Math.ceil((l - 1) / records_per_page);
      }

      function numPages1() {
        return Math.ceil((l1- 1) / records_per_page1);
      }
      //------------------------------------------------------------
      
      //------------------------------------------------------------
      window.onload = function() {
        var x = document.getElementById("number_of_rows_1").value;
        records_per_page = x;
        changePage(current_page);
        var x1 = document.getElementById("number_of_rows_2").value;
        records_per_page1 = x1;
        changePage1(current_page1);
      };

</script>


</html>