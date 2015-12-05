<?php
$message = NULL;

if(isset($_POST['Login'])){
	$message = NULL;

	// Check if the input areas are empty.
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

	// Check if username and password match.
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
	<link rel="stylesheet" type="text/css" href="css/login.css">
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
