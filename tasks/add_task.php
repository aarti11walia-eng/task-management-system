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
$title    = trim($_POST['title'] ?? '');
$desc     = trim($_POST['description'] ?? '');
$priority = $_POST['priority'] ?? '';
$status   = $_POST['status'] ?? 'pending';
$due      = $_POST['due_date'] ?? '';
$category = trim($_POST['category'] ?? 'General');

// ✅ VALIDATION
if (empty($title) || empty($priority) || empty($due)) {
    echo "Please fill all required fields!";
    exit();
}

// ✅ PREPARED STATEMENT
// Ensure your table columns match these exactly
$stmt = $conn->prepare("INSERT INTO tasks (user_id, title, description, priority, status, due_date, category) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issssss", $user_id, $title, $desc, $priority, $status, $due, $category);

if ($stmt->execute()) {
    echo "success"; 
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>