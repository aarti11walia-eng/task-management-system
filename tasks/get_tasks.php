<?php
session_start();
include("../config/db.php");

$user_id = $_SESSION['user_id'];

$result = $conn->query("SELECT * FROM tasks WHERE user_id='$user_id'");

// 🔴 CHECK IF NO TASKS
if ($result->num_rows == 0) {
    echo "
    <div class='no-task-card'>
        <p>No tasks have been assigned yet!</p>
    </div>
    ";
} else {

    while($row = $result->fetch_assoc()){
    echo "
    <div>
        <h3>{$row['title']} ({$row['priority']})</h3>
        <p>{$row['description']}</p>
        <small>Due: {$row['due_date']} | Category: {$row['category']}</small><br>

        <button onclick='deleteTask({$row['id']})'>Delete</button>

        <button onclick=\"editTask({$row['id']}, '{$row['title']}', '{$row['description']}')\">
            Edit
        </button>
        <button onclick=\"completeTask({$row['id']})\">
            Complete
        </button>
    
    </div>";
}

}
?>