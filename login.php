<?php
require("header.php");
?>

<h1>Customer Login</h1>
Please enter your username and password to 
login into the websites. <br/> If you do not have an account, you can get one for free by <a href="register.php">registering</a>.

<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="POST">
<table>
	<tr>
		<td>Username</td>
		<td><input type="text" name="userBox">
	</tr>
	<tr>
		<td>Password</td>
		<td><input type="password" name="passBox">
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" name="submit" value="log in">
	</tr>
</table>
</form>

<?php

if(isset($_SESSION['SESS_LOGGEDIN']) == TRUE) {
	header("Location: " . $config_basedir);
}

if(isset($_POST['submit'])) {
	$loginsql = "SELECT * FROM logins WHERE
				username = '" . $_POST['userBox'] . "' AND password = '" . $_POST['passBox'] . "'";
	$loginres = mysql_query($loginsql);
	$numrows = mysql_num_rows($loginres);
	
	if($numrows == 1) {
		$loginrow = mysql_fetch_assoc($loginres);
		
		$_SESSION['SESS_LOGGEDIN'] = 1;
		$_SESSION['SESS_USERNAME'] = $loginrow['username'];
		$_SESSION['SESS_USERID'] = $loginrow['id'];
		
		$ordersql = "SELECT id FROM orders WHERE 
					customer_id = " . $_SESSION['SESS_USERID'] . " AND status < 2";
		$orderres = mysql_query($ordersql);
		$orderrow = mysql_fetch_assoc($orderres);	
		
		$_SESSION['SESS_ORDERNUM'] = $orderrow['id'];
		
		/* session variable called SESS_ORDERNUM is set to the id returned from $ordersql query.
		This process can have one of two outcomes:
		■ No order exists. If no order exists in the orders table, SESS_ORDERNUM is not set
		to anything.

		■ An order exists. If an id is returned from the query, SESS_ORDERNUM is set to
		this id. This is useful if the user was selecting items for the shopping cart
		and then logged out. When the user logs in again, the shopping cart contains
		the same items from the previous visit and the user can continue to select
		items. This functionality provides some important continuity.
		*/
		
		header("Location: " . $config_basedir);
	}
	else {
		header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?error=1");
	}
}
?>


<?php 
if(isset($_GET['error'])) {
	echo "<strong>Incorrect username/password</strong>";
}
?>

<?php
require("footer.php");
?>



