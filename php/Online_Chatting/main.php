<?php
session_start();
session_destroy();
?>


<!DOCTYPE html>
<html>
<head>
	<title>Gossip Chatting</title>
	<style>
		div {
			size: 100%;
			text-align: center;
			padding: 10px;
		}
		h1 {
			color: #ff4d4d;
			font-family: "Comic Sans MS", cursive, sans-serif;
		}
		button {
			width: 100px;
			height: 35px;
			background-color: #ff4d4d;
			color: #ffffcc;
			font-family: "Comic Sans MS", cursive, sans-serif;
			font-size: 20px;
			margin-top: 30px;
			margin-left: 30px;
			margin-right: 30px;
		}
		#footer {
			margin-top: 50px;
		}
	</style>
</head>
<body>
	<div>
		<h1>Welcome To Gossip</h1>
		<button onclick="window.location.href='register.php'">Register</button>
		<button onclick="window.location.href='login.php'">Login</button>
	</div>
	<div id="footer">
		<img src="pics/footer.jpg">
	</div>
</body>
</html>

