<?php
include("../config/db.php");

$id = $_GET['id'];
$conn->query("UPDATE tasks SET status='Completed' WHERE id='$id'");
?>