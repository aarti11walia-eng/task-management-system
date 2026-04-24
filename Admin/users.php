<?php
session_start();
include("../config/db.php");

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
}

$result = $conn->query("SELECT * FROM users WHERE role != 'admin'");
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<div class="container">

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>Admin Panel</h2>
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="users.php" class="active">Users</a></li>
        <li><a href="tasks.php">Tasks</a></li>
        <li><a href="../auth/logout.php">Logout</a></li>
    </ul>
</div>

<!-- MAIN -->
<div class="main">

    <!-- HEADER -->
    <div class="header">User Management</div>

    <!-- TABLE WRAPPER (for spacing) -->
    <div class="table-card">

        <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>

        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo ucfirst($row['role']); ?></td>

            <td>
                <?php if($row['role'] != 'admin'): ?>
                    <a class="btn" 
                       href="delete_user.php?id=<?php echo $row['id']; ?>"
                       onclick="return confirm('Are you sure you want to delete this user?')">
                       Delete
                    </a>
                <?php else: ?>
                    <span class="admin-badge">Admin</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>

        </table>

    </div>

    <!-- ADD USER BUTTON (BOTTOM) -->
    <div class="bottom-action">
        <a href="add_user.php" class="btn add-btn">Add User</a>
    </div>

</div>
</div>

</body>
</html>