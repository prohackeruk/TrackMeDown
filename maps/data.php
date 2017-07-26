<?php
	require '../auth/database.php';

	function getDevices() {
		require '../auth/database.php';
		// Get all of the connected devices
		$sql_devices = "SELECT * FROM devices";
		$stmt_device = $conn->prepare($sql_devices);
		$stmt_device->execute();
		$devices = $stmt_device->fetchAll(PDO::FETCH_ASSOC);
		return json_encode($devices);
		#return var_dump($devices);
	}
	
	function getLocations() {
		require '../auth/database.php';	
		// Get all of the location data
		$sql_locations = "SELECT * FROM locations WHERE device_id = :device_id";
		$stmt_location = $conn->prepare($sql_locations);
		$stmt_location->bindParam(":device_id", $_GET['deviceId']);
		$stmt_location->execute();
		$locations = $stmt_location->fetchAll(PDO::FETCH_ASSOC);
		return json_encode($locations);
		#return var_dump($devices);
	}

	if (isset($_GET['dataTarget'])) {
		if ($_GET['dataTarget'] == 'devices') {
			echo getDevices();
		} else if ($_GET['dataTarget'] == 'locations' and isset($_GET['deviceId'])) {
			echo getLocations();
		} else {
			echo "Bad input";
		}
	}
?>
