<?php

// verify user
session_start();
$username = $_POST['fromUser'];

if (!isset($_SESSION['username'][$username])) {
	header("location:login.php");
}

$fromUser = $_POST['fromUser'];
$toUser = $_POST['toUser'];

if (isset($fromUser) && isset($toUser)){
	$db = mysqli_connect("localhost","root","1234","gossip_chat");
	if (!$db){
			echo 'Connect Error. Please try again.';
	} else {
		// Check if the username exists.
		$checkUsername = "select username from login where username='$toUser'";
		$usernameValid = mysqli_query($db, $checkUsername);
		$row = mysqli_fetch_array($usernameValid);
		if (!$row){
			http_response_code(400);
			echo "User $toUser does not exist.";
		} else {
			// Check if the two users are currently in friendship.
			$checkFriendship = "select * from friendship where fromUser='$fromUser' and toUser='$toUser'" .
					" union select * from friendship where fromUser='$toUser' and toUser='$fromUser'";
			$friendshipResult = mysqli_query($db, $checkFriendship);
			$row = mysqli_fetch_array($friendshipResult);
			if (!$row){
				http_response_code(400);
				echo "$fromUser, you are not in friendship with $toUser.";
			} else {
				// Delete the user pair from the frienship table.
				$query = "delete from friendship where (fromUser='$fromUser' and toUser='$toUser')" .
						" or (fromUser='$toUser' and toUser='$fromUser')";
				$result = mysqli_query($db, $query);
				if ($result){
					http_response_code(200);
					echo "$toUser has been removed from your friend list.";
				} else {
					http_response_code(400);
					echo "$fromUser, you are not in friendship with $toUser.";
				}
			}
		}
		mysqli_close($db);
	}
}

?>