<?php
session_start();
include("../config/db.php");

use PHPMailer\PHPMailer\PHPMailer;
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

// Inputs
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$name = isset($_POST['name']) ? trim($_POST['name']) : '';

if(empty($email) || empty($name)){
    echo "Email and Name are required!";
    exit();
}

// Check Duplicate
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
if($stmt->get_result()->num_rows > 0){
    echo "Email already registered!";
    exit();
}

// Generate OTP
$otp = (string)rand(100000, 999999);
$_SESSION['otp'] = $otp;
$_SESSION['otp_email'] = $email;
$_SESSION['otp_expiry'] = time() + 300; // 5 Minutes

try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'taskmanager336@gmail.com';
    $mail->Password = 'qgmlpjaqyiqrfaaj'; 
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('taskmanager336@gmail.com', 'Task Manager');
    $mail->addAddress($email);

    $mail->Subject = "Your OTP for Registration";
    $mail->Body = "Hello $name, your OTP is: $otp";

    if($mail->send()){
        session_write_close(); // Save session immediately
        echo "OTP sent to your email!";
    }
} catch (Exception $e) {
    echo "Mail Error: " . $mail->ErrorInfo;
}
?>