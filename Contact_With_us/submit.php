<?php
include_once '../db_connect.php';

// Securely collect form data
$name = $_POST['Name'] ?? '';
$project_type = $_POST['Project_Type'] ?? '';
$address = $_POST['address'] ?? '';
$mobile = $_POST['mobile'] ?? '';
$message = $_POST['message'] ?? '';

// Prepare SQL statement
$sql = $conn->prepare("INSERT INTO inquiry (Name, Project_Type, Address, Mobile, Message) VALUES (?, ?, ?, ?, ?)");
$sql->bind_param("sssss", $name, $project_type, $address, $mobile, $message);

// Execute query
if ($sql->execute()) {
    echo "<script>alert('New record created successfully'); window.location.href='Contact_With_us.html';</script>";
} else {
    echo "<script>alert('Error: " . $sql->error . "'); window.location.href='Contact_With_us.html';</script>";
}


// Close connection
$sql->close();
$conn->close();
?>