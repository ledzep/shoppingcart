<?php
//session_start();
require("header.php");

require("db.php");
require("functions.php");

$validid = pf_validate_number($_GET['id'], "redirect", $config_basedir);

$prodsql = "SELECT * FROM products WHERE id = " . $_GET['id'] . ";";
$prodres = mysql_query($prodsql);

$numrows = mysql_num_rows($prodres);
$prodrow = mysql_fetch_assoc($prodres);

if($numrows == 0) {
	header("Location: " . $config_basedir);
}
else {
	if(isset($_POST['submit'])) {
		if($_SESSION['SESS_ORDERNUM']) {
			$itemsql = "INSERT INTO orderitems(order_id, product_id, quantity)
						VALUES(" . $_SESSION['SESS_ORDERNUM'] . ", " . $_GET['id'] . ", " . $_POST['amountBox'] . ")";
			mysql_query($itemsql);
		}
		else {
			if($_SESSION['SESS_LOGGEDIN']) {
				$sql = "INSERT INTO orders(customer_id, registered, date)
						VALUES(" . $_SESSION['SESS_USERID'] . ", 1, NOW())";
				mysql_query($sql);
				$_SESSION['SESS_ORDERNUM'] = mysql_insert_id();
				
				$itemsql = "INSERT INTO orderitems(order_id, product_id, quantity)
							VALUES(" . $_SESSION['SESS_ORDERNUM'] . ", " . $_GET['id'] . ", " . $_POST['amountBox'] . ")";
				mysql_query($itemsql);
			}
			/*else {
				$sql = "INSERT INTO orders(registered, date, session) VALUES(". "0, NOW(), '" . session_id() . "')";
				mysql_query($sql);
				$_SESSION['SESS_ORDERNUM'] = mysql_insert_id();
				
				$itemsql = "INSERT INTO orderitems(order_id, product_id, quantity) VALUES(" . $_SESSION['SESS_ORDERNUM'] . ", " . $_GET['id'] . ", " . $_POST['amountBox'] . ")";
				mysql_query($itemsql);
			}   If SESS_LOGGEDIN does not exist, the user is not currently logged in
				(they possibly don’t have a user account). As such, an order is created in the
				orders table (using session_id() to get the unique session id) and then the
				item is added to the orderitems table. */
		}
		
		$totalprice = $prodrow['price'] * $_POST['amountBox'];
		
		$updsql = "UPDATE orders SET total = total + " . $totalprice . " WHERE id = " . $_SESSION['SESS_ORDERNUM'] . ";";
		mysql_query($updsql);
		header("Location: " . $config_basedir . "showcart.php");
	}
	else {
		//require("header.php");
		echo "<form action='addtobasket.php?id=" . $_GET['id'] . "' method='POST'>";
		echo "<table cellpadding='10'>";
		
		echo "<tr>";
			echo "<td>"  . $prodrow['name'] . "</td>";
			echo "<td>Select Quantity <select name='amountBox'>";
			
			for($i=1;$i<=100;$i++)
			{
				echo "<option>" . $i . "</option>";
			}
			
			echo "</select></td>";
			echo "<td><strong>&pound;" . sprintf('%.2f', $prodrow['price']) . "</strong></td>";
			
			echo "<td><input type='submit' name='submit' value='Add to basket'></td>";
		echo "</tr>";
		echo "</table>";
		echo "</form>";
	}
}

require("footer.php");
?>

			
			
		

 
				
