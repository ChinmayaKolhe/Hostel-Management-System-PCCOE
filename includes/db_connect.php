<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$database = "hostel";
$port = 3308; // Specify the port

$conn = new mysqli($servername, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
