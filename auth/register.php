<?php
	session_start();

	require 'database.php';
	require 'strings.php';

	if (isset($_SESSION['user_id'])) {
		$message = $ALREADY_LOGGED_IN_ERROR;
		header("Location: /auth/");
	}

	$message = '';
	if (isset($_POST['email']) and isset($_POST['password']) and isset($_POST['confirm_password'])) {
		if ($_POST['password'] != $_POST['confirm_password']) {
			$message = $PASSWORD_CONFIRM_NO_MATCH_ERROR;
		} else {
			// Look for the entered email address in the database
			$sql = "SELECT id,email,password FROM users WHERE email = :email";

			$records = $conn->prepare($sql);
			$records->bindParam(':email', $_POST['email']);
			$records->execute();

			$results = $records->fetch(PDO::FETCH_ASSOC);
			// If you find one, don't allow the user to register
			if ($results) { // fetch() returns false when there are no rows which is the dumbest thing in the history of ever
				$message = $USER_ALREADY_EXISTS_ERROR;
			} else {
				// The user has tried to register correctly, enter in database
				$sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
				$stmt = $conn->prepare($sql);

				// Bound parameters prevent SQL injection attacks
				$stmt->bindParam(':email', $_POST['email']);
				$stmt->bindParam(':password', password_hash($_POST['password'], PASSWORD_BCRYPT)); // Hash the password before it goes in the database
			
				if ($stmt->execute()) {
					$message = $REGISTER_SUCCESS;
					header("Location: /auth/login.php"); // Redirect if you signed up successfully
				} else {
					$message = $REGISTER_FAILED;
				}
			}
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Register | TrackMeDown</title>
</head>
<body>

	<h1>Register</h1>
	<a href="index.php">Back to Home</a>

	<!-- Show a result message if there is one -->
	<?php if (!empty($message)): ?>
		<p><?= $message ?></p>
	<?php endif; ?>

	<form action="register.php" method="POST">
		<?php if (isset($_POST['email'])): ?>
			<input type="text" name="email" placeholder="Pick a username" value="<?= htmlspecialchars($_POST['email']) ?>" required>
		<?php else: ?>
			<input type="text" name="email" placeholder="Pick a username" required>
		<?php endif; ?>
		<input type="password" name="password" placeholder="Choose a password" required>
		<input type="password" name="confirm_password" placeholder="Confirm password" required>
		<input type="submit" name="submit">
	</form>

	<a href="login.php">Log In Here</a>
</body>
</html>
