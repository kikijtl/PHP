<?php

// verify user
session_start();
$username = $_POST['toUser'];

if (!isset($_SESSION['username'][$username])) {
	header("location:login.php");
}

$fromUser = $_POST['fromUser'];
$toUser = $_POST['toUser'];
$accept = $_POST['accept'];

$db = mysqli_connect("localhost","root","1234","gossip_chat");
if (!$db){
		echo 'Connect Error. Please try again.';
		http_reponse_code(500);
}

// Delete the read friend request from log table
$query = "delete from log where timestamp=0 and fromUser='$fromUser' and toUser='$toUser'";
$result = mysqli_query($db, $query);
if (!$result){
	http_response_code(500);
}

// Handle accept friend request.
if ($accept == "1"){
	$date = new DateTime();
	$timestamp = $date->getTimestamp();
	// Add the friendship to table.
	$query = "insert into friendship values('$fromUser', '$toUser')";
	$result = mysqli_query($db, $query);
	if (!$result){
		http_response_code(500);
		exit();
	}
	// Add the friendship accepted message to the log table so that
	// the user who sent the request will be notified.
	$query = "insert into log values('$timestamp', '$toUser', '$fromUser'," .
			" 'I have accepted your friend request', 1)";
	$result = mysqli_query($db, $query);
}

mysqli_close($db);


?>