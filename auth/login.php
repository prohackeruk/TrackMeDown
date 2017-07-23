<?php
	session_start();

	require 'auth/database.php';
	require 'auth/strings.php';

	if (isset($_SESSION['user_id'])) {
		$message = $ALREADY_LOGGED_IN_ERROR;
		header("Location: /");
	}

	$message = '';

	if (!empty($_POST['email']) && !empty($_POST['password'])) {
		// The user has tried to log in correctly
		$sql = "SELECT id,email,password FROM users WHERE email = :email";

		$records = $conn->prepare($sql);
		$records->bindParam(':email', $_POST['email']);
		$records->execute();

		$results = $records->fetch(PDO::FETCH_ASSOC);

		if(count($results) > 0 && password_verify($_POST['password'], $results['password'])) {
			$message = $LOGIN_SUCCESS;
			// Create a session
			$_SESSION['user_id'] = $results['id'];
			header("Location: /");

		} else {
			$message = $PASSWORD_VERIFY_ERROR;
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Log In | prohack-id</title>
</head>
<body>

	<h1>Log In</h1>
	<a href="index.php">Back to Home</a>

	<!-- Show a result message if there is one -->
	<?php if (!empty($message)): ?>
		<p><?= $message ?></p>
	<?php endif; ?>

	<form action="auth/ogin.php" method="POST">
		<!-- Make the email address the placeholder -->
		<?php if (isset($_POST['email'])): ?>
			<input type="text" name="email" placeholder="Enter your email" value="<?= htmlspecialchars($_POST['email']) ?>" required>
		<?php else: ?>
			<input type="text" name="email" placeholder="Enter your email" required>
		<?php endif; ?>
		<input type="password" name="password" placeholder="Enter your password" required>
		<input type="submit" name="submit">
	</form>

	<a href="auth/register.php">Register Here</a>

</body>
</html>
