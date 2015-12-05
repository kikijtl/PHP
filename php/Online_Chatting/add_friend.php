<?php

// verify user
session_start();
$username = $_POST['fromUser'];

if (!isset($_SESSION['username'][$username])) {
	header("location:login.php");
}

// start handling request
if (empty($_POST['toUser'])){
	http_response_code(400);
	echo "ERROR: Please enter your friend's username.";
} else {
	$fromUser = $_POST['fromUser'];
	$toUser = $_POST['toUser'];

	if (isset($fromUser) && isset($toUser)){
		// Check adding self as a friend.
		if ($fromUser == $toUser) {
			http_response_code(400);
			echo "You can not add yourself as a friend.";
			exit();
		}

		$db = mysqli_connect("localhost","root","1234","gossip_chat");
		if (!$db){
				echo 'Connect Error. Please try again.';
				exit();
		}
		// Check username validity.
		$checkUsername = "select username from login where username='$toUser'";
		$usernameValid = mysqli_query($db, $checkUsername);
		$row = mysqli_fetch_array($usernameValid);

		if (!$row){
			http_response_code(400);
			echo "User $toUser does not exist.";
			mysqli_close($db);
			exit();
		}

		// Check if friendship already exists.
		$checkFriendship = "select * from friendship where fromUser='$fromUser' and toUser='$toUser'" .
				" union select * from friendship where fromUser='$toUser' and toUser='$fromUser'";
		$friendshipResult = mysqli_query($db, $checkFriendship);
		$row = mysqli_fetch_array($friendshipResult);

		if ($row){
			http_response_code(400);
			echo "$fromUser, you are already in friendship with $toUser.";
			mysqli_close($db);
			exit();
		}

		// Insert the friend request into log table
		$query = "insert into log values (0, '$fromUser', '$toUser', 'pending', 1)";
		$result = mysqli_query($db, $query);
		if ($result){
			http_response_code(200);
			echo "$fromUser, your friend request has been sent to $toUser.";
		} else {
			http_response_code(400);
			echo "$fromUser, your friend request has already been sent to $toUser.";
		}
		mysqli_close($db);
	}
}

?>