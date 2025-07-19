<?php
$host = "localhost";
$dbname = "espresso_lane";
$username = "root";
$password = ""; // or your MySQL root password

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
