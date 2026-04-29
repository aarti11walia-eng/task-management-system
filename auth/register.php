<?php
session_start(); // Session start karna zaroori hai
include("../config/db.php");

// CHECK IF ADMIN EXISTS
$adminCheck = $conn->query("SELECT * FROM users WHERE role='admin'");
$adminExists = $adminCheck->num_rows > 0;

if (isset($_POST['register'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password_raw = $_POST['password'];

    // ✅ EMPTY CHECK
    if (empty($name) || empty($email) || empty($password_raw)) {
        $_SESSION['error_msg'] = "All fields are required!";
        header("Location: ../index.php");
        exit();
    }

    // ✅ EMAIL VALIDATION
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
        $_SESSION['error_msg'] = "Invalid email format!";
        header("Location: ../index.php");
        exit();
    }

    // ✅ CHECK DUPLICATE EMAIL
    // ... baki code same rahega ...

// ✅ CHECK DUPLICATE EMAIL
    $checkEmail = $conn->query("SELECT * FROM users WHERE email='$email'");
    
    if ($checkEmail->num_rows > 0) {
    
        header("Location: ../index.php?error=exists");
        exit();
    }

    // HASH PASSWORD
    $password = password_hash($password_raw, PASSWORD_DEFAULT);
    $role = 'user';

    // INSERT USER
    $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
    
    if ($conn->query($sql)) {
        header("Location: ../index.php?success=1");
    } else {
        header("Location: ../index.php?error=sqlerror");
    }
    exit();
}
?>

    