<?php
$servername = "localhost";
$username = "cs143";
$password = "";
$dbname = "cs143";

// Connect to database
$db = new mysqli($servername, $username, $password, $dbname);
if ($db->connect_errno > 0) { 
    die('Unable to connect to database [' . $db->connect_error . ']');
}
?>
