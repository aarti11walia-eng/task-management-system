<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ FETCH TASKS (Including soft-delete check)
$stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id = ? AND deleted_at IS NULL ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
    $priority = ucfirst(strtolower($row['priority']));
    // Format status: replace underscores with spaces and capitalize
    $status = ucfirst(str_replace('_', ' ', $row['status']));
    
    // Define Priority colors for the badge
    $priorityColor = ($priority === 'High') ? '#ef4444' : (($priority === 'Medium') ? '#3b82f6' : '#10b981');
    $priorityBg = ($priority === 'High') ? '#fef2f2' : (($priority === 'Medium') ? '#eff6ff' : '#ecfdf5');

    echo "
    <div class='task-card' style='background: white; padding: 28px; border-radius: 24px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05); border: 1px solid #f1f5f9; display: flex; flex-direction: column;'>
        
        <!-- Header Section -->
        <div style='display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;'>
            <h3 style='margin: 0; font-size: 22px; color: #0f172a; font-weight: 800; line-height: 1.2; max-width: 75%;'>
                " . htmlspecialchars($row['title']) . "
            </h3>
            <span style='color: $priorityColor; font-size: 11px; font-weight: 700; padding: 6px 12px; background: $priorityBg; border-radius: 10px; text-transform: uppercase; letter-spacing: 0.8px;'>
                " . $priority . "
            </span>
        </div>

        <!-- Description Section -->
        <p style='margin: 0 0 20px 0; font-size: 14px; color: #64748b; line-height: 1.6;'>
            " . htmlspecialchars($row['description'] ?? 'No description provided.') . "
        </p>

        <!-- Shaded Information Box -->
        <div style='background: #f8fafc; padding: 18px; border-radius: 16px; margin-bottom: 24px; display: flex; flex-direction: column; gap: 10px;'>
            <div style='display: flex; align-items: center; gap: 10px; font-size: 13px; color: #475569;'>
                <span style='font-size: 16px;'>🗓️</span> 
                <span><strong>Due:</strong> " . htmlspecialchars($row['due_date']) . "</span>
            </div>
            <div style='display: flex; align-items: center; gap: 10px; font-size: 13px; color: #475569;'>
                <span style='font-size: 16px;'>📂</span> 
                <span><strong>Category:</strong> " . htmlspecialchars($row['category'] ?? 'General') . "</span>
            </div>
            <div style='display: flex; align-items: center; gap: 10px; font-size: 13px; color: #475569;'>
                <span style='font-size: 16px;'>🛂</span> 
                <span><strong>Status:</strong> " . $status . "</span>
            </div>
        </div>

        <!-- Action Buttons (Only Edit and Delete) -->
        <div style='display: flex; gap: 12px;'>
            <button onclick=\"editTask(" . $row['id'] . ", '" . addslashes($row['title']) . "', '" . addslashes($row['description']) . "', '" . addslashes($row['category']) . "', '" . $row['priority'] . "', '" . $row['status'] . "', '" . $row['due_date'] . "')\" 
                style='flex: 1; padding: 12px; border: none; border-radius: 12px; background: #f1f5f9; color: #475569; font-size: 13px; font-weight: 700; cursor: pointer;'>
                Edit
            </button>
            
            <button onclick='deleteTask(" . $row['id'] . ")' 
                style='flex: 1; padding: 12px; border: none; border-radius: 12px; background: #fee2e2; color: #991b1b; font-size: 13px; font-weight: 700; cursor: pointer;'>
                Delete
            </button>
        </div>
    </div>";
}
} else {
    echo "<p>No tasks found. Add a task from the Dashboard!</p>";
}

$stmt->close();
$conn->close();
?>