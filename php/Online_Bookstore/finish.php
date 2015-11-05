<?php
session_start();

if (!empty($_SESSION['customer_id'])){
	$customer_id = $_SESSION['customer_id'];
	//echo $customer_id;
	echo '<p><a href="main.php">Log out</a>';
	echo '	<a href="order.php">View My Orders</a></p>';
}



echo '<p><b>Your order has been placed.</b></p>';

$_SESSION['cart_items'] = array();

echo '	<a href="search.php">Start Another Search</a></p>';





?>
