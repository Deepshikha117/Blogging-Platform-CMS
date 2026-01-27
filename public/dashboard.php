<?php
require_once '../includes/auth.php';

include '../includes/header.php'; 

include '../includes/footer.php'; 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Blog CMS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }
        .dashboard {
            width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
        }
        a {
            display: block;
            margin: 10px 0;
            text-decoration: none;
            color: #333;
        }
    </style>
</head>
<body>

<div class="dashboard">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></h2>

    <a href="add_post.php">➕ Add New Post</a>
    <a href="manage.php">📝 Manage Posts</a>
    <a href="logout.php">🚪 Logout</a>
</div>

</body>
</html>
