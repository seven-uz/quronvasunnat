<?php
	$host = "localhost";
	$user = "root";
	$password = "";
	$database = "quronvasunnat";

	$db = mysqli_connect($host,$user,$password,$database);
	// $db = mysqli_connect("localhost","sevenuz","O1Mdsa9p&9195ZGD^SAtel","quronvasunnat");

	if (!$db) {
		exit("Problem with selection of 'database'");
	}
?>