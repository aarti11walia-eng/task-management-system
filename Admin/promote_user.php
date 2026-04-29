<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['id'])) {
    $user_id = $conn->real_escape_string($_GET['id']);

    $sql = "UPDATE users SET role = 'admin' WHERE id = '$user_id'";

    if ($conn->query($sql)) {
        echo "<script>
                alert('Success: User role updated to Admin!');
                window.location.href = 'users.php';
              </script>";
    } else {
        echo "<script>
                alert('Database Error: Could not update role.');
                window.location.href = 'users.php';
              </script>";
    }
} else {
    header("Location: users.php");
}
exit();
?>