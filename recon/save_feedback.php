<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
date_default_timezone_set('Asia/Kolkata');
// DB CONFIG
$host = "68.178.149.184";
$db   = "dharwadhubballitutor";
$user = "dht";
$pass = "pKw-j-w9";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "DB connection failed"]);
    exit;
}

// Get POST data
$name = $_POST['name'] ?? '';
$phone = $_POST['phone'] ?? '';
$visitorType = $_POST['visitorType'] ?? '';
$propertyType = $_POST['propertyType'] ?? '';
$rating = $_POST['rating'] ?? '';

error_log($name);

if ($phone == '') {
    echo json_encode(["status" => "error", "message" => "Phone required"]);
    exit;
}

// Insert
$stmt = $conn->prepare(
    "INSERT INTO ricon_feedback 
    (name, phone, visitor_type, property_type, rating)
    VALUES (?, ?, ?, ?, ?)"
);

$stmt->bind_param(
    "ssssi",
    $name,
    $phone,
    $visitorType,
    $propertyType,
    $rating
);

$stmt->execute();

$stmt->close();
$conn->close();

echo json_encode(["status" => "success"]);
