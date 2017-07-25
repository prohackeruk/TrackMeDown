<!-- This is the template file for a page which requires authentication to view -->
<?php 
	session_start();

	require '../auth/database.php';
	require '../auth/strings.php';

	$user = NULL;

	if(isset($_SESSION['user_id'])) {
		$sql = "SELECT id,email,password FROM users WHERE id = :id";
		$records = $conn->prepare($sql);
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
		<h3>You need to log in to view the map.</h3>
		<a href="../auth/login.php">Log In Here</a>
		<a href="../auth/register.php">Register Here</a>
	<?php endif; ?>
	<!-- Google Maps code -->
	<script>
		var map;
		function initMap() {	
			map = new google.maps.Map(document.getElementById('map'), {
        		center: {lat: 54.0, lng: -1.0},
        		zoom: 8
        	});
		}
	</script>
	<!-- Call the Google Maps API -->
	<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDrSctxCpc9uvONzarhSatCnwwmyKAiNYo&callback=initMap&libraries=geometry"></script>
</body>
</html>
