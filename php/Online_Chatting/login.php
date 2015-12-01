<?php
$message = NULL;

if(isset($_POST['Login'])){
	$message = NULL;

	if(empty($_POST['username'])){
		$username = FALSE;
		$message .='User name is required.<br>';
	} else{
		$username = $_POST['username'];
	}

	if(empty($_POST['password'])){
		$password = FALSE;
		$message .='Password is required.<br>';
	} else{
		$password = $_POST['password'];
	}

	if($username&&$password){
		$db = mysqli_connect("localhost","root","1234","gossip_chat");

		if (!$db){
			echo '<p color="red">Connect Error. Please try again.</p>';
		}

		$query = "select username from active_user where username='$username'";
		$result = mysqli_query($db, $query);
		$row = mysqli_fetch_array($result);
		if ($row){
			$message = 'The user is already logged in.';
		} else {

			$query = "select password from login where username='$username'";
			$result = mysqli_query($db, $query);

			if ($result) {$row = mysqli_fetch_array($result);}
			if ($result && $password==$row['password']) {
				//start session
				session_start();
				$_SESSION['username'][$username] = 1;

				// add user to table active_user
				$query = "insert into active_user values ('$username')";
				$result = mysqli_query($db, $query);

				header("location:chat.php?username=".$username);

			} else{
				$message = 'Please check your username and password.';
			}
		}
		mysqli_close($db);
	}

}

?>


<!DOCTYPE html>
<html>
<head>
	<title>Gossip</title>
	<style>
		h1 {
			margin-bottom: 30px;
		}
		h2 {
			margin-bottom: 25px;
		}
		div {
			color: #ff4d4d;
			text-align: center;
			size: 100%;
			font-family: "Comic Sans MS", cursive, sans-serif;
		}
		form {
			font-size: 20px;
		}
		p {
			margin-bottom: 10px;
		}
		input {
			width: 150px;
			height: 20px;
			color: #ff4d4d;
			font-family: "Comic Sans MS", cursive, sans-serif;
			font-size: 15px;
			border-style: solid;
			border-color: #ff9999;
		}
		#loginBtn {
			margin-top: 20px;
			width: 100px;
			height: 50px;
			font-size: 23px;
			background-color: #ff4d4d;
			color: #ffffff;
			border-color: #ff4d4d;
			margin-bottom: 15px;
		}
		#msg {
			color: #660033;
			font-size: 15px;
			font: bold;
		}
		a {
			font-size: 15px;
			color: #ff9999;
		}
		#footer {
			margin-top: 50px;
		}
	</style>
</head>
<body>
	<div>
		<h1>Welcome to Gossip</h1>
		<h2>Please Login</h2>
		<p id="msg"><?php echo $message?></p>
		<form action="<?php echo $_SERVER['PHP_SELF'];?>"method="post">
			<p><b>Username: </b><input type="text" name="username" size="20" maxlength="20"
			value="<?php if (isset($_POST['username'])) echo $_POST['username'];?>"></input></p>
			<p><b>Password: </b><input type="password" name="password" size="20" maxlength="20"
			value="<?php if (isset($_POST['password'])) echo $_POST['password'];?>"></input></p>
			<input id="loginBtn" type="submit" name="Login" value="Login"></input>
		</form>
		<a href="register.php">First Time User? Please Register.</a>
	</div>
	<div id="footer">
		<img src="pics/footer.jpg">
	</div>
</body>
<html>
