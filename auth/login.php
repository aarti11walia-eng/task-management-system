<?php
session_start();
include("../config/db.php");

// GET INPUTS
$email = $_POST['email'];
$password = $_POST['password'];

// REQUIRED CHECK
if (empty($email) || empty($password)) {
    echo "All fields are required";
    exit();
}

// ✅ FULL EMAIL VALIDATION (STANDARD PHP WAY)
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email format";
    exit();
}

// FETCH USER
$result = $conn->query("SELECT * FROM users WHERE email='$email'");
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];

    if ($user['role'] == 'admin') {
        header("Location: ../admin/dashboard.php");
    } else {
        header("Location: ../dashboard.php");
    }
    exit();

} else {
    echo "Invalid login credentials";
}
?>