<?php
// Move session_start to the very top before any includes
session_start();
include("../config/db.php");

// 1. Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    exit("unauthorized");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Use the (int) cast for IDs to ensure they are treated as numbers
    $id = (int)$_POST['id'];
    $title = trim($_POST['title']);
    $desc = trim($_POST['description']);
    $cat = trim($_POST['category']);
    $prio = $_POST['priority'];
    $status = $_POST['status'];
    $date = $_POST['due_date'];

    // 2. Prepared Statement
    // Ensuring the user_id matches prevents "ID Guessing" attacks
    $stmt = $conn->prepare("UPDATE tasks SET 
                            title = ?, 
                            description = ?, 
                            category = ?, 
                            priority = ?, 
                            status = ?, 
                            due_date = ? 
                            WHERE id = ? AND user_id = ?");

    // "ssssssii" = 6 strings, 2 integers
    $stmt->bind_param("ssssssii", $title, $desc, $cat, $prio, $status, $date, $id, $user_id);

    if ($stmt->execute()) {
        // Output 'success' in lowercase to match your JS 'data.trim().toLowerCase()' check
        echo "success"; 
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
} else {
    echo "invalid_request";
}
$conn->close();
?>