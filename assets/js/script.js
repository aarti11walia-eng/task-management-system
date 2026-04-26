// ===== INITIAL LOAD =====
document.addEventListener("DOMContentLoaded", function () {
    loadTasks();
    loadStats();
});

// ===== TASK FORM SUBMIT (VALIDATION + AJAX) =====
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

            loadTasks();   // refresh tasks
            loadStats();   // refresh stats

            taskForm.reset(); // clear form
        })
        .catch(err => {
            console.error(err);
            alert("Error adding task ❌");
        });
    });
}

// ===== LOAD TASKS =====
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

// ===== COMPLETE TASK =====
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

// ===== EDIT TASK =====
function editTask(id, title, description) {

    let newTitle = prompt("Edit Title:", title);
    if (newTitle === null || newTitle.trim() === "") return;

    let newDesc = prompt("Edit Description:", description);

    let formData = new FormData();
    formData.append("id", id);
    formData.append("title", newTitle.trim());
    formData.append("description", newDesc);

    fetch("tasks/update_task.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(() => {
        alert("Task Updated ✏️");
        loadTasks();
        loadStats();
    })
    .catch(err => console.error("Update error:", err));
}

// ===== LOAD STATS =====
function loadStats() {

    if (!document.getElementById("totalTasks")) return;

    fetch("tasks/get_stats.php")
    .then(res => res.json())
    .then(data => {
        document.getElementById("totalTasks").innerText = data.total;
        document.getElementById("pendingTasks").innerText = data.pending;
        document.getElementById("completedTasks").innerText = data.completed;
    })
    .catch(err => console.error("Stats error:", err));
}