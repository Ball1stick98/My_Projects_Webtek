<?php
include('dbconn.php');

$sql = "SELECT * FROM travel_packages";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>
<head>
	<title>Travel Packages</title>
</head>
<body>
	<h1>Travel Packages</h1>
	<table>
		<thead>
			<tr>
				<th>Package ID</th>
				<th>Package Name</th>
				<th>Destination</th>
				<th>Price</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Maximum Capacity</th>
				<th>Available Capacity</th>
        <th>Departure Time</th>
        <th>Arrival Time</th>
        <th>Transport</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php while ($row = mysqli_fetch_assoc($result)) { ?>
			<tr>
				<td><?php echo $row['package_id']; ?></td>
				<td><?php echo $row['package_name']; ?></td>
				<td><?php echo $row['destination_name']; ?></td>
				<td><?php echo $row['price']; ?></td>
				<td><?php echo $row['start_date']; ?></td>
        <td><?php echo $row['end_date']; ?></td>
        <td><?php echo $row['maximum_capacity']; ?></td>
        <td><?php echo $row['available_capacity']; ?></td>
        <td><?php echo $row['departure_time']; ?></td>
        <td><?php echo $row['arrival_time']; ?></td>
        <td><?php echo $row['transport_type']; ?></td>
				<td><a href="reservation.html">Make A Reservation</a></td>
			</tr>
				<?php } ?>
		</tbody>
	</table>
</body>
</html>

<?php

mysqli_close($conn);
?>
