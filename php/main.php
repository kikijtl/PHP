<?php
session_start();

session_destroy();

$message_w = '<p>Welcome to E-book Store!</p>';
echo '<font color = "black">', $message_w, '</font>';

?>

<head>
<title>Welcome</title>
</head>

<div align="center"><input type="button" onclick="window.location.href='register.php'" value="Register"></div>

<div align="center"><input type="button" onclick="window.location.href='customer_login.php'" value="Customer Login"></div>

<div align="center"><input type="button" onclick="window.location.href='search.php'" value="Be a Guest"></div>

<div align="right"><input type="button" onclick="window.location.href='manager_login.php'" value="Manager_Login"></div>

