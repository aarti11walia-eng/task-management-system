<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Task Manager</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="container">
    <div class="sidebar">
        <h2>Task Manager</h2>
        <ul>
            <li><a href="dashboard.php" class="active">Dashboard</a></li>
            <li><a href="my_tasks.php">My Tasks</a></li>
            <li><a href="auth/logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main">
        <h2>Welcome to Dashboard</h2>

        <!-- FORM SECTION -->
        <form id="taskForm" class="task-form">
            <div class="form-group">
                <label>Task Title</label>
                <input type="text" name="title" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description"></textarea>
            </div>
            <div class="form-group">
                <label>Priority</label>
                <select name="priority" required>
                    <option value="">Select Priority</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" required>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <div class="form-group">
                <label>Due Date</label>
                <input type="date" name="due_date" required>
            </div>
            <div class="form-group">
                <label>Category</label>
                <input type="text" name="category">
            </div>
            <button type="submit" class="add-task-btn">
                <i class="fas fa-plus"></i> Add New Task
            </button>
        </form>

        <!-- STATS SECTION -->
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

        <!-- ✅ CRITICAL ADDITION: TASK LIST CONTAINER -->
        <!-- This ID is required for script.js to refresh the view after adding a task -->
        <div id="taskList" style="display:none;"></div>
        
    </div>
</div>
<script src="assets/js/script.js"></script>
</body>
</html>