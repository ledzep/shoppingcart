<?php

require("header.php");
require("functions.php");

if(isset($_GET['func']) == TRUE) {
	if(($_GET['func']) != "conf") {
		header("Location: " . $config_basedir);
	}
	$validid = pf_validate_number($_GET['id'], "redirect", $config_basedir);
	
	$funcsql = "UPDATE orders SET status = 10 WHERE id = " . $_GET['id'];
	mysql_query($funcsql);
	
	header("Location: " . $config_basedir . "adminorders.php");
}
else {
	echo "<h1>Outstanding orders</h1>";
	$ordersql = "SELECT * FROM orders WHERE status = 2";
	//printf($ordersql);
	$ordersres = mysql_query($ordersql);
	$numrows = mysql_num_rows($ordersres);
	//printf($numrows);
	
	if($numrows == 0) {
		echo "<strong>No orders</strong>";
	}
	else {
		echo "<table cellspacing=10>";
		
		echo "<tr>";
			echo "<td></td>";
			echo "<td><strong>Order Details</strong></td>";
			echo "<td><strong>Date of Purchase</strong></td>";
			echo "<td><strong>Total Price</strong></td>";
			echo "<td><strong>Payment Type</strong></td>";
			echo "<td></td>";
		echo "</tr>";
		
		while($row = mysql_fetch_assoc($ordersres)) {
			echo "<tr>";
				echo "<td></td>";
				echo "<td>[<a href='adminorderdetails.php?id=" . $row['id'] . "'>View</a>]</td>";
				echo "<td>" . date("D jS F Y g.iA", strtotime($row['date'])) . "</td>";
				echo "<td>&dollar;" . sprintf('%.2f', $row['total']) . "</td>";
				echo "<td>";
				if($row['payment_type'] == 1) {
					echo "PayPal";
				}
				else {
					echo "Cheque";
				}
				echo "</td>";
				echo "<td><a href='adminorders.php?func=conf&id=" . $row['id'] . "'>Confirm Payment</a></td>";
			echo "</tr>";
		}
		echo "</table>";
	}
}

require("footer.php");
?>

				

	
