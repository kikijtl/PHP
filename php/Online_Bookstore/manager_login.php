<?php

if(isset($_POST['Login'])){
	$message = NULL;

if(empty($_POST['username'])){
	$username = FALSE;
	$message .='<p>User name is required.</p>';
} else{
	$username = $_POST['username'];
}

if(empty($_POST['password'])){
	$password = FALSE;
	$message .='<p>Password is required.</p>';
} else{
	$password = $_POST['password'];
}

if($username&&$password){
	$db = mysqli_connect("localhost","$username","$password","e_book");
	if (!$db){
		echo '<font color="black">','Connect Error', '</font>';
	} else{
		session_start();
		$_SESSION['m_username'] = $username;
		$_SESSION['m_password'] = $password;
		header("location:query.php");
		mysqli_close($db);
		
		
	
	} 
	
}

if(isset($message)){
	echo '<font color="red">',$message, '</font>';
}

}
?>



<form action="<?php echo $_SERVER['PHP_SELF'];?>"method="post">
<fieldset><legend>Manager Login:</legend>

<p><b>User Name:</b><input type="text" name="username" size="15" maxlength="20"
value="<?php if (isset($_POST['username'])) echo $_POST['username'];?>"/></p>

<p><b>Password:</b><input type="password" name="password" size="15" maxlength="20"
value="<?php if (isset($_POST['password'])) echo $_POST['password'];?>"/></p>

</fieldset>

<div align="center"><input type="submit" name="Login" value="Login"/></div>

</form><!--End of Form-->
