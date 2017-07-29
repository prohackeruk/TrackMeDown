<?php
	$server = 'localhost';
	$username = 'root';
	$password = 'root';
	$database = 'TrackMeDown'; // This is set by the sql setup script so don't change it

	try {
		$conn = new PDO("mysql:host=$server;dbname=$database;", $username, $password);
	} catch (PDOException $e) {
		// If you can't connect to the DB, throw the error you get
		die("Connection failed " . $e->getMessage());
	}
?>
