<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pastry_shop"; // Double-check this matches your DB name

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>