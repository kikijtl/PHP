<?php


session_start();

if (!empty($_SESSION['customer_id'])){
	$customer_id = $_SESSION['customer_id'];
	//echo $customer_id;
	echo '<p><a href="main.php">Log out</a>';
	echo '	<a href="order.php">View My Orders</a></p>';
}

//session_write_close();

echo '<p><a href="cart.php">View Shopping Cart</a>';

echo '	<a href="search.php">Start Another Search</a></p>';

$ISBN = $_GET['isbn'];

$db = mysqli_connect("localhost","root","1234","e_book");
	if (!$db){
		echo '<font color="black">','Connect Error', '</font>';
	}
	
	$query1 = "select * from book where book_id='$ISBN'";
	$query2 = "select text, rating from comment where book_id='$ISBN'";
	$result1 = mysqli_query($db, $query1);
	$result2 = mysqli_query($db, $query2);
	$row1 = mysqli_fetch_array($result1);
	//book info display
	echo "<table>";
    echo "<tr><th>ISBN:</th><td>".$row1["book_id"]."</td></tr>";
	echo "<tr><th>Book Name:</th><td>".$row1["book_name"]."</td></tr>";
	echo "<tr><th>Publisher:</th><td>".$row1["publisher"]."</td></tr>";
	echo "<tr><th>Price:</th><td>".$row1["price"]."USD</td></tr>";
	echo "<tr><th>Picture:</th><td>".'<img src="data:image/jpeg;base64,'
		.base64_encode( $row1['picture'] ).'" width="300" height="400" />'."</td></tr>";
	echo "<tr><th>Description:</th><td>".$row1["description"]."</td></tr>";
	echo "<tr><th>Tag:</th><td>".$row1["tag"]."</td></tr>";
	echo "<tr><th>Stock:</th><td>".$row1["stock"]."</td></tr>";
	echo "</table>";
	



?>


<form action=""method="post">

<fieldset><legend>Buy The Book:</legend>
<p>Quantity:<input type="number" name="quantity" size="3" maxlength="3" min="1"
max="<?php if (isset($row1['stock'])) echo $row1['stock'];?>"/>

<input type="submit" name="add_cart" value="Add to Cart"/></p>

</fieldset>

</form><!--End of Form-->


<?php
//Add to cart function



//session_start();
 
// get the product id
$book_name = $row1['book_name'];

if(isset($_POST['add_cart'])){
	$message = NULL;

if(empty($_POST['quantity'])){
	$quantity = 0;
	echo '<p><font color="red">Please enter the quantity field!</font></p>';
} else{
	if($_POST['quantity']<1 or $_POST['quantity']>$row1['stock']){
		$quantity = 0;
		echo '<p><font color="red">Please enter valid quantity.</font></p>';
	} else{
		$quantity = $_POST['quantity'];
	
		if(!isset($_SESSION['cart_items'])){
    	$_SESSION['cart_items'] = array();
		}
 
		// check if the item is in the array, if it is, do not add
		if(array_key_exists($ISBN, $_SESSION['cart_items'])){
    		echo '<p><font color="red">The book is already in your shopping cart.</font></p>';
		}
 
		// else, add the item to the array
		else{
    		$_SESSION['cart_items'][$ISBN]=$quantity;
 			echo '<p>The book has been added to your shopping cart successfully.</p>';
		}
	
	
	}
}

}

echo '<td><input type="button" value="Go back to search Result" onclick="window.location=\'result.php\';" /></td></tr>'; 
/*
 * check if the 'cart' session array was created
 * if it is NOT, create the 'cart' session array
 */

?>	
	
	
	
<?php	
	
	//suggestion display
	$tag = $row1['tag'];
	$query3 = "select * from book where tag='$tag' and book_id<>'$ISBN' limit 5";
	$result3 = mysqli_query($db, $query3);
	echo "<p></p><p></p><p></p><p></p><p></p>";
	echo "<table>";
	if ($result3 && mysqli_num_rows($result3)>0) {
		echo "<tr><th>You May Also Like:</th></tr>";
		echo "<table><tr><th>ISBN</th><th>Name</th><th>Publisher<th>Price</th></tr>";
   		while($row3 = mysqli_fetch_array($result3)) {
        	echo "<tr><td>".$row3["book_id"]."</td><td>".$row3["book_name"]."</td><td>".$row3["publisher"]."</td><td>".$row3["price"]."</td>";
			echo '<td><input type="button" value="View Detail" onclick="window.location=\'detail.php?isbn=' . 
				urlencode($row3['book_id']) . ' \';" /></td></tr>';
    	}
	}
    echo "</table>";
	
//Make comment
if (!empty($_SESSION['customer_id'])){
	$customer_id = $_SESSION['customer_id'];
	//echo $customer_id;
	echo "<p></p><p></p><p></p><p></p><p></p>";
	echo '<p><input type="button" value="My Comment" onclick="window.location=\'comment.php?isbn=' . 
				urlencode($ISBN) . ' \';" /></p>';
}	


	//comment display	
	echo "<table><tr><th>Ratings</th><th>Comments</th></tr>";
   		while($row2 = mysqli_fetch_array($result2)) {
        echo "<tr><td>".$row2["rating"]."</td><td>".$row2["text"]."</td>";
    	}
    echo "</table>";




mysqli_close($db);

?>




