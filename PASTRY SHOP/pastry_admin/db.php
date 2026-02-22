<?php
// pastry_admin/db.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pastry_shop"; // Confirmed database name

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>