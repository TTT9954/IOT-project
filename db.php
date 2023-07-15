<table border = 1 cellpadding = 10>
  <tr>
    <td>ID</td>
    <td>Nhiệt độ</td>
    <td>Độ ẩm</td>
    <td>Đèn</td>
    <td>Quạt</td>
	<td>TIME</td>
    <td>DATE</td>
  </tr>
  <?php
		$conn = mysqli_connect("localhost", "root", "", "databaseesp");

		if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
		}
		$sql = "SELECT id, nhietdo, doam, den, quat, date, time FROM dbsensor order by id desc";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {

		while($row = $result->fetch_assoc()) {
			echo '<table>';
			echo '<tr>';
            echo '<td class="bdr">'. $row['id'] . '</td>';
            echo '<td class="bdr">'. $row['nhietdo'] . '</td>';
            echo '<td class="bdr">'. $row['doam'] . '</td>';
            echo '<td class="bdr">'. $row['den'] . '</td>';
            echo '<td class="bdr">'. $row['quat'] . '</td>';
            echo '<td class="bdr">'. $row['time'] . '</td>';
            echo '<td class="bdr">'. $row['date'] . '</td>';
            echo '</tr>';
			echo '</table>';
		}
		 else { echo "0 results"; }
		$conn->close();
	?>
</table>
