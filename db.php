<?php

	$config = require('config.php');
	$servername = $config['servername'];
	$username = $config['username'];
	$password = $config['password'];
	$dbname = $config['dbname'];

	// connect to the database
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if(!$conn)
	{
		die("Connection failed: " .  mysqli_connect_error());
	}
?>