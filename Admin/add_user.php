<?php
session_start();
include("../config/db.php");

// Security check: Ensure only admins can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['add'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password_raw = $_POST['password'];
    
    // 1. Email Format Validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
        echo "<script>alert('Invalid email format!'); window.history.back();</script>";
        exit();
    }

    // 2. Check if Email already exists
    $checkEmail = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($checkEmail->num_rows > 0) {
        echo "<script>alert('Error: This email is already registered!'); window.history.back();</script>";
        exit();
    }

    // 3. Process the insertion
    $pass = password_hash($password_raw, PASSWORD_DEFAULT);
    $role = 'user'; // Role is now hardcoded to 'user' as requested

    $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$pass', '$role')";

    if ($conn->query($sql)) {
        echo "<script>
                alert('User created successfully!');
                window.location.href = 'users.php';
              </script>";
    } else {
        echo "<script>alert('Database Error: Could not add user.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New User | Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<div class="container">
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="users.php" class="active">Users</a></li>
            <li><a href="tasks.php">Tasks</a></li>
            <li><a href="../auth/logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main">
        <div class="header">Add New User</div>

        <div class="form-wrapper">
            <div class="form-card">
                <h3>Create New Account</h3>

                <form method="POST">
                    <label>Full Name</label>
                    <input type="text" name="name" placeholder="" required>

                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="" required>

                    <label>Password</label>
                    <input type="password" name="password" placeholder="" required>

                    <button class="btn add-btn" name="add" style="margin-top: 15px;">Create User</button>
                    <a href="users.php" style="display: block; text-align: center; margin-top: 10px; font-size: 14px; color: #666; text-decoration: none;">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>