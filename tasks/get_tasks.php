<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    exit("Access Denied");
}

$user_id = $_SESSION['user_id'];

// Only fetch tasks where deleted_at is NULL (Soft delete check)
$result = $conn->query("SELECT * FROM tasks WHERE user_id='$user_id' AND deleted_at IS NULL ORDER BY due_date ASC");

if ($result->num_rows == 0) {
    echo "
    <div class='no-task-card' style='text-align:center; padding: 50px; color: #64748b; grid-column: span 3;'>
        <p>No active tasks found! Start by adding one from the dashboard.</p>
    </div>
    ";
} else {
    while($row = $result->fetch_assoc()){
        // 1. Prepare variables and escape them for JavaScript
        $id     = $row['id'];
        $title  = addslashes($row['title']);
        $desc   = addslashes($row['description']);
        $cat    = addslashes($row['category']);
        $prio   = $row['priority'];
        $status = $row['status'];
        $date   = $row['due_date'];

        // 2. Assign colors based on priority
        $prioColor = ($prio == 'high') ? '#ef4444' : (($prio == 'medium') ? '#f59e0b' : '#3b82f6');

        // 3. Assign colors based on Status (New Logic)
        $statusStyle = "color: #64748b; background: #f1f5f9;"; // Default: Pending (Grey)
        if ($status == 'in_progress') {
            $statusStyle = "color: #2563eb; background: #eff6ff;"; // Blue
        } elseif ($status == 'completed') {
            $statusStyle = "color: #10b981; background: #ecfdf5;"; // Green
        }

        echo "
        <div class='task-item'>
            <div class='task-content'>
                <div style='display: flex; justify-content: space-between; align-items: flex-start;'>
                    <h3>" . htmlspecialchars($row['title']) . "</h3>
                    <span class='priority-tag' style='background: {$prioColor}20; color: {$prioColor}; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: bold;'>
                        " . strtoupper($prio) . "
                    </span>
                </div>
                <p>" . htmlspecialchars($row['description']) . "</p>
            </div>

            <div class='task-meta'>
                <div class='meta-item'>
                    <strong>📅 Due:</strong> $date
                </div>
                <div class='meta-item'>
                    <strong>📁 Category:</strong> " . htmlspecialchars($row['category']) . "
                </div>
                <div class='meta-item' style='margin-top:5px;'>
                    <span style='padding: 2px 8px; border-radius: 12px; font-size: 12px; font-weight: 500; $statusStyle'>
                        ● " . ucfirst(str_replace('_', ' ', $status)) . "
                    </span>
                </div>
            </div>

            <div class='task-actions'>
                <button onclick=\"editTask('$id', '$title', '$desc', '$cat', '$prio', '$status', '$date')\" class='btn-edit'>
                    Edit
                </button>
                
                <button onclick='deleteTask($id)' class='btn-delete'>
                    Delete
                </button>
            </div>
        </div>";
    }
}
?>