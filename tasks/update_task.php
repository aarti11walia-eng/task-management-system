<?php
include("../config/db.php");

$id = $_POST['id'];
$title = $_POST['title'];
$description = $_POST['description'];

$conn->query("UPDATE tasks SET title='$title', description='$description' WHERE id='$id'");
?>