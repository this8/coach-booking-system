<?php
$servername = "localhost";
$username = "root";
$password = "root";
$db_name = "coach_booking_system";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $db_name);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";

//close the connection
//mysqli_close($conn);
?>