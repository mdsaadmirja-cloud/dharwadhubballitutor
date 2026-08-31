<?php
$conn = new mysqli('68.178.149.184','dht','pKw-j-w9','dharwadhubballitutor');

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}
?>