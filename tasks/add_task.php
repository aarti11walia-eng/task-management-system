<?php
session_start();
include("../config/db.php");

$user_id = $_SESSION['user_id'];
$title = $_POST['title'];
$desc = $_POST['description'];
$priority = $_POST['priority'];
$due = $_POST['due_date'];
$category = $_POST['category'];

$conn->query("INSERT INTO tasks (user_id,title,description,priority,due_date,category)
VALUES ('$user_id','$title','$desc','$priority','$due','$category')");
?>
