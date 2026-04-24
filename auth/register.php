<?php
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
        echo "All fields are required!";
        exit();
    }

    // ✅ EMAIL VALIDATION
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format!";
        exit();
    }

    // ✅ PASSWORD LENGTH
    if (strlen($password_raw) < 6) {
        echo "Password must be at least 6 characters!";
        exit();
    }

    // ✅ CHECK DUPLICATE EMAIL
    $checkEmail = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($checkEmail->num_rows > 0) {
        echo "Email already exists!";
        exit();
    }

    // HASH PASSWORD
    $password = password_hash($password_raw, PASSWORD_DEFAULT);

    // DEFAULT ROLE
    $role = $_POST['role'] ?? 'user';

    // 🔐 ONLY ONE ADMIN
    if ($role == 'admin' && $adminExists) {
        echo "Admin already exists!";
        exit();
    }

    // INSERT USER
    $conn->query("INSERT INTO users (name,email,password,role) 
                  VALUES ('$name','$email','$password','$role')");

    header("Location: ../index.php");
    exit();
}
?>