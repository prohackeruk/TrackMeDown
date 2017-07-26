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
		function stringToObject(str) { // This is needed because we get a string of an array of JSON objects instead of straight JSON -- probably fixed in the migrate to Go microservices
			data = str.replace("[", "").replace("]", "").split("},");
			for (var i = 0; i < data.length; i++) {
				if (i != data.length - 1) {
					data[i] += "}";
				}
				console.log("Data string: " + data[i]);
				data[i] = JSON.parse(data[i]);
				console.log("Data JSON: " + data[i]);
			}
			return data;
		}
		var map;
		function initMap() {
			// Create the map
			map = new google.maps.Map(document.getElementById('map'), {
        		center: {lat: 54.9881, lng: 1.6194},
        		zoom: 16
			});
			// Get all of the connected devices
			$.get("data.php?dataTarget=devices")
					.done(function(data) {
						data = stringToObject(data);
						// Loop through them
						data.forEach(function (element) {
								// Make an array out of all of the points associated with that device
								var points = []; // Points on the map
								$.get("data.php?dataTarget=locations&deviceId=" + element['id'])
										.done(function(pointData) {
											console.log("Server returned: " + pointData);
											if (pointData != "[]") { // This is what you get back if there is no location data associated with the device in question
												pointData = stringToObject(pointData);
												//console.log(pointData);
												// Add the points to the array
												pointData.forEach(function (point) {
													//console.log(point);
													points.push({lat: point['latitude'], lng: point['longitude']});
												});
											} else {
												console.log("No location data available for device " + element['id']);
											}
									});
								// Make a path object
								var routePath = new google.maps.Polyline({
								path: points,
									geodesic: true,
									strokeColor: '#FF0000', // Give each device a color
									strokeOpacity: 1.0,
									strokeWeight: 2
								});
								// Add it to the map
								routePath.setMap(map);
								// End of the loop, go back and do the next one
						});
				 	});
		}
	</script>
	<!-- Call the Google Maps API -->
	<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDrSctxCpc9uvONzarhSatCnwwmyKAiNYo&callback=initMap&libraries=geometry"></script>
</body>
</html>
