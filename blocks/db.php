<?
	$host = "localhost";
	$user = "root";
	$password = "";
	$database = "quronvasunnat";

	$db = mysqli_connect($host,$user,$password,$database);
	// $db = mysqli_connect("localhost","mysqlsunway","O1Mp&9195Ztel","mysqlsunway");

	if (!$db) {
		exit("Problem with selection of 'database'");
	}
?>