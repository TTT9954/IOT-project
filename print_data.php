<?php 

function PrintObjectDatabase($conn) {
      
		$conn = mysqli_connect("localhost", "root", "", "databaseesp");
		
		if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
		}

		$sql = "SELECT id, nhietdo, doam, ID_FP, Name, den, quat, H1, H2, date, time FROM dbsensor order by id desc";
		$result = $conn->query($sql);
		$number_of_result = mysqli_num_rows($result);
		if ($result->num_rows > 0) {

		while($row = $result->fetch_assoc()) {
			echo '<tr>';
            echo '<td class="bdr">'. $row['id'] . '</td>';
            echo '<td class="bdr">'. $row['nhietdo'] . '</td>';
            echo '<td class="bdr">'. $row['doam'] . '</td>';
			echo '<td class="bdr">'. $row['ID_FP'] . '</td>';
            echo '<td class="bdr">'. $row['Name'] . '</td>';
            echo '<td class="bdr">'. $row['den'] . '</td>';
            echo '<td class="bdr">'. $row['quat'] . '</td>';
            echo '<td class="bdr">'. $row['H1'] . '</td>';
            echo '<td class="bdr">'. $row['H2'] . '</td>';
            echo '<td class="bdr">'. $row['time'] . '</td>';
            echo '<td class="bdr">'. $row['date'] . '</td>';
            echo '</tr>';
		}
			
		} else { echo "0 results"; }
		$conn->close();
}

// Print object
function PrintObject($objType, $objName, $state, $objFalvor, $amplitude, $icon) {

	if($state) {
		$stateButton = "switch-on";
		$stateName = "turn-on";
	}

	echo '<div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">';
		echo '<div class="object '.$objType . " " .$objFalvor. " " .$stateName.'">';
			echo '<div class="obj-info">',
	                '<p class="obj-header">'.$objName.'</p>';

	            if( $objType != "obj-button")
	            echo'<p class="obj-counter-percent">',
	                	'<i class="fa '.$icon.'"></i>',
	                	'<b class="counter">'.$amplitude.'</b>',
	                '</p>';
	        echo  '</div>';
	    	echo '<div class="obj-timer">
	                <svg class="timer-progress" viewbox="0 0 82 82">
	                  <circle class="progress-bg" r="39" cx="41" cy="41" stroke-dasharray="245"></circle>
	                  <circle class="progress-bar" r="39" cx="41" cy="41" stroke-dasharray="245"></circle>
	                </svg>',
	                '<i class="obj-icon fa '.$icon.'"></i>',
	              '</div>';
	        if( $objType == "obj-slider")
	    		echo '<div class="slider-range-min"></div>';
	        else if( $objType == "obj-button")
	        	echo '<div class="switch-button '.$stateButton.'"></div>';
	        else if( $objType == "obj-turn obj-slider" ) {
	        	echo '<div class="switch-button type-turn"></div>
	        			<div class="obj-off"><i class="fa fa-close"></i></div>
	        			<div class="slider-range-min"></div>';
	        }
	        echo ' <div class="clearfix"></div>';
		echo '</div>';
	echo '</div>';
	
}

// Print object : send special name and value
function PrintObjectSend() {

	echo '<div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
            <div class="object obj-button obj-send send">
              <div class="obj-info">
                <p class="obj-header">Send To</p>
                <div class="send-name type-input">
                  <label for="name">Name : </label>
                  <input type="text" name="name" class="input-name">
                </div>
                <div class="send-state type-input">
                  <label for="state">State : </label>
                  <input type="text" name="state" class="input-sate">
                </div>
              </div>
              <button class="submit submit-button" type="button"><i class="obj-icon fa fa-send"></i><span>Send</span></button>
              <div class="obj-off"><i class="fa fa-close"></i></div>
              <div class="clearfix"></div>
            </div>
          </div>';
}

// Print whatever you want

function PrintEverythingElse() {
	echo '<div>Print here</div>';
}
	
?>