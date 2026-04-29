<?php
include("../config/db.php");
session_start(); // Important if you want to ensure the user owns the task

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $conn->real_escape_string($_POST['id']);
    $title = $conn->real_escape_string($_POST['title']);
    $desc = $conn->real_escape_string($_POST['description']);
    $cat = $conn->real_escape_string($_POST['category']);
    $prio = $conn->real_escape_string($_POST['priority']);
    $status = $conn->real_escape_string($_POST['status']);
    $date = $conn->real_escape_string($_POST['due_date']);

    $sql = "UPDATE tasks SET 
            title='$title', 
            description='$desc', 
            category='$cat', 
            priority='$prio', 
            status='$status', 
            due_date='$date' 
            WHERE id='$id'";

    if ($conn->query($sql)) {
        // Use lowercase "success" to make JavaScript comparison easier
        echo "success"; 
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "No ID provided or invalid request.";
}
?>