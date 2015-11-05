<?php

session_start();

if (!empty($_SESSION['customer_id'])){
	$customer_id = $_SESSION['customer_id'];
	//echo $customer_id;
	echo '<p><a href="main.php">Log out</a></p>';
	echo '<p><a href="order.php">View My Orders</a></p>';
}

echo '<p><a href="cart.php">View Shopping Cart</a>';

echo '	<a href="search.php">Start Another Search</a></p>';

if ($_SESSION['isbn']){
	$ISBN = $_SESSION['isbn'];

	$db = mysqli_connect("localhost","root","1234","e_book");
	if (!$db){
		echo '<font color="black">','Connect Error', '</font>';
	}
	
	$query = "select * from book where book_id like '%$ISBN%'";
	$result = mysqli_query($db, $query);
	if ($result && mysqli_num_rows($result)>0) {
		echo "<table><tr><th>ISBN</th><th>Name</th><th>Publisher<th>Price</th></tr>";
   		while($row = mysqli_fetch_array($result)) {
        	echo "<tr><td>".$row["book_id"]."</td><td>".$row["book_name"]."</td><td>".$row["publisher"]."</td><td>".$row["price"]."</td>";
			echo '<td><input type="button" value="View Detail" onclick="window.location=\'detail.php?isbn=' . 
				urlencode($row['book_id']) . ' \';" /></td></tr>';
    	}
    echo "</table>";
	mysqli_close($db);
	}	
}


if ($_SESSION['book_name']){
	$book_name = $_SESSION['book_name'];
	$db = mysqli_connect("localhost","root","1234","e_book");
	if (!$db){
		echo '<font color="black">','Connect Error', '</font>';
	}
	
	$query = "select * from book where book_name like '%$book_name%'";
	$result = mysqli_query($db, $query);
	if ($result && mysqli_num_rows($result)>0) {
		echo "<table><tr><th>ISBN</th><th>Name</th><th>Publisher<th>Price</th></tr>";
   		while($row = mysqli_fetch_array($result)) {
        echo "<tr><td>".$row["book_id"]."</td><td>".$row["book_name"]."</td><td>".$row["publisher"]."</td><td>".$row["price"]."</td>";
		echo '<td><input type="button" value="View Detail" onclick="window.location=\'detail.php?isbn=' . 
				urlencode($row['book_id']) . ' \';" /></td></tr>';
    	}
    echo "</table>";
	mysqli_close($db);
	}	
}


if ($_SESSION['keyword']){
	$keyword = $_SESSION['keyword'];
	$db = mysqli_connect("localhost","root","1234","e_book");
	if (!$db){
		echo '<font color="black">','Connect Error', '</font>';
	}
	
	$query = "select * from book where book_name like '%$keyword%' or book_id like '%$keyword%'
			or publisher like '%$keyword%' or description like '%$keyword%' or 
					tag like '%$keyword%'";
	$result = mysqli_query($db, $query);
	if ($result && mysqli_num_rows($result)>0) {
		echo "<table><tr><th>ISBN</th><th>Name</th><th>Publisher<th>Price</th></tr>";
   		while($row = mysqli_fetch_array($result)) {
        echo "<tr><td>".$row["book_id"]."</td><td>".$row["book_name"]."</td><td>".$row["publisher"]."</td><td>".$row["price"]."</td>";
		echo '<td><input type="button" value="View Detail" onclick="window.location=\'detail.php?isbn=' . 
				urlencode($row['book_id']) . ' \';" /></td></tr>';
    	}
    echo "</table>";
	mysqli_close($db);
	}	
}
	
	
?>
