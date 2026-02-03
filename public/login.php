<?php

session_start();
require_once '../config/db.php';

// If already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get form data safely
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Basic validation
    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {

        // Prepare SQL to prevent SQL Injection
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify user and password
        if ($user && password_verify($password, $user['password'])) {

            // Store user data in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect to dashboard
            header('Location: dashboard.php');
            exit;

        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Blog CMS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: white;

        }
        .login-box {
            width: 350px;
            margin: 100px auto;
            background: #fff;
            padding: 10px;
            border: 1px solid #ddd;
            margin-top: 15px;
        }
        h2 {
            text-align: center;
        }
        input {
            width: 90%;
            padding: 10px;
            margin-top: 10px;
        }
        button {
            width: 97%;
            padding: 10px;
            margin-top: 15px;
            background: #0065CB;
            color: #fff;
            border: none;
        }
        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
        .logo {
            width: 200px;
            height: 250px;
            transition: transform 0.3s ease;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .logo:hover {
            transform: scale(1.08);
        }
        /* Header left */
        .header-left {
            display: flex;
            align-items: center;
        }

    </style>
</head>
<body>
<div class="header-left">
        <img src="../assets/images/CMSLogo2.png" alt="Blog CMS Logo" class="logo">
    </div>
<div class="login-box">
    <h2>Admin Login</h2>
    
  
    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
