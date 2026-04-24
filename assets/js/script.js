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

        // ✅ AJAX REQUEST (ADD TASK)
        fetch("tasks/add_task.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(data => {
            // Optional: show response
            console.log(data);

            loadTasks();   // refresh tasks
            loadStats();   // refresh stats

            taskForm.reset(); // clear form
        })
        .catch(err => console.log(err));
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
    .catch(err => console.log(err));
}

// ===== DELETE TASK =====
function deleteTask(id) {
    if (confirm("Are you sure you want to delete this task?")) {
        fetch("tasks/delete_task.php?id=" + id)
        .then(res => res.text())
        .then(() => {
            loadTasks();
            loadStats();
        })
        .catch(err => console.log(err));
    }
}

// ===== COMPLETE TASK =====
function completeTask(id) {
    fetch("tasks/complete_task.php?id=" + id)
    .then(res => res.text())
    .then(() => {
        loadTasks();
        loadStats();
    })
    .catch(err => console.log(err));
}

// ===== EDIT TASK =====
function editTask(id, title, description) {
    let newTitle = prompt("Edit Title:", title);
    let newDesc = prompt("Edit Description:", description);

    if (newTitle !== null && newTitle.trim() !== "") {
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
            loadTasks();
            loadStats();
        })
        .catch(err => console.log(err));
    }
}

// ===== LOAD STATS =====
function loadStats() {
    let total = document.getElementById("totalTasks");

    // If stats not on page, skip
    if (!total) return;

    fetch("tasks/get_stats.php")
    .then(res => res.json())
    .then(data => {
        document.getElementById("totalTasks").innerText = data.total;
        document.getElementById("pendingTasks").innerText = data.pending;
        document.getElementById("completedTasks").innerText = data.completed;
    })
    .catch(err => console.log(err));

}
