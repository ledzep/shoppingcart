<?php

session_start();

require("config.php");

unset($_SESSION['SESS_ADMINLOGGEDIN']);

header("Location: " . $config_basedir);
?>
