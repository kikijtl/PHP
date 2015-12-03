<?php

$fromUser = $_GET['fromUser'];
$toUser = $_GET['toUser'];
$username = $fromUser;

// verify user
session_start();
if (!isset($_SESSION['username'][$username])) {
	header("location:login.php");
}

$history = array();

$db = mysqli_connect("localhost","root","1234","gossip_chat");
if (!$db){
	http_response_code(500);
	exit();
}

$query = "select * from log where (toUser='$fromUser' and fromUser='$toUser' and unread=0)" .
		" or (fromUser='$fromUser' and toUser='$toUser') order by timestamp desc limit 10";
$result = mysqli_query($db, $query);

while ($row=mysqli_fetch_array($result)){
	$json = json_encode($row);
	array_push($history, $json);
}

if (!empty($history)){
	echo json_encode(array_reverse($history));
	mysqli_close($db);
	exit();
} else {
	echo "[]";
}

mysqli_close($db);

?>