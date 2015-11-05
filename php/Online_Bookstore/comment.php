<?php


session_start();

if (!empty($_SESSION['customer_id'])){
	$customer_id = $_SESSION['customer_id'];
	//echo $customer_id;
	echo '<p><a href="main.php">Log out</a></p>';
	echo '<p><a href="order.php">View My Orders</a></p>';
}

$ISBN = $_GET['isbn'];

if(isset($_POST['submit'])){
	$message = NULL;

if(empty($_POST['text'])){
	$text = FALSE;
	$message .='<p>Comment is required.</p>';
} else{
	$text = $_POST['text'];
}

if(empty($_POST['rating'])){
	$rating = FALSE;
	$message .='<p>Rating is required.</p>';
} else{
	if($_POST['rating']<1 or $_POST['rating']>5){
		$rating = FALSE;
		$message .='<p>Rating should be 1~5.</p>';
	} else{
		$rating = $_POST['rating'];
	}
	
}

if($text && $rating){
	$db = mysqli_connect("localhost","root","1234","e_book");
	if (!$db){
		echo '<font color="black">','Connect Error', '</font>';
	}
	$query = "insert into comment(customer_id, book_id, text, rating) values('$customer_id','$ISBN','$text','$rating')";
	$result = mysqli_query($db, $query);
	if ($result) {
		echo '<p>Thank you for your comment.</p>';
		echo '<td><input type="button" value="Go Back" onclick="window.location=\'detail.php?isbn=' . 
				urlencode($ISBN) . ' \';" /></td></tr>';
		mysqli_close($db);
		
		
	} else{
		$message = 'You have already commented the book .';
		echo '<td><input type="button" value="Go Back" onclick="window.location=\'detail.php?isbn=' . 
				urlencode($ISBN) . ' \';" /></td></tr>';
	}
	
}

if(isset($message)){
	echo '<font color="red">',$message, '</font>';
}

}



?>


<form action="" method="post">
<fieldset><legend>My Comment:</legend>

<p><b>Comment:</b><input type="text" name="text" size="100" maxlength="100"
value="<?php if (isset($_POST['text'])) echo $_POST['text'];?>"/></p>

<p><b>Rating:</b><input type="text" name="rating" size="1" maxlength="1"
value="<?php if (isset($_POST['rating'])) echo $_POST['rating'];?>"/></p>

</fieldset>

<div align="center"><input type="submit" name="submit" value="Submit"/></div>

</form><!--End of Form-->

