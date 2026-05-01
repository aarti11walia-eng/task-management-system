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
        .switch-link {
            cursor: pointer; 
            color: #2563eb; 
            font-weight: 600;
            text-decoration: none;
        }
        .switch-link:hover {
            text-decoration: underline;
        }
        #otpSection, #passwordSection {
            margin-top: 10px;
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

            <!-- LOGIN FORM -->
            <form id="loginForm" method="POST" action="auth/login.php">
                <h2>Log In</h2>
                <input type="email" name="email" placeholder="Email" required>
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

            <!-- REGISTER FORM -->
            <form id="registerForm" method="POST" action="auth/register.php" style="display:none;">
                <h2>Create Account</h2>

                <!-- STEP 1: Name & Email -->
                <input type="text" id="regName" name="name" placeholder="Name" required>
                <input type="email" id="regEmail" name="email" placeholder="Email "required>

                <button type="button" id="sendOtpBtn">Send OTP</button>

                <!-- STEP 2: OTP Verification -->
                <div id="otpSection" style="display:none;">
                    <input type="text" id="otpInput" placeholder="Enter 6-digit OTP" maxlength="6">
                    <button type="button" id="verifyOtpBtn">Verify OTP</button>
                </div>

                <!-- STEP 3: Password -->
                <div id="passwordSection" style="display:none;">
                    <input type="password" name="password" id="regPassword" placeholder="Create Password" required>
                    <input type="hidden" name="role" value="user">
                    <button type="submit" name="register">Register</button>
                </div>

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
// --- FORM TOGGLE LOGIC ---
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

/**
 * EMAIL VALIDATION LOGIC
 * 1. Must contain at least one letter (prevents numbers-only emails)
 * 2. Must end exactly in .com
 */
function isValidEmail(email) {
    const emailPattern = /^(?=.*[a-zA-Z])[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.com$/;
    return emailPattern.test(email);
}

// --- STEP 1: SEND OTP ---
document.getElementById("sendOtpBtn").addEventListener("click", function(){
    let email = document.getElementById("regEmail").value.trim();
    let name = document.getElementById("regName").value.trim();
    let btn = this;

    if(!name || !email){
        alert("Please enter both name and email!");
        return;
    }

    if(!isValidEmail(email)){
        alert("Invalid Email! ");
        return;
    }

    btn.innerText = "Sending...";
    btn.disabled = true;

    fetch("auth/send_otp.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "email=" + encodeURIComponent(email) + "&name=" + encodeURIComponent(name)
    })
    .then(res => res.text())
    .then(data => {
        alert(data.trim());
        document.getElementById("otpSection").style.display = "block";
        btn.innerText = "Resend OTP";
        btn.disabled = false;
    })
    .catch(err => {
        alert("Error sending OTP. Check connection.");
        btn.disabled = false;
    });
});

// --- STEP 2: VERIFY OTP ---
document.getElementById("verifyOtpBtn").addEventListener("click", function(){
    let otp = document.getElementById("otpInput").value.trim();
    let verifyBtn = this;

    if(otp.length < 6){
        alert("Enter a valid 6-digit OTP");
        return;
    }

    fetch("auth/verify_otp.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "otp=" + encodeURIComponent(otp)
    })
    .then(res => res.text())
    .then(data => {
        let response = data.trim();
        if(response === "success"){
            alert("OTP Verified Successfully!");
            document.getElementById("passwordSection").style.display = "block";
            document.getElementById("otpSection").style.display = "none";
            document.getElementById("sendOtpBtn").style.display = "none";
            document.getElementById("regEmail").readOnly = true;
            document.getElementById("regName").readOnly = true;
        } else {
            alert("Invalid OTP! Please check again.");
        }
    });
});

// --- FINAL VALIDATION ON SUBMIT ---
document.getElementById("registerForm").addEventListener("submit", function(e){
    let password = document.getElementById("regPassword").value;
    let email = document.getElementById("regEmail").value.trim();

    if(!isValidEmail(email)){
        alert("Proper email validation failed.");
        e.preventDefault();
        return;
    }

    
});

// --- URL PARAMETER ALERTS ---
window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('error') === 'exists') {
        alert("This email is already registered!");
        toggleForm(); 
    }
    if (urlParams.get('success') === '1') {
        alert("Registration Successful! You can now log in.");
    }
}
</script>
</body>
</html>