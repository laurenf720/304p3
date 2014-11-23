<?php
	function getconnection(){
		$connection = new mysqli("127.0.0.1", "root", "photon", "AMS");
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		return $connection;
	}
?>	