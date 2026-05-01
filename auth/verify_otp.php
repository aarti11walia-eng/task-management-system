<?php
ob_start(); // Start output buffering
session_start();

// Clear any accidental previous output
ob_clean();

if (!isset($_SESSION['otp']) || !isset($_SESSION['otp_expiry'])) {
    echo "session_expired";
    exit();
}

$entered = isset($_POST['otp']) ? trim($_POST['otp']) : '';
$real = (string)$_SESSION['otp'];
$expiry = $_SESSION['otp_expiry'];

if ($entered === $real && time() < $expiry) {
    // success ke baad OTP delete na karein agar aapko registration form submit tak ise validate rakhna hai
    // Par logic ke hisab se verify hote hi success dena hai:
    echo "success";
} else {
    echo "invalid";
}
exit(); // Ensure no extra spaces after this
?>