<?php

// verify user.
session_start();
$username = $_GET['username'];

if (!isset($_SESSION['username'][$username])) {
	header("location:login.php");
}

// Get user's friend list.
$db = mysqli_connect("localhost","root","1234","gossip_chat");

if (!$db){
	echo '<p color="red">Connect Error. Please try again.</p>';
}

$query = "select toUser as friends from friendship where fromUser='$username' " .
		"union select fromUser from friendship where toUser='$username'";
$result = mysqli_query($db, $query);
$friends = array();
while ($row = mysqli_fetch_array($result)){
	array_push($friends, $row['friends']);
};

//if (empty($friends)){
//	$friends = "[]";
//}

mysqli_close($db);


?>

<!DOCTYPE html>
<html>
<head>
	<title>Gossiping</title>
	<link rel="stylesheet" type="text/css" href="css/chat.css">
</head>
<body>
	<div id="leftDiv">
		<div id="username">
			<div id="welcome">Welcome, <?php echo $username?></div>
			<div id="logout"><button id="logoutBtn">Logout</button></div>
		</div>
		<div id="friendListTitle" onclick="hideRightDiv()">My Friends</div>
		<div id="friendList"></div>
		<div id="friendRequest">Friend Request
			<img id="unread_friendRequest" src="pics/unread.png">
		</div>
		<div id="addFriend"><button id="addFriendBtn" onclick="addFriend()">Add A New Friend</button></div>
	</div>
	<div id="rightDiv">
		<div id="windowHeader">
			<div id="sessionName"></div>
			<div id="unfriend"><button id="unfriendBtn" onclick="unfriend()">Unfriend</button></div>
		</div>
		<div id="content"></div>
		<div id="typing">
			<textarea id="typingArea" type="text" name="typingArea" rows="10" maxlength="500"></textarea>
			<button id="sendBtn">Send</button>
		</div>
	</div>
	<script type="text/javascript">
		var username = <?php echo json_encode($username); ?>;
		var friends = <?php echo json_encode($friends); ?>;
	</script>
	<script type="text/javascript" src="script/chat.js"></script>
</body>
</html>