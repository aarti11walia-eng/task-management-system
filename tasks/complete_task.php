<?php
session_start();
include("../config/db.php");

// ✅ CHECK LOGIN
if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized";
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ CHECK ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid Task ID";
    exit();
}

$id = $_GET['id'];

// ✅ PREPARED STATEMENT (SECURE + USER CHECK)
$stmt = $conn->prepare("UPDATE tasks SET status='completed' WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $id, $user_id);

// ✅ EXECUTE
if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo "Task marked as completed";
    } else {
        echo "Task not found or already updated";
    }
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
?>