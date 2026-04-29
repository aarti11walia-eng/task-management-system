<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Task Manager</title>
    <link rel="stylesheet" href="assets/css/auth.css">
    <style>
        .error-banner {
            background-color: #fef2f2;
            color: #dc2626;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #fee2e2;
            font-size: 14px;
            font-weight: 500;
        }
        /* Style for the Register/Login toggle link */
        .switch-link {
            cursor: pointer; 
            color: #2563eb; 
            font-weight: 600;
            text-decoration: none;
        }
        .switch-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="login-container">
    <div class="login-left">
        <div class="form-box">
            
            <?php 
            if (isset($_SESSION['error_msg'])) {
                echo '<div class="error-banner">' . htmlspecialchars($_SESSION['error_msg']) . '</div>';
                unset($_SESSION['error_msg']); 
            }
            ?>

            <form id="loginForm" method="POST" action="auth/login.php">
                <h2>Log In</h2>
                <input type="email" name="email" placeholder="Email" required 
                       pattern="^[a-z0-9._%+-]+@[a-z0-9.-]+\.com$" 
                       title="Enter a valid email ending in .com">
                <input type="password" name="password" placeholder="Password" required>
                
                <div class="options">
                    <label><input type="checkbox"> Remember me</label>
                    <a href="forgot_password.php">Forgot Password?</a>
                </div>
                
                <button type="submit">Log In</button>
                
                <p class="switch-text">
                    Don't have an account? 
                    <span class="switch-link" onclick="toggleForm()">Register</span>
                </p>
            </form>

            <form id="registerForm" method="POST" action="auth/register.php" style="display:none;">
                <h2>Create Account</h2>
                
                <input type="text" id="regName" name="name" placeholder="Full Name" required 
                       pattern="^[A-Za-z\s]+$" 
                       title="Name must contain only alphabets and spaces.">
                
                <input type="email" id="regEmail" name="email" placeholder="Email" required 
                       pattern="^[a-z0-9._%+-]+@[a-z0-9.-]+\.com$" 
                       title="Enter a valid email ending in .com">
                
                <input type="password" name="password" placeholder="Password" required>
                <input type="hidden" name="role" value="user">
                
                <button type="submit" name="register">Register</button>
                
                <p class="switch-text">
                    Already have an account? 
                    <span class="switch-link" onclick="toggleForm()">Login</span>
                </p>
            </form>
        </div>
    </div>

    <div class="login-right">
        <h1>Your Task <br> Management System.</h1>
        <p>Manage your daily tasks efficiently with a clean and simple interface.</p>
    </div>
</div>

<script>
// Toggle between Login and Register forms
function toggleForm() {
    let login = document.getElementById("loginForm");
    let register = document.getElementById("registerForm");
    if (login.style.display === "none") {
        login.style.display = "block";
        register.style.display = "none";
    } else {
        login.style.display = "none";
        register.style.display = "block";
    }
}

// Handle URL parameters for alerts
window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.get('error') === 'exists') {
        alert("Email already exists!");
        toggleForm(); 
    }
    
    if (urlParams.get('success') === '1') {
        alert("Registration Successful! Please login.");
    }

    if (urlParams.get('error') === 'invalidname') {
        alert("Invalid Name! Use only alphabets.");
        toggleForm();
    }
}

/** * FORM VALIDATION LOGIC 
 */

// Login Validation
document.getElementById("loginForm").addEventListener("submit", function(e){
    let email = this.email.value.trim();
    let emailPattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.com$/;
    
    if(!emailPattern.test(email)){
        alert("Please enter a valid email address!");
        e.preventDefault();
    }
});

// Register Validation (Including Name Check)
document.getElementById("registerForm").addEventListener("submit", function(e){
    let name = document.getElementById("regName").value.trim();
    let email = document.getElementById("regEmail").value.trim();
    
    // Regex: Start to End, only Alphabets (A-Z, a-z) and Spaces (\s)
    let namePattern = /^[A-Za-z\s]+$/;
    let emailPattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.com$/;

    // Validate Name
    if(!namePattern.test(name)){
        alert("Error: Name must contain alphabets only. No digits or symbols allowed!");
        e.preventDefault();
        return; 
    }

    // Validate Email
    if(!emailPattern.test(email)){
        alert("Error: Please enter a valid email address!");
        e.preventDefault();
    }
});
</script>
</body>
</html>