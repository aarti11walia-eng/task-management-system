<?php
session_start();
include("../config/db.php");

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
}

$message = "";

if(isset($_POST['add'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // CHECK ADMIN
    if ($role == 'admin') {
        $check = $conn->query("SELECT * FROM users WHERE role='admin'");
        if ($check->num_rows > 0) {
            $message = "Only one admin allowed!";
        } else {
            $conn->query("INSERT INTO users (name,email,password,role)
                          VALUES ('$name','$email','$pass','$role')");
            $message = "Admin created!";
        }
    } else {
        $conn->query("INSERT INTO users (name,email,password,role)
                      VALUES ('$name','$email','$pass','user')");
        $message = "User added!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<div class="container">

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>Admin Panel</h2>
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="users.php" class="active">Users</a></li>
        <li><a href="tasks.php">Tasks</a></li>
        <li><a href="../auth/logout.php">Logout</a></li>
    </ul>
</div>

<!-- MAIN -->
<div class="main">

<div class="header">Add New User</div>

<!-- CENTERED CARD -->
<div class="form-wrapper">

    <div class="form-card">

        <h3>Create User</h3>

        <?php if($message): ?>
            <p class="success-msg"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST">

            <input type="text" name="name" placeholder="Full Name" required>

            <input type="email" name="email" placeholder="Email Address" required>

            <input type="password" name="password" placeholder="Password" required>

            <select name="role">
                <option value="user">User</option>

                <?php
                $check = $conn->query("SELECT * FROM users WHERE role='admin'");
                if ($check->num_rows == 0) {
                    echo "<option value='admin'>Admin</option>";
                }
                ?>
            </select>

            <button class="btn add-btn" name="add">Create User</button>

        </form>

    </div>

</div>

</div>
</div>

</body>
</html>