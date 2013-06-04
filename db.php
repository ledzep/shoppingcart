<?php

require("config.php");

// Create a connection 
$db = mysql_connect($dbhost, $dbuser, $dbpassword);

// Select database
mysql_select_db($dbdatabase, $db);

?>


