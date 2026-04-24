<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Tasks</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>

<div class="container">

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Task Manager</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="my_tasks.php" class="active">My Tasks</a></li>
            <li><a href="auth/logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main -->
    <div class="main">

        <!-- Dynamic Content -->
        <div id="taskList" class="task-grid"></div>
    </div>

</div>

<script src="assets/js/script.js"></script>

<script>
    loadTasks(); // load automatically
</script>

</body>
</html>