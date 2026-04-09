<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "wisshare";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Erreur connexion: " . $conn->connect_error);
}
?>