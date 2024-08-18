<?php 

	// connect to database
	$conn = mysqli_connect('localhost', 'your_host', 'pass', 'crypto investment');

	// check connection
	if(!$conn){
		echo 'Connection error: ' . mysqli_connect_error();
	}

 ?>
