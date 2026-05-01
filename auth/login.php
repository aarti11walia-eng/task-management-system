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

/** 
 * 3. STRICT EMAIL VALIDATION 
 * Matches your registration logic: 
 * - Must contain letters
 * - Must end exactly in .com
 */
$emailPattern = "/^(?=.*[a-zA-Z])[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.com$/";
if (!preg_match($emailPattern, $email)) {
    echo "<script>alert('Invalid email format! Must contain letters and end in .com'); window.history.back();</script>";
    exit();
}

// 4. FETCH USER (SECURE VERSION using Prepared Statements)
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND deleted_at IS NULL");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// 5. VERIFY & REDIRECT
if ($user && password_verify($password, $user['password'])) {

    // IMPORTANT: Check if the user is verified (if you use OTP status in DB)
    // if ($user['is_verified'] == 0) {
    //     echo "<script>alert('Please verify your email via OTP first.'); window.location.href='../index.php';</script>";
    //     exit();
    // }

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
    echo "<script>
            alert('Invalid login credentials.'); 
            window.history.back();
          </script>";
    exit();
}
?>