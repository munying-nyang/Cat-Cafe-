<?php
require 'db.php';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first = $_POST['first_name'];
    $last = $_POST['last_name'];
    $user = $_POST['username'];
    $raw_password = $_POST['password']; // Changed to get raw password for validation
    $age = $_POST['age'];
    $address = $_POST['address'];

    // check passwords
    
    // if valid password
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $raw_password)) {
        $message = "<div style='color:red;'>Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number.</div>";
    } else {
        // hash
        $pass = password_hash($raw_password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, username, password, age, address) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$first, $last, $user, $pass, $age, $address]);
            $message = "<div style='color:green;'>Registration successful! <a href='login.php'>Login here</a></div>";
        } catch(PDOException $e) {
            $message = "<div style='color:red;'>Username already exists!</div>";
        }
    }
    
}
?>
<!DOCTYPE html>
<html>
<head><title>Register</title><link rel="stylesheet" href="style.css"></head>
<body>
    <div class="container">
        <h2>Register New User</h2>
        <?= $message ?>
        <form method="POST">
            <label>First Name:</label> <input type="text" name="first_name" required>
            <label>Last Name:</label> <input type="text" name="last_name" required>
            <label>Username:</label> <input type="text" name="username" required>
            
            <label>Password:</label>
            <div class="password-wrapper">
                <input type="password" name="password" id="regPass" required 
                       pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" 
                       title="Must contain at least one number, one uppercase and lowercase letter, and at least 8 characters">
                <span class="toggle-password" onclick="togglePassword('regPass', this)">👁️</span>
            </div>
            
            <label>Age:</label> <input type="number" name="age">
            <label>Address:</label> <input type="text" name="address">
            <button type="submit">Register</button>
        </form>
        <br><a href="login.php">Already have an account? Login here.</a>
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