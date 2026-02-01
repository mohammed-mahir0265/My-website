<?php
require "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if ($name === "" || $email === "" || $password === "") {
        die("All fields are required.");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $checkQuery = "SELECT id FROM users WHERE email = :email";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->execute(["email" => $email]);

    if ($checkStmt->rowCount() > 0) {
        die("Email already registered.");
    }

    $insertQuery = "
        INSERT INTO users (name, email, password)
        VALUES (:name, :email, :password)
    ";

    $stmt = $conn->prepare($insertQuery);
    $stmt->execute([
        "name" => $name,
        "email" => $email,
        "password" => $hashedPassword
    ]);

    header("Location: login.php?success=registered");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<style>
  
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background-color: #f0f2f5;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.signup-container {
    width: 100%;
    max-width: 400px;
    padding: 20px;
}

.signup-card {
    background-color: #fff;
    padding: 35px 30px;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    text-align: center;
}

.signup-logo {
    height: 60px;
    margin-bottom: 20px;
}

.signup-card h2 {
    margin-bottom: 25px;
    color: #111827;
    font-size: 26px;
}

.input-group {
    margin-bottom: 20px;
    text-align: left;
    position: relative;
}

.input-group label {
    display: block;
    margin-bottom: 6px;
    color: #374151;
    font-weight: 500;
}

.input-group input {
    width: 100%;
    padding: 10px 14px;
    border-radius: 8px;
    border: 1px solid #d1d5db;
    font-size: 14px;
    color: #111827;
    transition: all 0.2s;
}

.input-group input:focus {
    border-color: #6366f1;
    outline: none;
    box-shadow: 0 0 5px rgba(99,102,241,0.3);
}

.password-group .toggle-btn {
    position: absolute;
    right: 10px;
    top: 35px;
    background: none;
    border: none;
    color: #6b7280;
    cursor: pointer;
    font-size: 13px;
}

.password-group .toggle-btn:hover {
    color: #6366f1;
}

.signup-btn {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 10px;
    background-color: #6366f1;
    color: #fff;
    font-weight: bold;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.2s, transform 0.1s;
}

.signup-btn:hover {
    background-color: #4f46e5;
    transform: translateY(-2px);
}

.login-text {
    margin-top: 20px;
    font-size: 14px;
    color: #6b7280;
}

.login-text a {
    color: #6366f1;
    font-weight: 600;
    text-decoration: none;
}

.login-text a:hover {
    text-decoration: underline;
}

</style>


    <div class="signup-container">

        <div class="signup-card">

            <h2>Create Account</h2>

            <form onsubmit="return validatePasswords()" action="register.php" method="POST">

                <div class="input-group">
                    <label for="fullname">Full Name</label>
                    <input type="text" name="fullname" id="fullname" placeholder="Enter the Full Name" required>
                </div>

                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="you@example.com" required>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter the Password" required>
                </div>

                <div class="input-group password-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm the Password" required>
                    <button type="button" onclick="togglePasswords(this)" class="toggle-btn">Show</button>
                </div>

                <button type="submit" class="signup-btn">Sign Up</button>

            </form>

            <p class="login-text">
                Already have an account? <a href="login.php">Login</a>
            </p>
        </div>

    </div>

    <script>
        const pass1 = document.getElementById('password');
        const pass2 = document.getElementById('confirmPassword');

        function togglePasswords(btn) {
            const show = pass1.type === 'password';
            pass1.type = show ? 'text' : 'password';
            pass2.type = show ? 'text' : 'password';
            btn.textContent = show ? 'Hide' : 'Show';
        }

        function validatePasswords() {
            const passs1 = pass1.value.trim();
            const passs2 = pass2.value.trim();

            if (passs1 !== passs2) {
                alert('Passwords do not match!');
                return false;
            } else if (passs1 === '' || passs2 === '') {
                alert('Password fields cannot be empty!');
                return false;
            }

            return true;
        }
    </script>

</body>

</html>
