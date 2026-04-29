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
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>

<div class="container">

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Task Manager</h2>
        <ul>
            <li><a href="dashboard.php" class="active">Dashboard</a></li>
            <li><a href="my_tasks.php">My Tasks</a></li>
            <li><a href="auth/logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main">

        <h2>Welcome to Dashboard</h2>

        <!-- ✅ ADD TASK FORM -->
        <form id="taskForm" class="task-form">

            <!-- TITLE -->
            <div class="form-group">
                <label>Task Title</label>
                <input type="text" name="title" required>
            </div>

            <!-- DESCRIPTION -->
            <div class="form-group">
                <label>Description</label>
                <textarea name="description"></textarea>
            </div>

            <!-- PRIORITY -->
            <div class="form-group">
                <label>Priority</label>
                <select name="priority" required>
                    <option value="">Select Priority</option>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
            </div>

            <!-- STATUS -->
            <div class="form-group">
                <label>Status</label>
                <select name="status" required>
                    <option value="">Select Status</option>
                    <option value="Pending">Pending</option>
                     <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>

            <!-- DUE DATE -->
            <div class="form-group">
                <label>Due Date</label>
                <input type="date" name="due_date" required>
            </div>

            <!-- CATEGORY -->
            <div class="form-group">
                <label>Category</label>
                <input type="text" name="category">
            </div>

            <form id="taskForm">
    <button type="submit" class="add-task-btn">
        <i class="fas fa-plus"></i> Add New Task
    </button>
</form>

        </form>

        <!-- ✅ STATS -->
        <div class="stats">

            <div class="card">
                <h3>Total Tasks</h3>
                <p id="totalTasks">0</p>
            </div>

            <div class="card">
                <h3>Pending Tasks</h3>
                <p id="pendingTasks">0</p>
            </div>

            <div class="card">
                <h3>Completed Tasks</h3>
                <p id="completedTasks">0</p>
            </div>

        </div>

        
    </div>
</div>

<!-- ✅ ONLY THIS SCRIPT (IMPORTANT) -->
<script src="assets/js/script.js"></script>

</body>
</html>