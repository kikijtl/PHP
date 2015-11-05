<?php

session_start();

if (!empty($_SESSION['m_username'])){
	$username = $_SESSION['m_username'];
	$password = $_SESSION['m_password'];
	//echo $customer_id;
	echo '<p><a href="main.php">Log out</a></p>';
	//echo '<p><a href="order.php">View My Orders</a></p>';
}

echo '<p><b>Sales Summary for This Month</b></p>';

$year = date("Y");
$month = date("m");

$db = mysqli_connect("localhost","$username","$password","e_book");
if (!$db){
	echo '<font color="black">','Connect Error', '</font>';
} else{
	$query_order = "select * from orders where extract(year from order_date)='$year' 
			and extract(month from order_date)='$month'";
	$result_order = mysqli_query($db, $query_order);
	$ids = "";
	$total_book=0;
	$total_amount=0;
	if ($result_order && mysqli_num_rows($result_order)>0) {
		
		while($order_info = mysqli_fetch_array($result_order)) {
			$ids = $ids.$order_info['order_no'].",";
		}
		$ids = rtrim($ids, ',');
		$ids = explode(",", $ids);
 		$ids = "'". implode("', '", $ids) ."'";	
 		//echo $ids;
			
		$query_detail = "SELECT book_id, sum(quantity) as total_quantity FROM order_detail WHERE order_no IN ($ids) GROUP BY book_id";
		$result_detail = mysqli_query($db, $query_detail); 			
		echo "<table><tr><th>ISBN</th><th>Price</th><th>Quantity</th><th>Subtotal</th></tr>";
   		while($summary = mysqli_fetch_array($result_detail)) {
        	echo "<tr><td>".$summary['book_id']."</td>";
        	$total_book += $summary['total_quantity'];
        	$book_id = $summary['book_id'];
        	$book = mysqli_fetch_array(mysqli_query($db, "select price from book where book_id='$book_id'"));
        	$price = $book['price'];
        	$subtotal = $price*$summary['total_quantity'];  
        	$total_amount += $subtotal;      
        	echo "<td>".$price." USD</td><td>".$summary['total_quantity']."</td><td>".$subtotal." USD</td></tr>";
    	}
    	echo "</table>";
	} else{
		echo '<p>No order this month.';
	}
	
	echo "<p><b>Total ".$total_book." books sold.</b></p>";
	echo "<p><b>Total sales income is ".$total_amount." USD.</b></p>";
	
}
	mysqli_close($db);	


?>
