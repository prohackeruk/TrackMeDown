<?php
	function geocode($_lat, $_lon) {
		$url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$_lat.",".$_lon;
		$data = file_get_contents($url);
		$json = json_decode($data);
		return $json->{'results'}[0]->{'formatted_address'};
	}
?>
