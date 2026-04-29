<?php
session_start();
include("../config/db.php");

// 1. GET INPUTS
$email = trim($_POST['email']);
$password = $_POST['password'];

// 2. REQUIRED CHECK
if (empty($email) || empty($password)) {
    echo "<script>alert('All fields are required'); window.history.back();</script>";
    exit();
}

// 3. FULL EMAIL VALIDATION
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
    echo "<script>alert('Invalid email format or garbage characters detected!'); window.history.back();</script>";
    exit();
}

// 4. FETCH USER (Including Soft Delete Check)
// We add 'AND deleted_at IS NULL' to ensure deactivated users cannot log in.
$result = $conn->query("SELECT * FROM users WHERE email='$email' AND deleted_at IS NULL");
$user = $result->fetch_assoc();

// 5. VERIFY & REDIRECT
if ($user && password_verify($password, $user['password'])) {

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['name'] = $user['name'];

    if ($user['role'] == 'admin') {
        header("Location: ../admin/dashboard.php");
    } else {
        header("Location: ../dashboard.php");
    }
    exit();

} else {
    // THIS PART NOW USES THE JAVASCRIPT ALERT
    echo "<script>
            alert('Invalid login credentials .'); 
            window.history.back();
          </script>";
    exit();
}
?>