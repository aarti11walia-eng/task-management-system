<?php
session_start();
include("../config/db.php");

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
}

// STATS
$totalUsers = $conn->query("SELECT * FROM users")->num_rows;
$totalTasks = $conn->query("SELECT * FROM tasks")->num_rows;
$pendingTasks = $conn->query("SELECT * FROM tasks WHERE status='pending'")->num_rows;
$completedTasks = $conn->query("SELECT * FROM tasks WHERE status='completed'")->num_rows;

// RECENT USERS
$recentUsers = $conn->query("SELECT * FROM users WHERE role != 'admin' ORDER BY id DESC LIMIT 5");
// RECENT TASKS
$recentTasks = $conn->query("
    SELECT tasks.*, users.name 
    FROM tasks 
    JOIN users ON tasks.user_id = users.id 
    WHERE users.role != 'admin'
    ORDER BY tasks.id DESC 
    LIMIT 5
");
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
        <li><a href="dashboard.php" class="active">Dashboard</a></li>
        <li><a href="users.php">Users</a></li>
        <li><a href="tasks.php">Tasks</a></li>
        <li><a href="../auth/logout.php">Logout</a></li>
    </ul>
</div>

<!-- MAIN -->
<div class="main">

<div class="header">Dashboard Overview</div>

<!-- CARDS -->
<div class="cards">
    <div class="card">
        <h3>Total Users</h3>
        <p><?php echo $totalUsers; ?></p>
    </div>

    <div class="card">
        <h3>Total Tasks</h3>
        <p><?php echo $totalTasks; ?></p>
    </div>

    <div class="card">
        <h3>Pending Tasks</h3>
        <p><?php echo $pendingTasks; ?></p>
    </div>

    <div class="card">
        <h3>Completed Tasks</h3>
        <p><?php echo $completedTasks; ?></p>
    </div>
</div>

<!-- TWO COLUMN SECTION -->
<div style="display:flex; gap:20px; margin-top:20px;">

    <!-- RECENT USERS -->
    <div style="flex:1;">
        <h3 class="section-title">Recent Users</h3>
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
            </tr>

            <?php while($u = $recentUsers->fetch_assoc()): ?>
            <tr>
                <td><?php echo $u['name']; ?></td>
                <td><?php echo $u['email']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <!-- RECENT TASKS -->
    <div style="flex:1;">
        <h3 class="section-title">Recent Tasks</h3>
        <table>
            <tr>
                <th>Title</th>
                <th>User</th>
            </tr>

            <?php while($t = $recentTasks->fetch_assoc()): ?>
            <tr>
                <td><?php echo $t['title']; ?></td>
                <td><?php echo $t['name']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

</div>

</div>
</div>

</body>
</html>