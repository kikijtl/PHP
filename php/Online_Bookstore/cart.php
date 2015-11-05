<?php

session_start();

if (!empty($_SESSION['customer_id'])){
	$customer_id = $_SESSION['customer_id'];
	//echo $customer_id;
	echo '<p><a href="main.php">Log out</a></p>';
	echo '<p><a href="order.php">View My Orders</a></p>';
}

//echo '<p><a href="cart.php">View Shopping Cart</a>';

echo '	<a href="search.php">Start Another Search</a></p>';

//print_r( array_values($_SESSION['cart_items']));

$db = mysqli_connect("localhost","root","1234","e_book");
	if (!$db){
		echo '<font color="black">','Connect Error', '</font>';
	}
 
$action = isset($_GET['action']) ? $_GET['action'] : "";

if($action=='removed'){
    echo "<div class='alert alert-info'>";
        $ISBN = isset($_GET['isbn']) ? $_GET['isbn'] : "";
        unset($_SESSION['cart_items'][$ISBN]);
        echo "Removed from your shopping cart.";
        echo '<td><input type="button" value="Go back to search Result" onclick="window.location=\'result.php\';" /></td></tr>';
    echo "</div>";
}
 
else if($action=='quantity_updated'){
    echo "<div class='alert alert-info'>";
        echo "Quantity updated!";
    echo "</div>";
}

if(count($_SESSION['cart_items'])==0){
	echo '<p>Nothing in your shopping cart.</p>';
}

 
if(count($_SESSION['cart_items'])>0){
 
    // get the product ids
    $ids = "";
    foreach($_SESSION['cart_items'] as $ISBN=>$quantity){
        $ids = $ids . $ISBN . ",";
        //echo $ISBN;
    }
 
    // remove the last comma
    $ids = rtrim($ids, ',');
    //echo $ids;
 
    //start table
    echo "<table class='table table-hover table-responsive table-bordered'>";
 
        // our table heading
        echo "<tr>";
            echo "<th>ISBN</th>";
            echo "<th class='textAlignLeft'>Book Name</th>";
            echo "<th>Price(USD)</th>";
            echo "<th>Quantity</th>";
            echo "<th>Action</th>";
        echo "</tr>";
 		
 		$ids = explode(",", $ids);
 		$ids = "'". implode("', '", $ids) ."'";
        $query = "SELECT * FROM book WHERE book_id IN ($ids)";
 		$result = mysqli_query($db, $query);
        
        $subtotal_price=0;
        while ($row = mysqli_fetch_array($result)){ 
            echo "<tr>";
                echo "<td>".$row['book_id']."</td>";
                echo "<td>".$row['book_name']."</td>";
                echo "<td>".$row['price']." USD</td>";
                echo "<td>".$_SESSION['cart_items'][$row['book_id']]."</td>";
                echo "<td>";
                    echo "<a href='cart.php?action=removed&isbn={$row['book_id']}' class='btn btn-danger'>";
                        echo "<span class='glyphicon glyphicon-remove'></span> Remove from cart";
                    echo "</a>";
                echo "</td>";
            echo "</tr>";
 
            $subtotal_price += $row['price']*$_SESSION['cart_items'][$row['book_id']];
        }
        
        $_SESSION['subtotal'] = $subtotal_price;
 
        echo "<tr>";
                echo "<td><b>Subtotal</b></td>";
                echo "<td>{$subtotal_price} USD</td>";
                echo "<td>";
                    echo "<a href='checkout.php' class='btn btn-success'>";
                        echo "<span class='glyphicon glyphicon-shopping-cart'></span> Checkout";
                    echo "</a>";
                echo "</td>";
            echo "</tr>";
 
    echo "</table>";
}
 

mysqli_close($db);



?>
