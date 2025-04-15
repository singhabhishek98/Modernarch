<?php
include_once '../db_connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT project_image FROM projects WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($imageData);
    $stmt->fetch();
    $stmt->close();
    $conn->close();

    header("Content-Type: image/jpeg"); // Change if using PNG or another format
    echo $imageData;
}
?>
