<?php
session_start();
require 'db.php';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['full_name'] = $user['first_name'] . ' ' . $user['last_name'];
        header("Location: index.php");
        exit();
    } else {
        $error = "<div style='color:red;'>Invalid username or password!</div>";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Login</title><link rel="stylesheet" href="style.css"></head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?= $error ?>
        <form method="POST">
            <label>Username:</label> <input type="text" name="username" required>
            
            <label>Password:</label>
            <div class="password-wrapper">
                <input type="password" name="password" id="loginPass" required>
                <span class="toggle-password" onclick="togglePassword('loginPass', this)">👁️</span>
            </div>
            
            <button type="submit">Login</button>
        </form>
        <br><a href="register.php">Don't have an account? Register here.</a>
    </div>
    <script>
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === "password") { input.type = "text"; icon.innerHTML = "🙈"; } 
            else { input.type = "password"; icon.innerHTML = "👁️"; }
        }
    </script>
</body>
</html>