<?php

// verify user
session_start();
$username = $_GET['fromUser'];

if (!isset($_SESSION['username'][$username])) {
	header("location:login.php");
}

// start handling request
if (empty($_GET['toUser'])){
	http_response_code(400);
	echo "ERROR: Please enter your friend's username.";
} else {
	$fromUser = $_GET['fromUser'];
	$toUser = $_GET['toUser'];

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
		} else {
			// Check username validity.
			$checkUsername = "select username from login where username='$toUser'";
			$usernameValid = mysqli_query($db, $checkUsername);
			$row = mysqli_fetch_array($usernameValid);

			if (!$row){
				http_response_code(400);
				echo "User $toUser does not exist.";
			} else {
				// Check if friendship already exists.
				$checkFriendship = "select * from friendship where fromUser='$fromUser' and toUser='$toUser'" .
						" union select * from friendship where fromUser='$toUser' and toUser='$fromUser'";
				$friendshipResult = mysqli_query($db, $checkFriendship);
				$row = mysqli_fetch_array($friendshipResult);

				if ($row){
					http_response_code(400);
					echo "$fromUser, you are already in friendship with $toUser.";
				} else {
					// Update friendship.
					$query = "insert into friendship values ('$fromUser', '$toUser')";
					$result = mysqli_query($db, $query);
					if ($result){
						http_response_code(200);
						echo "$fromUser, you are now $toUser's friend.";
					} else {
						http_response_code(400);
						echo "Some error occurred. Please try again.";
					}
				}
			}
			mysqli_close($db);
		}
	}
}

?>