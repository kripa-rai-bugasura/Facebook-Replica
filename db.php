<?php
	$servername = "localhost";
	$username = "fb_user";
	$password = "fb@Bugasura26";
	$dbname = "facebook";

	// connect to the database
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if(!$conn)
	{
		die("Connection failed: " .  mysqli_connect_error());
	}
?>

