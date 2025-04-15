<?php
session_start(); // ✅ Start session at the top

include_once './db_connect.php';

// Ensure the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Fetch user details from the database
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $user, $pass);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // ✅ Store user data in session variables
        $_SESSION['username'] = $row['username']; // ✅ Store username
        $_SESSION['user_id'] = $row['id']; // ✅ Store user ID
        $_SESSION['user_type'] = $row['user_type']; // ✅ Store user type

        // ✅ Redirect to Admin Panel
        header("Location: Admin_panel/admin.php");
        exit();
    } else {
        header("Location: index.html?error=Invalid username or password!");
        exit();
    }

    $stmt->close();
}
$conn->close();
?>
