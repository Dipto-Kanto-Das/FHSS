<?php
$host = "localhost";
$user = "root";        // XAMPP default
$password = "";        // XAMPP default
$dbname = "school_db";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
