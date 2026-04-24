<?php
include("../config/db.php");
$id = $_GET['id'];
$conn->query("DELETE FROM tasks WHERE id='$id'");
?>