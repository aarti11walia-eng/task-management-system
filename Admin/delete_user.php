<?php
session_start();
include("../config/db.php");

// SECURITY CHECK
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// GET USER ID
if (!isset($_GET['id'])) {
    header("Location: users.php");
    exit();
}

$id = $_GET['id'];

// PREVENT ADMIN DELETE
$check = $conn->query("SELECT role FROM users WHERE id='$id'");
$data = $check->fetch_assoc();

if ($data['role'] == 'admin') {
    echo "Admin cannot be deleted!";
    exit();
}

// DELETE USER
$conn->query("DELETE FROM users WHERE id='$id'");

// REDIRECT BACK
header("Location: users.php");
exit();
?>