<!DOCTYPE html>
<html>
<head>
    <title>Login | Task Manager</title>
    <link rel="stylesheet" href="assets/css/auth.css">

<div class="login-container">

    <!-- LEFT SIDE -->
    <div class="login-left">

        <div class="form-box">

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
                    <span onclick="toggleForm()">Register</span>
                </p>
            </form>

            <!-- REGISTER FORM -->
            <form id="registerForm" method="POST" action="auth/register.php" style="display:none;">
    <h2>Create Account</h2>

    <input type="text" name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>

    <!-- ✅ ROLE DROPDOWN (ADDED) -->
    <select name="role">
        <option value="user">User</option>

        <?php
        include("config/db.php");
        $check = $conn->query("SELECT * FROM users WHERE role='admin'");
        if ($check->num_rows == 0) {
            echo "<option value='admin'>Admin</option>";
        }
        ?>
    </select>

    <button type="submit" name="register">Register</button>

    <p class="switch-text">
        Already have an account?
        <span onclick="toggleForm()">Login</span>
    </p>
</form>

        </div>

    </div>

    <!-- RIGHT SIDE -->
    <div class="login-right">
        <h1>Your Task <br> Management System.</h1>
        <p>Manage your daily tasks efficiently with a clean and simple interface.</p>
    </div>

</div>

<script>
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
</script>
</body>
</html>