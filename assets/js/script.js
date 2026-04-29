// ===== INITIAL LOAD =====
document.addEventListener("DOMContentLoaded", function () {
    loadTasks();
    loadStats();
});

// ===== TASK FORM SUBMIT (ADD NEW TASK) =====
const taskForm = document.getElementById("taskForm");

if (taskForm) {
    taskForm.addEventListener("submit", function (e) {
        e.preventDefault();

        let title = this.title.value.trim();
        let priority = this.priority.value;
        let status = this.status.value;
        let due_date = this.due_date.value;

        // ✅ VALIDATION
        if (!title || !priority || !status || !due_date) {
            alert("Please fill all required fields!");
            return;
        }

        let formData = new FormData(this);

        // ✅ AJAX REQUEST
        fetch("tasks/add_task.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(data => {
            alert("Task Added Successfully ✅");
            loadTasks();   // refresh the 3-column grid
            loadStats();   // refresh dashboard stats
            taskForm.reset(); // clear form
        })
        .catch(err => {
            console.error(err);
            alert("Error adding task ❌");
        });
    });
}

// ===== LOAD TASKS INTO THE GRID =====
function loadTasks() {
    let taskList = document.getElementById("taskList");
    if (!taskList) return;

    fetch("tasks/get_tasks.php")
    .then(res => res.text())
    .then(data => {
        taskList.innerHTML = data;
    })
    .catch(err => console.error("Error loading tasks:", err));
}

// ===== DELETE TASK =====
function deleteTask(id) {
    if (confirm("Are you sure you want to delete this task?")) {
        fetch("tasks/delete_task.php?id=" + id)
        .then(res => res.text())
        .then(() => {
            alert("Task Deleted ❌");
            loadTasks();
            loadStats();
        })
        .catch(err => console.error("Delete error:", err));
    }
}

// ===== COMPLETE TASK (QUICK ACTION) =====
function completeTask(id) {
    fetch("tasks/complete_task.php?id=" + id)
    .then(res => res.text())
    .then(() => {
        alert("Task Marked as Completed ✅");
        loadTasks();
        loadStats();
    })
    .catch(err => console.error("Complete error:", err));
}

// ===== EDIT TASK (OPEN MODAL WITH ALL DATA) =====
function editTask(id, title, desc, cat, prio, status, date) {
    // 1. Fill the inputs
    document.getElementById('editId').value = id;
    document.getElementById('editTitle').value = title;
    document.getElementById('editDescription').value = desc;
    document.getElementById('editCategory').value = cat;
    document.getElementById('editPriority').value = prio;
    document.getElementById('editStatus').value = status;
    document.getElementById('editDate').value = date;
    
    // 2. Show modal centered
    const modal = document.getElementById('editModal');
    modal.style.display = 'flex'; // Use flex to center via CSS
}

function saveTaskChanges() {
    let formData = new FormData();
    
    // We grab EVERY field. If we miss one, the DB will overwrite it with empty text.
    formData.append("id", document.getElementById('editId').value);
    formData.append("title", document.getElementById('editTitle').value);
    formData.append("description", document.getElementById('editDescription').value);
    formData.append("category", document.getElementById('editCategory').value);
    formData.append("due_date", document.getElementById('editDate').value);
    formData.append("priority", document.getElementById('editPriority').value);
    formData.append("status", document.getElementById('editStatus').value);

    fetch("tasks/update_task.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        if (data.trim().toLowerCase() === "success") {
            closeModal();
            loadTasks(); // Refreshes the 3-column grid
            if(typeof loadStats === 'function') loadStats();
        } else {
            alert("Error: " + data);
        }
    })
    .catch(err => console.error("Update error:", err));
}
// ===== LOAD STATS =====
function loadStats() {
    let totalEl = document.getElementById("totalTasks");
    if (!totalEl) return;

    fetch("tasks/get_stats.php")
    .then(res => res.json())
    .then(data => {
        document.getElementById("totalTasks").innerText = data.total;
        document.getElementById("pendingTasks").innerText = data.pending;
        document.getElementById("completedTasks").innerText = data.completed;
    })
    .catch(err => console.error("Stats error:", err));
}

// Helper to close modal
function closeModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Close modal if user clicks outside of the box
window.onclick = function(event) {
    let modal = document.getElementById('editModal');
    if (event.target == modal) {
        closeModal();
    }
}