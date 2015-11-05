<?php


session_start();

if (!empty($_SESSION['customer_id'])){
	$customer_id = $_SESSION['customer_id'];
	//echo $customer_id;
	echo '<p><a href="main.php">Log out</a></p>';
	echo '<p><a href="order.php">View My Orders</a></p>';
}

if(isset($_POST['SearchID'])){
	$message = NULL;

if(empty($_POST['ISBN'])){
	$ISBN = FALSE;
	$message .='<p>Please input ISBN, book name or keyword.</p>';
} else{
	$ISBN = $_POST['ISBN'];
}

if($ISBN){
	$db = mysqli_connect("localhost","root","1234","e_book");
	if (!$db){
		echo '<font color="black">','Connect Error', '</font>';
	}
	$query1 = "select * from book where book_id like '%$ISBN%'";
	$result = mysqli_query($db, $query1);
	$row = mysqli_fetch_array($result);
	if ($result && mysqli_num_rows($result)>0) {
		//echo '<table>';
		//while($row = mysqli_fetch_array($result)) {
  			$_SESSION['isbn'] = $ISBN;
  			$_SESSION['book_name'] = FALSE;
  			$_SESSION['keyword'] = FALSE;
  			header("location:result.php");
 		//}
 		//echo '</table>';
		mysqli_close($db);		
	} else{
		$message = 'Sorry. The book you are looking for is not in stock.';
	}
	
}

if(isset($message)){
	echo '<font color="red">',$message, '</font>';
}

}
//end of search by ISBN

if(isset($_POST['SearchName'])){
	$message = NULL;

if(empty($_POST['book_name'])){
	$book_name = FALSE;
	$message .='<p>Please input ISBN, book name or keyword.</p>';
} else{
	$book_name = $_POST['book_name'];
}

if($book_name){
	$db = mysqli_connect("localhost","root","1234","e_book");
	if (!$db){
		echo '<font color="black">','Connect Error', '</font>';
	}
	$query2 = "select * from book where book_name like '%$book_name%'";
	$result = mysqli_query($db, $query2);
	$row = mysqli_fetch_array($result);
	if ($result && mysqli_num_rows($result)>0) {
  			$_SESSION['isbn'] = FALSE;
  			$_SESSION['book_name'] = $book_name;
  			$_SESSION['keyword'] = FALSE;
  			header("location:result.php"); 			
  			
  			//echo "<form method='post' action='result2.php'>";
			//echo "<input type='hidden' name='book_name' value='".$row['book_name']."'>";
			//echo "<input type='submit' name='result' value='View results'>";
			//echo "</form>";
		mysqli_close($db);		
	} else{
		$message = 'Sorry. The book you are looking for is not in stock.';
	}
	
}

if(isset($message)){
	echo '<font color="red">',$message, '</font>';
}

}
//end of search by book name

if(isset($_POST['SearchKey'])){
	$message = NULL;

if(empty($_POST['keyword'])){
	$keyword = FALSE;
	$message .='<p>Please input ISBN, book name or keyword.</p>';
} else{
	$keyword = $_POST['keyword'];
}

if($keyword){
	$db = mysqli_connect("localhost","root","1234","e_book");
	if (!$db){
		echo '<font color="black">','Connect Error', '</font>';
	}
	$query3 = "select * from book where book_name like '%$keyword%' or book_id like '%$keyword%'
			or publisher like '%$keyword%' or description like '%$keyword%' or tag like '%$keyword%'";
	$result = mysqli_query($db, $query3);
	$row = mysqli_fetch_array($result);
	if ($result && mysqli_num_rows($result)>0) {
  			$_SESSION['isbn'] = FALSE;
  			$_SESSION['book_name'] = FALSE;
  			$_SESSION['keyword'] = $keyword;
  			header("location:result.php");  			
  			//echo "<form method='post' action='result3.php'>";
			//echo "<input type='hidden' name='keyword' value='".$keyword."'>";
			//echo "<input type='submit' name='result' value='View results'>";
			//echo "</form>";
		mysqli_close($db);		
	} else{
		$message = 'Sorry. The book you are looking for is not in stock.';
	}
	
}

if(isset($message)){
	echo '<font color="red">',$message, '</font>';
}

}
//end of search by book name


?>



<form action="<?php echo $_SERVER['PHP_SELF'];?>"method="post">

<fieldset><legend>Search by ISBN:</legend>
<p><input type="text" name="ISBN" size="20" maxlength="17"
value="<?php if (isset($_POST['ISBN'])) echo $_POST['ISBN'];?>"/></p>
<div align="left"><input type="submit" name="SearchID" value="Search"/></div>

</fieldset>

</form><!--End of Form-->

<form action="<?php echo $_SERVER['PHP_SELF'];?>"method="post">

<fieldset><legend>Search by Book Name:</legend>
<p><input type="text" name="book_name" size="20" maxlength="50"
value="<?php if (isset($_POST['book_name'])) echo $_POST['book_name'];?>"/></p>

<div align="left"><input type="submit" name="SearchName" value="Search"/></div>

</fieldset>

</form><!--End of Form-->

<form action="<?php echo $_SERVER['PHP_SELF'];?>"method="post">

<fieldset><legend>Search by Keyword:</legend>
<p><input type="text" name="keyword" size="20" maxlength="50"
value="<?php if (isset($_POST['keyword'])) echo $_POST['keyword'];?>"/></p>

<div align="left"><input type="submit" name="SearchKey" value="Search"/></div>

</fieldset>

</form><!--End of Form-->

