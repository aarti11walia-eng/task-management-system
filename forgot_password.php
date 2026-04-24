<?php
include("config/db.php");

$message = "";

if (isset($_POST['reset'])) {
    $email = $_POST['email'];
    $newPass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $conn->query("SELECT * FROM users WHERE email='$email'");

    if ($check->num_rows > 0) {
        $conn->query("UPDATE users SET password='$newPass' WHERE email='$email'");
        $message = "Password updated successfully!";
    } else {
        $message = "Email not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="assets/css/forgot.css">
</head>
<body>

<div class="forgot-wrapper">

    <div class="forgot-card">

        <h2>Reset Password</h2>

        <?php if($message != ""): ?>
            <p class="success-msg"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="password" name="password" placeholder="New Password" required>

            <button name="reset">Reset Password</button>
        </form>

        <p class="switch-text">
            Back to <a href="index.php">Login</a>
        </p>

    </div>

</div>

</body>
</html>