<?php
include_once '../db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $rating = $_POST['rating'];  // Corrected variable name
    $message = $_POST['comments']; // Corrected variable name

    // Prevent SQL Injection
    $name = $conn->real_escape_string($name);
    $email = $conn->real_escape_string($email);
    $rating = $conn->real_escape_string($rating);
    $message = $conn->real_escape_string($message);

    // SQL Query
    $sql = "INSERT INTO feedback (name, email, experience, message) VALUES ('$name', '$email', '$rating', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('We love your feedback!'); window.location.href='Client_Feedback.html';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
?>