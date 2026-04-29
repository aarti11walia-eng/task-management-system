<?php
session_start();
include("../config/db.php");

// Protect Admin Route
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

/** * STATS LOGIC
 * We filter by 'deleted_at IS NULL' to ensure only active data is counted.
 */

// 1. Total Active Users (excluding admins)
$totalUsers = $conn->query("SELECT * FROM users WHERE role != 'admin' AND deleted_at IS NULL")->num_rows;

// 2. Total Active Tasks from Active Users
$totalTasks = $conn->query("
    SELECT tasks.id FROM tasks 
    JOIN users ON tasks.user_id = users.id 
    WHERE tasks.deleted_at IS NULL AND users.deleted_at IS NULL
")->num_rows;

// 3. Pending Tasks from Active Users
$pendingTasks = $conn->query("
    SELECT tasks.id FROM tasks 
    JOIN users ON tasks.user_id = users.id 
    WHERE tasks.status='pending' AND tasks.deleted_at IS NULL AND users.deleted_at IS NULL
")->num_rows;

// 4. Completed Tasks from Active Users
$completedTasks = $conn->query("
    SELECT tasks.id FROM tasks 
    JOIN users ON tasks.user_id = users.id 
    WHERE tasks.status='completed' AND tasks.deleted_at IS NULL AND users.deleted_at IS NULL
")->num_rows;


// RECENT USERS (Only non-deleted users)
$recentUsers = $conn->query("SELECT * FROM users WHERE role != 'admin' AND deleted_at IS NULL ORDER BY id DESC LIMIT 5");

// RECENT TASKS (Only active tasks from active users)
$recentTasks = $conn->query("
    SELECT tasks.*, users.name 
    FROM tasks 
    JOIN users ON tasks.user_id = users.id 
    WHERE users.role != 'admin' 
    AND users.deleted_at IS NULL 
    AND tasks.deleted_at IS NULL
    ORDER BY tasks.id DESC 
    LIMIT 5
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/Admin.css">
</head>
<body>

<div class="container">

<div class="sidebar">
    <h2>Admin Panel</h2>
    <ul>
        <li><a href="dashboard.php" class="active">Dashboard</a></li>
        <li><a href="users.php">Users</a></li>
        <li><a href="tasks.php">Tasks</a></li>
        <li><a href="../auth/logout.php">Logout</a></li>
    </ul>
</div>

<div class="main">

<div class="header">Dashboard Overview</div>

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

<div style="display:flex; gap:20px; margin-top:20px;">

    <div style="flex:1;">
        <h3 class="section-title">Recent Active Users</h3>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php if($recentUsers->num_rows > 0): ?>
                    <?php while($u = $recentUsers->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($u['name']); ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="2" style="text-align:center; color:gray;">No active users.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div style="flex:1;">
        <h3 class="section-title">Recent Active Tasks</h3>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>User</th>
                </tr>
            </thead>
            <tbody>
                <?php if($recentTasks->num_rows > 0): ?>
                    <?php while($t = $recentTasks->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($t['title']); ?></td>
                        <td><?php echo htmlspecialchars($t['name']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="2" style="text-align:center; color:gray;">No active tasks.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</div>
</div>

</body>
</html>