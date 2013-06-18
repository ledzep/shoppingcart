<?php

//error_reporting(1);

function pf_validate_number($value, $function, $redirect) {
	if(isset($value) == TRUE) {
		if(is_numeric($value) == FALSE) {
			$error = 1;
		}
		if(isset($error)) {
			header("Location: " . $redirect);
		}
		else {
			$final = $value;
		}
	}
	else {
		if($function == 'redirect') {
			header("Location: " . $redirect);
		}
		if($function == 'value') {
			$final = 0;
		}
	}
	return $final;
}

function showcart() {
	if($_SESSION['SESS_ORDERNUM']) {
		if($_SESSION['SESS_LOGGEDIN']) {
			$custsql = "SELECT id, status FROM orders WHERE customer_id = " . $_SESSION['SESS_USERID'] . " AND status < 2;";
			$custres = mysql_query($custsql);
			$custrow = mysql_fetch_assoc($custres);
			$itemsql = "SELECT products.* orderitems.*, orderitems.id AS itemid FROM products, orderitems
						WHERE orderitems.product_id = products.id AND order_id = " . $custrow['id'];
			$itemres = mysql_query($itemsql);
			$itemnumrows = mysql_num_rows($itemres);
		}
		/*else {
			$custsql = "SELECT id, status FROM orders WHERE session = '" . session_id() . "' AND status < 2;";
			$custres = mysql_query($custsql);
			$custrow = mysql_fetch_assoc($custres);
			$itemsql = "SELECT products.*, orderitems.*, orderitems.id AS
						itemid FROM products, orderitems WHERE orderitems.product_id =
						products.id AND order_id = " . $custrow['id'];
			$itemres = mysql_query($itemsql);
			$itemnumrows = mysql_num_rows($itemres);
			mysql_error();
		}*/
	}
	else {
		$itemnumrows = 0;
	}
	
	if($itemnumrows == 0) {
		echo "You have not added anything to your shopping cart yet.";
	}
	else {
		echo "<table cellpadding='10'>";
		echo "<tr>";
			echo "<td></td>";
			echo "<td><strong>Item</strong></td>";
			echo "<td><strong>Quantity</strong></td>";
			echo "<td><strong>Unit Price</strong></td>";
			echo "<td><strong>Total Price</strong></td>";
			echo "<td></td>";
		echo "</tr>";
		
		while($itemsrow = mysql_fetch_assoc($itemres)) {
			$quantitytotal = $itemsrow['price'] * $itemsrow['quantity'];
			echo "<tr>";
				echo "<td></td>";
				echo "<td>" . $itemsrow['name'] . "</td>";
				echo "<td>" . $itemsrow['quantity'] . "</td>";
				echo "<td><strong>&dollar;" . sprintf('%.2f', $itemsrow['price']) . "</strong></td>";
				echo "<td><strong>&dollar;" . sprintf('%.2f', $quantitytotal) . "</strong></td>";
				echo "<td>[<a href='" . $config_basedir . "delete.php?id=" . $itemsrow['itemid'] . "'>X</a>]</td>";
			echo "</tr>";
			
			$total = $total + $quantitytotal;
			$totalsql = "UPDATE orders SET total = " . $total . " WHERE id = " . $_SESSION['SESS_ORDERNUM'];
			$totalres = mysql_query($totalsql);
		}
		
		echo "<tr>";
			echo "<td></td>";
			echo "<td></td>";
			echo "<td></td>";
			echo "<td>TOTAL</td>";
			echo "<td><strong>&dollar;" . sprintf('%.2f', $total) . "</strong></td>";
			echo "<td></td>";
		echo "</tr>";
		echo "</table>";
		//echo "<p><a href='checkout-address.php'>Go to the checkout</a></p>";
	}	
			
}			
?>
