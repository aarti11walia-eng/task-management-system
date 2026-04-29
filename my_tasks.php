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
    <title>My Tasks | Task Manager</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <style>
        /* Modern Overlay for Modal */
        .modal {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.6);
            display: none; /* Controlled by JS */
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 16px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .form-group { margin-bottom: 12px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: #4b5563; margin-bottom: 4px; }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="sidebar">
        <h2>Task Manager</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="my_tasks.php" class="active">My Tasks</a></li>
            <li><a href="auth/logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main">
        <div class="header">
            <h2>Task List</h2>
        </div>
        
        <div id="taskList" class="task-grid">
            </div>
    </div>
</div>

<div id="editModal" class="modal">
    <div class="modal-content">
        <h3 style="margin-bottom: 20px; font-size: 20px;">Edit Task Details</h3>
        
        <input type="hidden" id="editId">
        
        <div class="form-group">
            <label>Title</label>
            <input type="text" id="editTitle">
        </div>
        
        <div class="form-group">
            <label>Description</label>
            <textarea id="editDescription" rows="3"></textarea>
        </div>
        
        <div style="display:flex; gap:15px;">
            <div class="form-group" style="flex:1;">
                <label>Category</label>
                <input type="text" id="editCategory">
            </div>
            <div class="form-group" style="flex:1;">
                <label>Due Date</label>
                <input type="date" id="editDate">
            </div>
        </div>

        <div style="display:flex; gap:15px; margin-top: 10px;">
            <div class="form-group" style="flex:1;">
                <label>Priority</label>
                <select id="editPriority">
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
            </div>
            <div class="form-group" style="flex:1;">
                <label>Status</label>
                <select id="editStatus">
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
        </div>

        <div class="modal-actions" style="margin-top: 25px; display: flex; gap: 10px;">
            <button class="btn-complete" style="flex:2; padding: 12px; cursor: pointer; background: #2563eb; color: white; border: none; border-radius: 8px;" onclick="saveTaskChanges()">Update Task</button>
            <button class="btn-delete" style="flex:1; padding: 12px; cursor: pointer; background: #f3f4f6; border: none; border-radius: 8px;" onclick="closeModal()">Cancel</button>
        </div>
    </div>
</div>

<script src="assets/js/script.js"></script>
<script>
    window.onload = function() {
        loadTasks();
    };

    /**
     * THE FIX: This function takes the data from your card and 
     * force-injects it into the dropdowns.
     */
    function editTask(id, title, desc, cat, prio, status, date) {
        document.getElementById('editId').value = id;
        document.getElementById('editTitle').value = title;
        document.getElementById('editDescription').value = desc;
        document.getElementById('editCategory').value = cat;
        document.getElementById('editDate').value = date;

        // CRITICAL FIX: Convert priority to lowercase and assign it
        const priorityEl = document.getElementById('editPriority');
        if (priorityEl) {
            // .trim().toLowerCase() makes sure "High" or "high " both work
            priorityEl.value = prio.trim().toLowerCase();
        }

        const statusEl = document.getElementById('editStatus');
        if (statusEl) {
            statusEl.value = status.trim().toLowerCase();
        }

        // Show the modal centered
        document.getElementById('editModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    function saveTaskChanges() {
        // Prepare data
        let formData = new FormData();
        formData.append("id", document.getElementById('editId').value);
        formData.append("title", document.getElementById('editTitle').value);
        formData.append("description", document.getElementById('editDescription').value);
        formData.append("category", document.getElementById('editCategory').value);
        formData.append("priority", document.getElementById('editPriority').value);
        formData.append("status", document.getElementById('editStatus').value);
        formData.append("due_date", document.getElementById('editDate').value);

        // Send to PHP
        fetch("tasks/update_task.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(data => {
            if (data.trim() === "success") {
                closeModal();
                loadTasks(); // Refresh the grid
            } else {
                alert("Error updating: " + data);
            }
        });
    }
</script>

</body>
</html>