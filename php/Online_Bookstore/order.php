<?php

session_start();

if (!empty($_SESSION['customer_id'])){
	$customer_id = $_SESSION['customer_id'];
	//echo $customer_id;
	echo '<p><a href="main.php">Log out</a></p>';
	//echo '<p><a href="order.php">View My Orders</a></p>';
}

$db = mysqli_connect("localhost","root","1234","e_book");
	if (!$db){
		echo '<font color="black">','Connect Error', '</font>';
	}
	
	$query1 = "SELECT * FROM orders WHERE customer_id='$customer_id'";
	//echo $customer_id;
	$result1 = mysqli_query($db, $query1);
	//$result2 = mysqli_query($db, $query2);
	if ($result1 && mysqli_num_rows($result1)>0) {
		echo "<table><tr><th>Order_no</th><th>Order Date</th></tr>";
   		while($row = mysqli_fetch_array($result1)) {
        	echo "<tr><td>".$row["order_no"]."</td><td>".$row["order_date"]."</td>";
			echo '<td><input type="button" value="View Order Detail" onclick="window.location=\'order_detail.php?order_no=' . 
				urlencode($row['order_no']) . ' \';" /></td></tr>';
    	}
    	echo "</table>";
	} else{
		echo "You have no previous order.";
	}
	mysqli_close($db);



?>
