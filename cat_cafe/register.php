<?php
require 'db.php';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first = $_POST['first_name'];
    $last = $_POST['last_name'];
    $user = $_POST['username'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $age = $_POST['age'];
    $address = $_POST['address'];

    try {
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, username, password, age, address) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$first, $last, $user, $pass, $age, $address]);
        $message = "<div style='color:green;'>Registration successful! <a href='login.php'>Login here</a></div>";
    } catch(PDOException $e) {
        $message = "<div style='color:red;'>Username already exists!</div>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* CSS for the Eye Icon */
        .password-wrapper { position: relative; width: 100%; }
        .password-wrapper input { padding-right: 40px; } 
        .toggle-password {
            position: absolute; right: 15px; top: 50%; transform: translateY(-50%);
            cursor: pointer; color: #888; font-size: 18px; user-select: none;
        }
        .toggle-password:hover { color: #d97736; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register New User</h2>
        <?= $message ?>
        <form method="POST">
            <label>First Name:</label> 
            <input type="text" name="first_name" required>
            
            <label>Last Name:</label> 
            <input type="text" name="last_name" required>
            
            <label>Username:</label> 
            <input type="text" name="username" required>
            
            <label>Password:</label>
            <div class="password-wrapper">
                <input type="password" name="password" id="regPass" required>
                <span class="toggle-password" onclick="togglePassword('regPass', this)">👁️</span>
            </div>
            
            <label>Age:</label> 
            <input type="number" name="age">
            
            <label>Address:</label> 
            <input type="text" name="address">
            
            <button type="submit">Register</button>
        </form>
        <br><a href="login.php">Already have an account? Login here.</a>
    </div>

    <!-- JavaScript for the Eye Icon -->
    <script>
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                icon.innerHTML = "🙈"; 
            } else {
                input.type = "password";
                icon.innerHTML = "👁️"; 
            }
        }
    </script>
</body>
</html>