<?php
session_start();
include("../config/db.php");

// ✅ CHECK LOGIN
if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized";
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ GET DATA SAFELY
$title = trim($_POST['title'] ?? '');
$desc = trim($_POST['description'] ?? '');
$priority = $_POST['priority'] ?? '';
$status = $_POST['status'] ?? '';
$due = $_POST['due_date'] ?? '';
$category = trim($_POST['category'] ?? '');

// ✅ VALIDATION
if (empty($title) || empty($priority) || empty($status) || empty($due)) {
    echo "Please fill all required fields!";
    exit();
}

// ✅ PREPARED STATEMENT (SECURE)
$stmt = $conn->prepare("INSERT INTO tasks 
(user_id, title, description, priority, status, due_date, category) 
VALUES (?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("issssss", $user_id, $title, $desc, $priority, $status, $due, $category);

// ✅ EXECUTE
if ($stmt->execute()) {
    echo "Task Added Successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
?>