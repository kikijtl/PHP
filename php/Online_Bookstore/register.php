<?php

$page_title='Submit';
//include('templates/header.inc');



if(isset($_POST['Register'])){
	$message = NULL;

if(empty($_POST['first_name'])){
	$first_name = FALSE;
	$message .='<p>First name is required.</p>';
} else{
	$first_name = $_POST['first_name'];
}

if(empty($_POST['last_name'])){
	$last_name = FALSE;
	$message .='<p>Last name is required.</p>';
} else{
	$last_name = $_POST['last_name'];
}

if(empty($_POST['street'])){
	$street = FALSE;
	$message .='<p>Street is required.</p>';
} else{
	$street = $_POST['street'];
}

if(empty($_POST['city'])){
	$city = FALSE;
	$message .='<p>City is required.</p>';
} else{
	$city = $_POST['city'];
}

if(empty($_POST['state'])){
	$state = FALSE;
	$message .='<p>State is required.</p>';
} else{
	$state = $_POST['state'];
}

if(empty($_POST['zipcode'])){
	$zipcode = FALSE;
	$message .='<p>Zipcode is required.</p>';
} else{
	$zipcode = $_POST['zipcode'];
}

if(empty($_POST['email'])){
	$email = FALSE;
	$message .='<p>Email is required.</p>';
} else{
	$email = $_POST['email'];
}

if(empty($_POST['phone'])){
	$phone = FALSE;
	$message .='<p>Phone is required.</p>';
} else{
	$phone = $_POST['phone'];
}

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
	if ($_POST['password']==$_POST['c_password']){
		$password = $_POST['password'];
	} else{
		$password = FALSE;
		$message .='<p>Your password did not match the confirmed password.</p>';
	}
	
}



if($first_name&&$last_name&&$street&&$city&&$state&&$zipcode&&$email&&$phone&&$username&&$password)
{
	$db = mysqli_connect("localhost","root","1234","e_book");
	
	if (!$db){
		echo '<font color="black">','Connect Error', '</font>';
	}
	
	mysqli_query($db, "START TRANSACTION");
	$query1 = "INSERT INTO customer(first_name, last_name, customer_street,
			customer_city, customer_state, customer_zipcode, customer_email, customer_phone,
			user_name) VALUES('$first_name','$last_name','$street','$city','$state',
			'$zipcode','$email','$phone','$username')";		
			//the ()part after customers can be omitted.
	$result1 = mysqli_query($db, $query1);
	$query2 = "INSERT INTO login(user_name, password)
			VALUES('$username', '$password')";
	$result2 = mysqli_query($db, $query2);
	if ($result1 && $result2) {
    	mysqli_query($db, "COMMIT");
	} else {        
    	mysqli_query($db, "ROLLBACK");
	}
	if ($result1 && $result2) {
		$message = 'Registered Successfully.';
		echo '<font>',$message, '</font>';
		$query3 = "select customer_id from customer where user_name='$username'";
		$result3 = mysqli_query($db, $query3);
		$row = mysqli_fetch_array($result3);
		$customer_id = $row['customer_id'];
		//echo $customer_id;
		
		//session start
		session_start();
		$_SESSION['customer_id']=$customer_id;
		
		header("location:search.php");
		mysqli_close($db);
		
	} else{
		$message = 'The username or the email is used.';
	}
	
}

}


if(isset($message)){

	echo '<font color="red">',$message, '</font>';
}

?>




<form action="<?php echo $_SERVER['PHP_SELF'];?>"method="post">
<fieldset><legend>Please enter your information:</legend>

<p><b>First Name:</b><input type="text" name="first_name" size="15" maxlength="20"
value="<?php if (isset($_POST['first_name'])) echo $_POST['first_name'];?>"/></p>

<p><b>Last Name:</b><input type="text" name="last_name" size="15" maxlength="20"
value="<?php if (isset($_POST['last_name'])) echo $_POST['last_name'];?>"/></p>

<p><b>Street:</b><input type="text" name="street" size="15" maxlength="50"
value="<?php if (isset($_POST['street'])) echo $_POST['street'];?>"/></p>

<p><b>City:</b><input type="text" name="city" size="15" maxlength="20"
value="<?php if (isset($_POST['city'])) echo $_POST['city'];?>"/></p>

<p><b>State:</b><input type="text" name="state" size="15" maxlength="20"
value="<?php if (isset($_POST['state'])) echo $_POST['state'];?>"/></p>

<p><b>Zipcode:</b><input type="text" name="zipcode" size="15" maxlength="5"
value="<?php if (isset($_POST['zipcode'])) echo $_POST['zipcode'];?>"/></p>

<p><b>Email:</b><input type="text" name="email" size="15" maxlength="50"
value="<?php if (isset($_POST['email'])) echo $_POST['email'];?>"/></p>

<p><b>Phone:</b><input type="text" name="phone" size="15" maxlength="10"
value="<?php if (isset($_POST['phone'])) echo $_POST['phone'];?>"/></p>

<p><b>User Name:</b><input type="text" name="username" size="15" maxlength="20"
value="<?php if (isset($_POST['username'])) echo $_POST['username'];?>"/></p>

<p><b>Password:</b><input type="password" name="password" size="15" maxlength="20"
value="<?php if (isset($_POST['password'])) echo $_POST['password'];?>"/></p>

<p><b>Confirm Password:</b><input type="password" name="c_password" size="15" maxlength="20"
value="<?php if (isset($_POST['c_password'])) echo $_POST['c_password'];?>"/></p>

</fieldset>

<div align="center"><input type="submit" name="Register" value="Register"/></div>

</form><!--End of Form-->




<?php

//include('templates/footer.inc');

?>