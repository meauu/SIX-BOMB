<?php
$host = "localhost";  // Change to your database host if needed
$username = "root";   // Default username for XAMPP, WAMP, or LAMP
$password = "";       // Default password is empty for local servers
$database = "pos_system";  // Make sure this matches your database name

// Create a database connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check if the connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>