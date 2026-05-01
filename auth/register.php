<?php
session_start();
// Error reporting on karein taaki DB errors dikhein
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
include("../config/db.php");

if(isset($_POST['register'])){

    // 1. Check if Email is in session
    if(!isset($_SESSION['otp_email'])){
        $_SESSION['error_msg'] = "Registration session expired. Please verify OTP again.";
        header("Location: ../index.php");
        exit();
    }

    // 2. Data Collect
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = $_SESSION['otp_email']; // OTP wali email hi use karein
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'user';

    try {
        // 3. Check if email already exists (Last minute check)
        $checkEmail = $conn->query("SELECT id FROM users WHERE email='$email'");
        if($checkEmail->num_rows > 0){
            $_SESSION['error_msg'] = "Email already registered!";
            header("Location: ../index.php");
            exit();
        }

        // 4. INSERT QUERY
        // Check karein ki table ka naam 'users' hi hai aur columns sahi hain
        $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
        
        if($conn->query($sql)){
            // ✅ Success
            unset($_SESSION['otp']);
            unset($_SESSION['otp_email']);
            unset($_SESSION['otp_expiry']);
            unset($_SESSION['otp_verified']);

            header("Location: ../index.php?success=1");
            exit();
        } else {
            $_SESSION['error_msg'] = "Database error. Could not save user.";
            header("Location: ../index.php");
            exit();
        }

    } catch (Exception $e) {
        // Agar table ya column name galat hai toh ye error dikhayega
        $_SESSION['error_msg'] = "DB Error: " . $e->getMessage();
        header("Location: ../index.php");
        exit();
    }
}
?>