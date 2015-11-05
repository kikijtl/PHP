<?php

session_start();

if (!empty($_SESSION['m_username'])){
	$username = $_SESSION['m_username'];
	$password = $_SESSION['m_password'];
	//echo $customer_id;
	echo '<p><a href="main.php">Log out</a></p>';
	//echo '<p><a href="order.php">View My Orders</a></p>';
}

	
if(isset($_POST['execute'])){
	if(empty($_POST['query'])){
		echo '<p><font color="red">Please enter your query.</font></p>';
	} else{
		$query_manager = $_POST['query'];
	}
	
	if($query_manager){
		$db = mysqli_connect("localhost","$username","$password","e_book");
		if (!$db){
			echo '<font color="black">','Connect Error', '</font>';
		} else{
			$result = mysqli_multi_query($db, $query_manager);
			if ($result){
				echo '<p>Your query is executed successfully.</p>';
			} else{
				echo '<p><font color="red">Your query is not executed due to some error!</font></p>';
			}
		}
	mysqli_close($db);	
	}


}



?>




<form action="<?php echo $_SERVER['PHP_SELF'];?>"method="post">
<fieldset><legend>Please write your query:</legend>

<p><b>Query:</b><input type="text" name="query" size="180"
value="<?php if (isset($_POST['m_query'])) echo $_POST['m_query'];?>"/></p>

</fieldset>

<div align="center"><input type="submit" name="execute" value="Execute"/></div>

</form><!--End of Form-->


<div align="center"><input type="button" onclick="window.location.href='summary.php'" value="View sales summary for this month"></div>


