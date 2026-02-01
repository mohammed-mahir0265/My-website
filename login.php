<?php
session_start();
require "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if ($email === "" || $password === "") {
        die("Email and password are required.");
    }

    $query = "SELECT id, name, password FROM users WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->execute(["email" => $email]);

    if ($stmt->rowCount() === 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["name"];

            header("Location: home.php");
            exit();
        }
    }

    die("Invalid email or password.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
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

.success-msg {
    display: none;
    background-color: #d1fae5;
    color: #065f46;
    padding: 10px 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
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

            <h2>Login</h2>

            <p id="successMsg" class="success-msg">
                User registered successfully! Please login.
            </p>

            <form action="login.php" method="POST">

                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="you@example.com" required>
                </div>

                <div class="input-group password-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter your Password" required>
                    <button type="button" onclick="togglePasswords(this)" class="toggle-btn">Show</button>
                </div>

                <button type="submit" class="signup-btn">Login</button>
            </form>

            <p class="login-text">
                Don't have an account? <a href="register.php">Sign up</a>
            </p>
        </div>

    </div>

    <script>
        const pass = document.getElementById('password');

        function togglePasswords(btn) {
            const show = pass.type === 'password';
            pass.type = show ? 'text' : 'password';
            btn.textContent = show ? 'Hide' : 'Show';
        }

        const params = new URLSearchParams(window.location.search);
        if (params.get("success") === "registered") {
            document.getElementById("successMsg").style.display = 'block';
        }
    </script>

</body>
</html>


