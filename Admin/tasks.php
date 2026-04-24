<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// GET ALL USERS
$users = $conn->query("SELECT * FROM users WHERE role != 'admin'");
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
        <li><a href="users.php">Users</a></li>
        <li><a href="tasks.php" class="active">Tasks</a></li>
        <li><a href="../auth/logout.php">Logout</a></li>
    </ul>
</div>

<!-- MAIN -->
<div class="main">

<div class="header">Tasks by Users</div>

<?php while($user = $users->fetch_assoc()): ?>

    <!-- USER CARD -->
    <div class="card" style="margin-bottom:20px;">

        <!-- USER INFO -->
        <h3>
            <?php echo $user['name']; ?> 
            (User ID: <?php echo $user['id']; ?>)
        </h3>

        <hr style="margin:10px 0;">

        <?php
        // GET TASKS FOR THIS USER
        $tasks = $conn->query("SELECT * FROM tasks WHERE user_id='{$user['id']}'");
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
                    <td><?php echo $task['title']; ?></td>
                    <td><?php echo $task['priority']; ?></td>
                    <td><?php echo ucfirst($task['status']); ?></td>
                    <td><?php echo $task['due_date']; ?></td>
                </tr>
                <?php endwhile; ?>

            </table>

        <?php else: ?>

            <p style="color:gray; margin-top:10px;">
                No tasks assigned to this user.
            </p>

        <?php endif; ?>

    </div>

<?php endwhile; ?>

</div>
</div>

</body>
</html>