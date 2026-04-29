<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// 1. UPDATE: Only get users who have NOT been soft-deleted
$users = $conn->query("SELECT * FROM users WHERE role != 'admin' AND deleted_at IS NULL");
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<div class="container">
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="tasks.php" class="active">Tasks</a></li>
            <li><a href="../auth/logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main">
        <div class="header">Users Tasks</div>

        <?php while($user = $users->fetch_assoc()): ?>
            <div class="card" style="margin-bottom:20px;">
                <h3>
                    <?php echo htmlspecialchars($user['name']); ?> 
                    (User ID: <?php echo $user['id']; ?>)
                </h3>

                <hr style="margin:10px 0;">

                <?php
                // 2. UPDATE: Only get tasks that have NOT been soft-deleted
                $user_id = $user['id'];
                $tasks = $conn->query("SELECT * FROM tasks WHERE user_id='$user_id' AND deleted_at IS NULL");
                ?>

                <?php if ($tasks->num_rows > 0): ?>
                    <table>
                        <tr>
                            <th>Title</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Due Date</th>
                        </tr>

                        <?php while($task = $tasks->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($task['title']); ?></td>
                            <td><?php echo ucfirst($task['priority']); ?></td>
                            <td><?php echo ucfirst(str_replace('_', ' ', $task['status'])); ?></td>
                            <td><?php echo $task['due_date']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </table>
                <?php else: ?>
                    <p style="color:gray; margin-top:10px;">No tasks assigned to this user.</p>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>