<?php
	require '../auth/database.php';
	require 'token.php'; // Has a function for generating tokens

	class Device {
		private $id;
		private $token;
		private $user_agent;
		private $ip_remote_addr;
		private $ip_forwarded_for;
		private $time_located;

		function __construct($_id, $_token, $_user, $_remote, $_forwarded, $_time) {
			$this->id = $_id;
			$this->token = $_token;
			$this->user_agent = $_user;
			$this->ip_remote_addr = $_remote;
			$this->ip_forwarded_for = $_forwarded;
			$this->time_located = $_time;
		}

		public function Save() {
			$sql = "INSERT INTO devices (token, user_agent, ip_remote_addr, ip_forwarded_for, time_located) VALUES (:token, :user_agent, :ip_remote_addr, :ip_forwarded_for, :time_located);";
			$stmt = $conn->prepare($sql); // We get $conn from auth/database.php

			// Always bind parameters, SQL injection is so 2003
			$stmt->bindParam(":token", $this->token); // The token we gave the user
			$stmt->bindParam(":user_agent", $this->user_agent); // UserAgent header string
			$stmt->bindParam(":ip_remote_addr", $this->ip_remote_addr); // The address the connection was made from
			$stmt->bindParam(":ip_forwarded_for", $this->ip_forwarded_for); // An HTTP header sometimes set by proxy servers -- if set do not implicitly trust
			$stmt->bindParam(":time_located", $this->time_located); // Date formatted for MySQL's DATE type
			
			// Run the statement
			if ($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}
	}
?>