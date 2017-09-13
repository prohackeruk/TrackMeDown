<!-- This is the template file for a page which requires authentication to view -->
<?php 
	session_start();

	require '../auth/database.php';
	require '../auth/strings.php';

	$user = NULL;

	if(isset($_SESSION['user_id'])) { // If a user is logged in
		// Get the user who is logged in
		$sql_users = "SELECT * FROM users WHERE id = :id";
		$records = $conn->prepare($sql_users);
		$records->bindParam(':id', $_SESSION['user_id']);
		$records->execute();
		$results = $records->fetch(PDO::FETCH_ASSOC);

		if (count($results) > 0) {
			$user = $results;
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>TrackMeDown | Map</title>
	<style>
	html, body { /* STUPID STUPID DUMB */
    	height: 100%;
    	margin: 0;
    	padding: 0;
  	}
	#map {
		height: 100%;
	}
	</style>
	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
</head>
<body>
	<?php if (!empty($user)): ?>
<!--		<span>You are logged in as <?= $user['email']; ?></span> <a href="../auth/logout.php">Log Out</a> -->
		<!-- Full-page map -->
		<div id="map"></div>
	<?php else: ?>
		<!-- Redirect to the login screen eventually -->
		<h3>You need to log in to view the map.</h3>
		<a href="../auth/login.php">Log In Here</a>
		<a href="../auth/register.php">Register Here</a>
	<?php endif; ?>
	<!-- Google Maps code -->
	<script>
		var map;
		var devices = [];
		var locations = [];
		var pointsArray = [];

		function getDevices() {
			var val = null;
			$.ajax({
				url: "data.php?dataTarget=devices",
				async: false,
				dataType: 'json',
				success: function(data) {
					val = data;
				}
			});
			return val;
		}

		function getLocationsForDevice(id) {
			var val = null;
			$.ajax({
				url: "data.php?dataTarget=locations&deviceId=" + id,
				async: false,
				dataType: 'json',
				success: function(data) {
					val = data;
				}
			});
			return val;
		}

		function updatePaths() {
			devices = getDevices();
			devices.forEach(function (device) {
				locations = getLocationsForDevice(device['id']);
				// Add all of the locations for this device to an array
				var points = [];
				locations.forEach(function (location) {
					points.push({lat: parseFloat(location['latitude']), lng: parseFloat(location['longitude'])});
				});
				// Draw the line of points on the map
				poly = new google.maps.Polyline({
					path: points,
					geodesic: true,
					strokeColor: device['color'],
					strokeOpacity: 1.0,
					strokeWeight: 3
				});
				console.log("Device number " + device['id'] + " color: " + device['color']);
				poly.setMap(map);
				// Add a marker at the last point
				var marker = new google.maps.Marker({
					position: points[points.length - 1],
					map: map,
					title: device['user_agent']
				});
				marker.setMap(map);
			});
			setTimeout(updatePaths, 10000); // Update the map every ten seconds
			console.log("Updated paths");
		}

		function initMap() {
			// Create the map
			map = new google.maps.Map(document.getElementById('map'), {
        		center: {lat: 54.9881, lng: 1.6194},
        		zoom: 8
			});
			updatePaths();
		}
/*
		$(document).ready(function() {
			updatePaths();
		});
 */
	</script>
	<!-- Call the Google Maps API -->
	<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDM3ko2yoT3WNFKfl86XLpnUEquXRnwlF4&callback=initMap&libraries=geometry"></script>
</body>
</html>
