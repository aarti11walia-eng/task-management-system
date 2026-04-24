<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["total"=>0,"pending"=>0,"completed"=>0]);
    exit();
}

$user_id = $_SESSION['user_id'];

// TOTAL
$total_q = $conn->query("SELECT COUNT(*) as count FROM tasks WHERE user_id='$user_id'");
$total = $total_q->fetch_assoc()['count'];

// PENDING
$pending_q = $conn->query("SELECT COUNT(*) as count FROM tasks WHERE user_id='$user_id' AND status='Pending'");
$pending = $pending_q->fetch_assoc()['count'];

// COMPLETED
$completed_q = $conn->query("SELECT COUNT(*) as count FROM tasks WHERE user_id='$user_id' AND status='Completed'");
$completed = $completed_q->fetch_assoc()['count'];

echo json_encode([
    "total" => (int)$total,
    "pending" => (int)$pending,
    "completed" => (int)$completed
]);
?>