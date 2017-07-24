<?php
	require '../auth/database.php';

	class Location {
		private $id;
		private $device_id;
		private $latitude;
		private $longitude;
		private $address;

		function __construct($_id, $_device, $_lat, $_long, $_addr) {
			$this->id = $_id;
			$this->device_id = $_device;
			$this->latitude = $_lat;
			$this->longitude = $_long;
			$this->address = $_addr;
		}

		public function GetLocationById($_id) {
			
		}
	}
?>