<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "wisshare";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("DB ERROR: " . $conn->connect_error);
}
?>