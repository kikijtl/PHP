<?php

$order_no = $_GET['order_no'];

session_start();

if (!empty($_SESSION['customer_id'])){
	$customer_id = $_SESSION['customer_id'];
	//echo $customer_id;
	echo '<p><a href="main.php">Log out</a></p>';
	echo '<p><a href="order.php">View My Orders</a></p>';
}



$db = mysqli_connect("localhost","root","1234","e_book");
	if (!$db){
		echo '<font color="black">','Connect Error', '</font>';
	}
	
	$query1 = "SELECT * FROM orders WHERE order_no='$order_no'";
	$result1 = mysqli_query($db, $query1);
	//result1 has the info such as shipping method, coupon ocde.
	
	$query2 = "select * from order_detail where order_no='$order_no'";
	$result2 = mysqli_query($db, $query2);
	//result2 has the info such as book_id, quantities.
		
	if ($result2 && mysqli_num_rows($result2)>0) {
		echo "<p><b>Your Order Number: ".$order_no."</b></p>";
		echo "<p></p><p></p>";
		echo "<p><b>Order Details</b></p>";
		echo "<table><tr><th>ISBN</th><th>Quantity</th><th>Price(USD)</th><th>Subtotal(USD)</th></tr>";
   		$subtotal = array();
   		while($row2 = mysqli_fetch_array($result2)) {
        	$ISBN = $row2['book_id'];
        	$query3 = "select price from book where book_id='$ISBN'";
        	$result3 = mysqli_query($db, $query3);
        	//result3 has the info about a specific book
        	
        	$row3 = mysqli_fetch_array($result3);
        	$r_price = $row3['price']*$row2['quantity'];
        	array_push($subtotal, $r_price);
        	echo "<tr><td>".$row2["book_id"]."</td><td>".$row2["quantity"]."</td><td>".$row3['price']."USD</td><td>".$r_price."USD</td>";
			echo '<td><input type="button" value="View Book Detail and Comment" onclick="window.location=\'detail.php?isbn=' . 
				urlencode($row2['book_id']) . ' \';" /></td></tr>';
    	}
    	echo "</table>";
    	
    	$query4 = "select * from customer where customer_id='$customer_id'";
    	$result4 = mysqli_query($db, $query4);
    	$row4 = mysqli_fetch_array($result4);
    	//result4 has the info about the customer
    	
    	$result5 = mysqli_query($db, $query1);
    	$row5 = mysqli_fetch_array($result5);
    	//result5 has the general info of this order.
    	
    	echo "<p></p><p></p>";
    	echo "<p><b>Order Summary</b></p>";
    	echo "<table>";
    	echo "<tr><td>Tax:</td><td>".$row5['tax']."</td></tr>";
    	//$shipping_method = $row5['shipping_method'];
    	//$ship = mysqli_fetch_array(mysqli_query($db, 
		//		"select * from shipment where shipping_method = '$shipping_method'"));
    	$shipping_fee = 5;
    	echo "<tr><td>Shipping Fee:</td><td>".$shipping_fee." USD</td></tr>";

    	
    	//echo "<p>Shipping method</p>";
    	//echo "<input type='radio' name='shoprunner' value='shoprunner'> Shop Runner";
    	//echo "<br>";
    	//echo "<input type='radio' name='standard' value='standard'> Standard";
    	
    	$total = array_sum($subtotal)*(1+$row5['tax'])+$shipping_fee;
    	echo "<tr><td>Total Amount:</td><td>".$total." USD</td></tr>";
    	
    	$begin = substr($row5['card_no'],0,3);
    	$end = substr($row5['card_no'], 12,15);
    	echo "<tr><td>Paid By:</td><td>".$begin." **** **** ".$end."</td></tr>";
    	
    	echo "<tr><td>Shipping Address:</td></tr>";
    	echo "<tr><td>".$row4['customer_street'].", ".$row4['customer_city'].", ".$row4['customer_state'].", "
    	.$row4['customer_zipcode']."</td></tr>";
    	//echo "<tr><td>Shipping Method:</td><td>".$shipping_method."</td></tr>";

    	
    	
	} else{
		echo "You have no previous order.";
	}
	mysqli_close($db);


?>
