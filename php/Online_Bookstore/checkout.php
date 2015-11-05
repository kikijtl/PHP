<?php

session_start();

$customer_id = false;
if (!empty($_SESSION['customer_id'])){
	$customer_id = $_SESSION['customer_id'];
	//echo $customer_id;
	echo '<p><a href="main.php">Log out</a>';
	echo '	<a href="order.php">View My Orders</a></p>';
	header("location: payment.php");
}


echo '<p><a href="cart.php">View Shopping Cart</a>';

echo '	<a href="search.php">Start Another Search</a></p>';


if(!$customer_id){
$db = mysqli_connect("localhost","root","1234","e_book");
	if (!$db){
		echo '<font color="black">','Connect Error', '</font>';
	}
 
/*
if(count($_SESSION['cart_items'])==0){
	echo '<p>Nothing in your shopping cart.</p>';
}
*/
/*
$card_no = FALSE;
$expiration = false;
$card_name = false;
*/
$guest_id = false;

/*
if(isset($_POST['submit'])){
	$message = NULL;

if(empty($_POST['card_no'])){
	$card_no = FALSE;
	echo '<p><font color="red">Card number is required.</font></p>';
} else{
	$card_no = $_POST['card_no'];
}

if(empty($_POST['expiration'])){
	$expiration = FALSE;
	echo '<p><font color="red">Expiration date is required.</font></p>';
} else{
	$expiration = $_POST['expiration'];
}

if(empty($_POST['card_name'])){
	$card_name = FALSE;
	echo '<p><font color="red">Name on card is required.</font></p>';
} else{
	$card_name = $_POST['card_name'];
}

}
*/
/*
if ($customer_id) {
	$query_cust = "select * from customer where customer_id='$customer_id'";
	$result_cust = mysqli_query($db, $query_cust);
	$cust_info = mysqli_fetch_array($result_cust);

	

} else {
	
	echo '<form action="" method="post">';
	echo "<fieldset><legend>Please enter your delivery information:</legend>";

	echo '<p><b>First Name:</b><input type="text" name="first_name" size="15" maxlength="20"
		value=""/></p>';

	echo '<p><b>Last Name:</b><input type="text" name="last_name" size="15" maxlength="20"
		value=""/></p>';

	echo '<p><b>Street:</b><input type="text" name="street" size="15" maxlength="50"
		value=""/></p>';

	echo '<p><b>City:</b><input type="text" name="city" size="15" maxlength="20"
		value=""/></p>';

	echo '<p><b>State:</b><input type="text" name="state" size="15" maxlength="20"
		value=""/></p>';

	echo '<p><b>Zipcode:</b><input type="text" name="zipcode" size="15" maxlength="5"
		value=""/></p>';

	echo '<p><b>Email:</b><input type="text" name="email" size="15" maxlength="50"
		value=""/></p>';

	echo '<p><b>Phone:</b><input type="text" name="phone" size="15" maxlength="10"
		value=""/></p>';

	echo '</fieldset>';

	echo '<div align="center"><input type="submit" name="submit_addr" value="Submit"/></div>';

	echo '</form><!--End of Form-->';
	
*/	
	
	$first_name = false;
	$last_name = false;
	$street = false;
	$city = false;
	$state = false;
	$zipcode = false;
	$email = false;
	$phone = false;
	
	
	if(isset($_POST['submit_addr'])){
		$message = NULL;

		if(empty($_POST['first_name'])){
			$first_name = FALSE;
			echo '<p><font color="red">First name is required.</font></p>';
		} else{
			$first_name = $_POST['first_name'];
		}

		if(empty($_POST['last_name'])){
			$last_name = FALSE;
			echo '<p><font color="red">Last name is required.</font></p>';
		} else{
			$last_name = $_POST['last_name'];
		}

		if(empty($_POST['street'])){
			$street = FALSE;
			echo '<p><font color="red">Street is required.</font></p>';} 
		else{
			$street = $_POST['street'];
		}

		if(empty($_POST['city'])){
			$city = FALSE;
			echo '<p><font color="red">City is required.</font></p>';
		} else{
			$city = $_POST['city'];
		}

		if(empty($_POST['state'])){
			$state = FALSE;
			echo '<p><font color="red">State is required.</font></p>';
		} else{
			$state = $_POST['state'];
		}

		if(empty($_POST['zipcode'])){
			$zipcode = FALSE;
			echo '<p><font color="red">Zipcode is required.</font></p>';
		} else{
			$zipcode = $_POST['zipcode'];
		}

		if(empty($_POST['email'])){
			$email = FALSE;
			echo '<p><font color="red">Email is required.</font></p>';
		} else{
			$email = $_POST['email'];
		}

		if(empty($_POST['phone'])){
			$phone = FALSE;
			echo '<p><font color="red">Phone is required.</font></p>';
		} else{
			$phone = $_POST['phone'];
		}

	}
	
	
	if($first_name&&$last_name&&$street&&$city&&$state&&$zipcode&&$email&&$phone)
	{
		$query_add_cust = "INSERT INTO customer(first_name, last_name, customer_street,
			customer_city, customer_state, customer_zipcode, customer_email, customer_phone
			) VALUES('$first_name','$last_name','$street','$city','$state',
			'$zipcode','$email','$phone')";		
			//the ()part after customers can be omitted.
		$result_add_cust = mysqli_query($db, $query_add_cust);
		if ($result_add_cust) {
			$query_guest = "select customer_id from customer where customer_email='$email'";
			$result_guest = mysqli_query($db, $query_guest);
			$guest = mysqli_fetch_array($result_guest);
			$guest_id = $guest['customer_id'];
			$_SESSION['guest_id'] = $guest_id;
			echo '<p>Please enter your payment information.</p>';
			header("location:payment.php");
		} else{
			echo '<p><font color="red">The email is used.</font></p>';
		}
	}
			
}

//}
//End of guest info collection.

/*

if($card_no&&$card_name&&$expiration&&($customer_id or $guest_id)){

if(count($_SESSION['cart_items'])>0){
 	if (!empty($_SESSION['customer_id'])){
 		
 	}
    // get the product ids
    $ids = "";
    foreach($_SESSION['cart_items'] as $ISBN => $quantity){
        //echo $_SESSION['cart_items']['345'];
        //echo $ISBN;
        $ids = $ids . $ISBN . ",";
    }
 	
 	echo $ids;
    // remove the last comma
    $ids = rtrim($ids, ',');
 
    $query_book = "SELECT book_id, stock FROM book WHERE book_id IN ({$ids})";
 	$result_book = mysqli_query($db, $query_book);
        
    $subtotal = $_SESSION['subtotal'];
    echo '<p><b>Subtotal: </b>'.$subtotal.' USD</p>';
    echo '<p><b>Tax Rate: </b>10.00 %</p>';
    echo '<p><b>Shipping Fee: </b>5.00 USD</p>';
    $total = $subtotal*1.1+5;
    echo '<p><b>Total Amount: </b>'.$total.' USD</p>';
    
    
    mysqli_query($db, "START TRANSACTION");
	if ($customer_id) {
		$query_add_pay = "INSERT INTO payment(card_no, expiration, card_name, amount_due,
			customer_id) VALUES('$card_no','$expiration','$card_name','$total','$customer_id')";	
	} else {
		$query_add_pay = "INSERT INTO payment(card_no, expiration, card_name, amount_due,
			customer_id) VALUES('$card_no','$expiration','$card_name','$total','$guest_id')";
	}	
	$result_add_pay = mysqli_query($db, $query_add_pay);
	if (!$result_add_pay){
		$pre_pay = mysqli_fetch_array(mysqli_query($db, "select * from payment where card_no='$card_no'"));
		$cur_amount = $pre_pay['amount_due']+$total;
		$query_upd_pay = "update payment set expiration='$expiration', card_name='$card_name', amount_due='$cur_amount'";
		$result_upd_pay = mysqli_query($db, $query_upd_pay);
	}		
	
	$order_date = date("Y-m-d H:i:s");
	if($customer_id){
		$query_add_order = "insert into orders(order_date, customer_id, tax, card_no)
				 values('$order_date', '$customer_id', '0.1', '$card_no')";
		$result_add_order = mysqli_query($db, $query_add_order);
		$result_get_order = mysqli_query($db, "select order_no from orders where customer_id='$customer_id' 
						and order_date='$order_date'");
	
	
	} else {
		$query_add_order = "insert into orders(order_date, customer_id, tax, card_no)
				 values('$order_date', '$guest_id', '0.1', '$card_no')";
		$result_add_order = mysqli_query($db, $query_add_order);
		$result_get_order = mysqli_query($db, "select order_no from orders where customer_id='$guest_id' 
						and order_date='$order_date'");
	
	}
	
	$order_info = mysqli_fetch_array($result_get_order); 
	
	while ($book_info = mysqli_fetch_array($result_book)){ 
           $book_id = $book_info['book_id'];
           $stock = $book_info['stock'];
           $stock -= $_SESSION['cart_items'][$book_id];
           $query_upd_stock = "update book set stock = '$stock' where book_id='$book_id'";
           $result_upd_stock = mysqli_query($db, $query_upd_stock);
           $cur_order_no = $order_info['order_no'];
           $cur_quantity = $_SESSION['cart_items'][$book_id];
           $query_order_detail = "insert into order_detail(order_no, book_id, quantity)
           		 		values('$cur_order_no', '$book_id', '$cur_quantity')";
           $result_order_detail = mysqli_query($db, $query_order_detail);
           if (!$result_upd_stock or !$result_order_detail){
           		mysli_query($db, "ROLLBACK");
           		echo '<p><font color="red">Sorry. There is a problem in checking out.</font></p>';
           		break;
           }
	if ($result_upd_stock && $result_order_detail){
		
		mysqli_query($db, "COMMIT");
		echo '<p>Your order has been placed.</p>';
		header("location:finish.php"); 
	} else{
		mysli_query($db, "ROLLBACK");
           		echo '<p><font color="red">Sorry. There is a problem in checking out.</font></p>';
           		
	}
           
 
       }
	
	
	
	
	
	
 
}
*/
mysqli_close($db);


//}

?>

<?php
/*
<form action=""method="post">
<fieldset><legend>Please enter your payment information:</legend>

<p><b>Card Number:</b><input type="text" name="card_no" size="16" minlength="16" maxlength="16"
value="<?php if (isset($_POST['card_no'])) echo $_POST['card_no'];?>"/></p>

<p><b>Expiration Date:</b><input type="date" name="expiration" 
value="<?php if (isset($_POST['expiration'])) echo $_POST['expiration'];?>"/></p>

<p><b>Name on Card:</b><input type="text" name="card_name" size="25" maxlength="40"
value="<?php if (isset($_POST['card_name'])) echo $_POST['card_name'];?>"/></p>

</fieldset>

<div align="center"><input type="submit" name="submit" value="Submit"/></div>

</form><!--End of Form-->
*/
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

</fieldset>

<div align="center"><input type="submit" name="submit_addr" value="Submit"/></div>

</form><!--End of Form-->




