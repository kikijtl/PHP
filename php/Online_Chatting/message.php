<?php

$fromUser = null;
$toUser = null;
$username = null;
$method = $_SERVER['REQUEST_METHOD'];

if ($method == "GET"){
//	$fromUser = $_GET['fromUser'];
	$toUser = $_GET['toUser'];
	$username = $toUser;
} elseif ($method == "POST"){
	$fromUser = $_POST['fromUser'];
	$toUser = $_POST['toUser'];
	$username = $fromUser;
}


// verify user
session_start();
if (!isset($_SESSION['username'][$username])) {
	header("location:login.php");
}


// Update unread messages to be read.
function justSetRead($timestamp, $fromUser, $toUser) {
	$db = mysqli_connect("localhost","root","1234","gossip_chat");
	if (!$db){
		http_response_code(500);
		exit();
	}

	// Update unread flag.
	$query = "update log set unread=0 where fromUser='$fromUser' and toUser='$toUser' and timestamp='$timestamp'";
	$result = mysqli_query($db, $query);
	mysqli_close($db);
}


// Store the new message into database.
if ($method == "POST"){
	if (isset($_POST['setRead'])){
		justSetRead($_POST['timestamp'], $fromUser, $toUser);
		exit();
	}
	$timestamp = $_POST['timestamp'];
	$message = $_POST['message'];

	$db = mysqli_connect("localhost","root","1234","gossip_chat");
	if (!$db){
		http_response_code(500);
		exit();
	}

	// Check friendship.
	$checkFriendship = "select * from friendship where fromUser='$fromUser' and toUser='$toUser'" .
		" union select * from friendship where fromUser='$toUser' and toUser='$fromUser'";
	$friendshipResult = mysqli_query($db, $checkFriendship);
	$row = mysqli_fetch_array($friendshipResult);
	if (!$row){
		http_response_code(400);
		echo "$fromUser, you are not in friendship with $toUser.";
		mysqli_close($db);
		exit();
	}

	// Store the new message.
	$query = "insert into log values ('$timestamp', '$fromUser', '$toUser', '$message', 1)";
	$result = mysqli_query($db, $query);
	if (!$result){
		http_response_code(500);
		echo "Failed to send message.";
	} else{
		echo "$fromUser said to $toUser: $message";
	}
	mysqli_close($db);
}


// Get unread messages from database.
// Loop until timeout or get non-empty results from database.

function getUnread($timeout, $username, $newMessages) {
	$i = 0;
	while ($i < $timeout){
//		sleep(1);
		$i++;

		$db = mysqli_connect("localhost","root","1234","gossip_chat");
		if (!$db){
			continue;
		}

		// Select unread messages.
		$query = "select * from log where toUser='$username' and unread=1";
		$result = mysqli_query($db, $query);
		$rowcount = mysqli_num_rows($result);
		if ($rowcount == 0){
//			echo $rowcount;
			continue;
		}
		while ($row=mysqli_fetch_array($result)){
			$json = json_encode($row);
			array_push($newMessages, $json);
		}
		if (!empty($newMessages)){
			echo json_encode($newMessages);
			mysqli_close($db);
			exit();
		}
		mysqli_close($db);
	}
	echo "[]";
}


// Get unread messages from database.
if ($method == "GET"){
	$timeout = $_GET['timeout'];
	$newMessages = array();
	getUnread($timeout, $username, $newMessages);
}


?>

