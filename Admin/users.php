<?php
session_start();
include("../config/db.php");

// 1. SECURITY CHECK
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$current_admin_id = $_SESSION['user_id'];

// 2. ACTION HANDLER
if (isset($_GET['action'])) {
    $id = $conn->real_escape_string($_GET['id']);
    
    // Handle Role Change
    if ($_GET['action'] == 'change_role' && isset($_GET['new_role'])) {
        $new_role = $conn->real_escape_string($_GET['new_role']);
        $conn->query("UPDATE users SET role = '$new_role' WHERE id = '$id'");
        
        // Check if you just demoted yourself
        if ($id == $current_admin_id && $new_role == 'user') {
            $_SESSION['role'] = 'user';
            echo "<script>alert('You have demoted yourself. Redirecting...'); window.location.href='../dashboard.php';</script>";
            exit();
        }

        echo "<script>alert('Role updated successfully!'); window.location.href='users.php';</script>";
        exit();
    }

    // Handle Soft Delete
    if ($_GET['action'] == 'delete') {
        $conn->query("UPDATE users SET deleted_at = NOW() WHERE id = '$id'");
        
        // Check if you just deleted yourself
        if ($id == $current_admin_id) {
            session_destroy();
            echo "<script>alert('Your account has been deleted. Logging out.'); window.location.href='../index.php';</script>";
            exit();
        }

        echo "<script>alert('User account deleted.'); window.location.href='users.php';</script>";
        exit();
    }
}

// 3. FETCH ALL ACTIVE USERS (Admins and Users)
$result = $conn->query("SELECT * FROM users 
                        WHERE deleted_at IS NULL 
                        ORDER BY role ASC, name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management | Admin</title>
    <link rel="stylesheet" href="../assets/css/Admin.css">
    <style>
        .role-action {
            display: inline-flex; align-items: center; padding: 6px 16px; border-radius: 20px;
            font-size: 13px; font-weight: 700; text-decoration: none; border: 1px solid; cursor: pointer;
        }
        .role-user { background-color: #f8f9fa; color: #333; border-color: #ddd; }
        .role-user::after { content: ' ▼'; font-size: 10px; margin-left: 8px; color: #888; }
        .role-admin { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
        .role-admin::after { content: ' ▼'; font-size: 10px; margin-left: 8px; }
        
        .btn-delete { background-color: #fff1f0; color: #cf1322; padding: 6px 12px; border: 1px solid #ffa39e; border-radius: 4px; text-decoration: none; font-size: 13px; }
        .self-label { font-size: 11px; color: #faad14; font-weight: normal; margin-left: 5px; }
    </style>
</head>
<body>

<div class="container">
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="users.php" class="active">Users</a></li>
            <li><a href="tasks.php">Tasks</a></li>
            <li><a href="../auth/logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main">
        <div class="header">User Management</div>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr class="<?php echo ($row['id'] == $current_admin_id) ? 'self-highlight' : ''; ?>">
                        <td>
                            <?php echo htmlspecialchars($row['name']); ?>
                            <?php if($row['id'] == $current_admin_id) echo "<span class='self-label'>(You)</span>"; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        
                        <td>
                            <?php if($row['role'] == 'user'): ?>
                                <a href="users.php?action=change_role&id=<?php echo $row['id']; ?>&new_role=admin" 
                                   class="role-action role-user" 
                                   onclick="return confirm('Promote to Admin?')">User</a>
                            <?php else: ?>
                                <a href="users.php?action=change_role&id=<?php echo $row['id']; ?>&new_role=user" 
                                   class="role-action role-admin" 
                                   onclick="return confirm('Demote to User? WARNING: If this is your account, you will lose access!')">Admin</a>
                            <?php endif; ?>
                        </td>

                        <td>
                            <a href="users.php?action=delete&id=<?php echo $row['id']; ?>" 
                               class="btn-delete" 
                               onclick="return confirm('Delete this account?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="bottom-action" style="margin-top: 20px;">
            <a href="add_user.php" style="text-decoration:none; background:#007bff; color:white; padding:10px 20px; border-radius:5px; font-weight:bold;">Add New User</a>
        </div>
    </div>
</div>

</body>
</html>