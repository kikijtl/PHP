<?php

// verify user
session_start();
$username = $_GET['username'];

if (!isset($_SESSION['username'][$username])) {
	header("location:login.php");
}

// start handling request
if (empty($_GET['username'])){
	http_response_code(400);
} else {
	$db = mysqli_connect("localhost","root","1234","gossip_chat");
	if (!$db){
		http_response_code(500);
		echo '<p color="red">Connect Error. Please try again.</p>';
	}

	$query = "delete from active_user where username='$username'";
	$result = mysqli_query($db, $query);
	if ($result){
		unset($_SESSION['username'][$username]);
		echo "You are successfully logged out.";
	} else {
		http_response_code(400);
	}

}

//echo $_SERVER['REQUEST_URI'];

?>