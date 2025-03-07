<?php
session_start();
$conn = new mysqli("localhost", "root", "", "auth_system");

if ($conn->connect_error) {
    die(json_encode(["success" => false, "error" => "Connection failed: " . $conn->connect_error]));
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['query_id'])) {
    $query_id = intval($_POST['query_id']);
    
    $stmt = $conn->prepare("DELETE FROM queries WHERE id = ?");
    $stmt->bind_param("i", $query_id);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Failed to delete query."]);
    }
    
    $stmt->close();
} else {
    echo json_encode(["success" => false, "error" => "Invalid request."]);
}

$conn->close();
?>
