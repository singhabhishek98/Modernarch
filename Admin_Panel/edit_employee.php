<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

include_once '../db_connect.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid employee ID.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = intval($_POST['employee_id'] ?? 0);
} else {
    $employee_id = intval($_GET['id'] ?? 0);
}

if ($employee_id === 0) {
    die("Invalid employee ID.");
}

$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $position = $_POST['position'] ?? '';
    $mobileNo = $_POST['mobileNo'] ?? '';
    $email = $_POST['email'] ?? '';
    $experience = $_POST['experience'] ?? '';
    $joining_date = $_POST['joining_date'] ?? '';
    $address = $_POST['address'] ?? '';

    // Update employee data
    $sql_update = "UPDATE employee SET name=?, position=?, mobileNo=?, email=?, experience=?, joining_date=?, address=? WHERE id=?";
    $stmt_update = $conn->prepare($sql_update);

    if (!$stmt_update) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt_update->bind_param("sssssssi", $name, $position, $mobileNo, $email, $experience, $joining_date, $address, $employee_id);

    if ($stmt_update->execute()) {
        $stmt_update->close();
        $conn->close();
        header("Location: admin.php?message=Employee updated successfully");
        exit();
    } else {
        $error = "Error updating employee: " . $stmt_update->error;
        $stmt_update->close();
    }
}

// Fetch employee data for form
$sql = "SELECT id, name, position, mobileNo, email, experience, joining_date, address FROM employee WHERE id=?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Employee not found.");
}

$employee = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="container">
        <h2>Edit Employee</h2>
        <?php if (!empty($error)) : ?>
            <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($employee['name']); ?>" required><br><br>

            <label for="position">Position:</label><br>
            <input type="text" id="position" name="position" value="<?php echo htmlspecialchars($employee['position']); ?>" required><br><br>

            <label for="mobileNo">Mobile No:</label><br>
            <input type="text" id="mobileNo" name="mobileNo" value="<?php echo htmlspecialchars($employee['mobileNo']); ?>" required><br><br>

            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($employee['email']); ?>" required><br><br>

            <label for="experience">Experience:</label><br>
            <input type="text" id="experience" name="experience" value="<?php echo htmlspecialchars($employee['experience']); ?>" required><br><br>

            <label for="joining_date">Joining Date:</label><br>
            <input type="date" id="joining_date" name="joining_date" value="<?php echo htmlspecialchars($employee['joining_date']); ?>" required><br><br>

            <label for="address">Address:</label><br>
            <textarea id="address" name="address" rows="4" required><?php echo htmlspecialchars($employee['address']); ?></textarea><br><br>

            <button type="submit" name="submit" class="edit-btn">Update</button>
            <button type="button" onclick="window.location.href='admin.php'">Cancel</button>
        </form>
    </div>
</body>
</html>
