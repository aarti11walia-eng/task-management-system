<?php
session_start();
include("../config/db.php");

// ✅ SET JSON HEADER
header('Content-Type: application/json');

// ✅ CHECK LOGIN
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "total" => 0,
        "pending" => 0,
        "completed" => 0
    ]);
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ SINGLE OPTIMIZED QUERY
$stmt = $conn->prepare("
    SELECT 
        COUNT(*) AS total,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending,
        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS completed
    FROM tasks
    WHERE user_id = ?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// ✅ SAFE OUTPUT
echo json_encode([
    "total" => (int)($data['total'] ?? 0),
    "pending" => (int)($data['pending'] ?? 0),
    "completed" => (int)($data['completed'] ?? 0)
]);

$stmt->close();
?>