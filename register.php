<?php
require("header.php");

if(isset($_GET['error']) == TRUE) {
	echo "<strong>Please fill in the missing information from the form</strong>";
}
?>

<h2>Register</h2>
To register on the <?php echo 
$config_sitename; ?>, fill in the form below. 
<form action="<?php echo $_SERVER['SCRIPT_NAME']?>" method="POST">
<table>
	<tr>
		<td>Choose your username</td>
		<td><input type="text" name="usernameBox"></td>
	</tr>
	<tr>
		<td>Create a password</td>
		<td><input type="password" name="password"></td>
	</tr>
	<tr>
		<td>Forename</td>
		<td><input type="text" name="forenameBox"></td>
	</tr>
	<tr>
		<td>Surname</td>
		<td><input type="text" name="surnameBox"></td>
	</tr>
	<tr>
		<td>House Number, Street</td>
		<td><input type="text" name="add1Box"></td>
	</tr>
	<tr>
		<td>Town/City</td>
		<td><input type="text" name="add2Box"></td>
	</tr>
	<tr>
		<td>County</td>
		<td><input type="text" name="add3Box"></td>
	</tr>
	<tr>
		<td>Postcode</td>
		<td><input type="text" name="postcodeBox"></td>
	</tr>
	<tr>
		<td>Phone</td>
		<td><input type="text" name="phoneBox"></td>
	</tr>
	<tr>
		<td>Email</td>
		<td><input type="text" name="emailBox"></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" name="submit"
		value="Register!"></td>
	</tr>
</table>
</form>

<?php
if(isset($_POST['submit'])) {
	$user = $_POST['usernameBox'];
	$pass = $_POST['password'];
	$token = sha1($pass);
	$forename = $_POST['forenameBox'];
	$surname = $_POST['surnameBox'];
	$house = $_POST['add1Box'];
	$town = $_POST['add2Box'];
	$country = $_POST['add3Box'];
	$postcode = $_POST['postcodeBox'];
	$phone = $_POST['phoneBox'];
	$email = $_POST['emailBox'];
	
	if(empty($user)||
		empty($pass)||
		empty($forename)||
		empty($surname)||
		empty($house)||
		empty($town)||
		empty($country)||
		empty($postcode)||
		empty($phone)||
		empty($email))
	{
		header("Location: " . $config_basedir . "register.php?error=1");
		exit;
	}
	
	$sql = "SELECT * FROM logins WHERE username = '$user';";
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	if($row['username'] == $user) {
		echo "Username already exists";
	}
	else {
		$custsql = "INSERT INTO customers(forename, surname, add1,
						add2, add3, postcode, phone, email) VALUES('"
						. strip_tags(addslashes(
						$forename)) . "', '"
						. strip_tags(addslashes(
						$surname)) . "', '"
						. strip_tags(addslashes(
						$house)) . "', '"
						. strip_tags(addslashes(
						$town)) . "', '"
						. strip_tags(addslashes(
						$country)) . "', '"
						. strip_tags(addslashes(
						$postcode)) . "', '"
						. strip_tags(addslashes(
						$phone)) . "', '"
						. strip_tags(addslashes(
						$email)) . "')";
		mysql_query($custsql);
		
		$loginsql = "INSERT INTO logins(customer_id, username, password) VALUES('" . mysql_insert_id() . "', '" . strip_tags(addslashes($user)) . "', '$token');";
		mysql_query($loginsql);
		echo "User successfully registered, You may now login";
	}
}
?>

<?php
require("footer.php");
?>

