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
</head>
<body>
	<?php if (!empty($user)): ?>
		<!-- MAP -->
		<p>You are logged in as <?= $user['email']; ?></p>
		<h1>MAP GOES HERE</h1>
		<a href="../auth/logout.php">Log Out</a>
	<?php else: ?>
		<h3>You need to log in to view the map.</h3>
		<a href="../auth/login.php">Log In Here</a>
		<a href="../auth/register.php">Register Here</a>
	<?php endif; ?>

</body>
</html>
