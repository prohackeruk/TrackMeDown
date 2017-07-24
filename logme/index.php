<?php
	require '../auth/database.php';
	require 'geocode.php';

	if (isset($_POST['token']) and isset($_POST['latitude']) and isset($_POST['longitude'])) { // This is what we get posted
		// Select a device ID from the devices table using the token you were posted
		$sql_device = "SELECT id FROM devices WHERE token = :token;";
		$select = $conn->prepare($sql_device);
		$select->bindParam(":token", $_POST['token']);
		if ($select->execute()) {
			$device = $records->fetch(PDO::FETCH_ASSOC);
		} else {
			die("Failed to get device for some reason");
		}
		// Get the street address from geocode.php
		$address = geocode($_POST['latitude'], $_POST['longitude']);
		// Get the time
		$time =  date("Y-m-d H:i:s"); // We need to pass a variable to bindParam for futureproofing reasons
		// Prepare a statement and insert a row
		$sql_location = "INSERT INTO locations (device_id, latitude, longitude, address, time_located) VALUES (:device_id, :latitude, :longitude, :address, :time_located);";
		$insert = $conn->prepare($sql_location);
		$insert->bindParam(":device_id", $device['id']);
		$insert->bindParam(":latitude", $_POST['latitude']);
		$insert->bindParam(":longitude", $_POST['longitude']);
		$insert->bindParam(":address", $address);
		$insert->bindParam(":time_located", $time); // Date formatted for MySQL's DATE type
		if ($insert->execute()) {
			echo "Inserted location";
		} else {
			die("Failed to insert location");
		}
	}
?>
