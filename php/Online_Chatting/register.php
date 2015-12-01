<?php

$page_title='Submit';

$message = NULL;

if(isset($_POST['Register'])){
	$message = NULL;

	if(empty($_POST['username'])){
		$username = FALSE;
		$message .='Username is required.<br>';
	} else{
		$username = $_POST['username'];
	}

	if(empty($_POST['password'])){
		$password = FALSE;
		$message .='Password is required.<br>';
	} else{
		if ($_POST['password']==$_POST['confirmPassword']){
			$password = $_POST['password'];
		} else{
			$password = FALSE;
			$message .='Your password did not match the confirmed password.<br>';
		}
	}

	if($username&&$password){
		$db = mysqli_connect("localhost","root","1234","gossip_chat");

		if (!$db){
			echo '<p color="red">Connect Error. Please try again.</p>';
		}

		$query = "INSERT INTO login(username, password)
				VALUES('$username', '$password')";
		$result = mysqli_query($db, $query);

		if ($result) {
			//session start
			session_start();
			$_SESSION['username'][$username] = 1;

			header("location:chat.php");
			mysqli_close($db);

		} else{
			$message = 'The username is used. Please choose another one.';
		}
		mysqli_close($db);
	}

}

?>


<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
	<style>
		h1 {
			margin-bottom: 30px;
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
		#info {
			font-size: 12px;
		}
		#registerBtn {
			margin-top: 20px;
			width: 110px;
			height: 50px;
			font-size: 23px;
			background-color: #ff4d4d;
			color: #ffffff;
			border-color: #ff4d4d;
			margin-bottom: 15px;
			text-align: center;
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
	</style>
</head>

<body>
	<div>
		<h1>Please Enter Your Information</h1>
		<p id="msg"><?php echo $message?></p>
		<p id="info">(Note: All fields are required)</p>
		<form action="<?php echo $_SERVER['PHP_SELF'];?>"method="post">
			<p><b>Username: </b><input type="text" name="username" size="20" maxlength="20"
			value="<?php if (isset($_POST['username'])) echo $_POST['username'];?>"></input></p>
			<p><b>Password: </b><input type="password" name="password" size="20" maxlength="20"
			value="<?php if (isset($_POST['password'])) echo $_POST['password'];?>"></input></p>
			<p><b>Confirm Password: </b><input type="password" name="confirmPassword" size="20" maxlength="20"
			value="<?php if (isset($_POST['confirmPassword'])) echo $_POST['confirmPassword'];?>"></input></p>
			<input id="registerBtn" type="submit" name="Register" value="Register"></input>
		</form>
		<a href="login.php">Already has an account? Just Login.</a>
	</div>
</body>
</html>
