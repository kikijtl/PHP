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
	$db = mysqli_connect("localhost","root","1234","e_book");
	if (!$db){
		echo '<font color="black">','Connect Error', '</font>';
	}
	$query = "select password from login where user_name='$username'";
	$result = mysqli_query($db, $query);
	$row = mysqli_fetch_array($result);
	if ($result && $password==$row['password']) {
		$message = 'Login Successfully.';
		echo '<font color="black">',$message, '</font>';
		$q = "select customer_id from customer where user_name='$username'";
		$rs = mysqli_query($db, $q);
		$tmp = mysqli_fetch_array($rs);
		$customer_id = $tmp['customer_id'];
		
		//start session
		session_start();
		//$username=$_POST['username'];
		$_SESSION['customer_id']=$customer_id;
		
		header("location:search.php");
		mysqli_close($db);
		
		
	} else{
		$message = 'Please check your username and password.';
	}
	
}

if(isset($message)){
	echo '<font color="red">',$message, '</font>';
}

}
?>



<form action="<?php echo $_SERVER['PHP_SELF'];?>"method="post">
<fieldset><legend>Customer Login:</legend>

<p><b>User Name:</b><input type="text" name="username" size="15" maxlength="20"
value="<?php if (isset($_POST['username'])) echo $_POST['username'];?>"/></p>

<p><b>Password:</b><input type="password" name="password" size="15" maxlength="20"
value="<?php if (isset($_POST['password'])) echo $_POST['password'];?>"/></p>

</fieldset>

<div align="center"><input type="submit" name="Login" value="Login"/></div>

</form><!--End of Form-->
