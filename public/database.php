<?php
$servername = "localhost";
$username = "root";
$password = "Sc7bTTc7f%H6rWR@";
$dbname = "hamrofitnessDataware";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

// Close connection (optional, connection closes automatically when the script ends)
$conn->close();
?>
