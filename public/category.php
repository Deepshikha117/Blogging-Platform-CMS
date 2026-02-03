<?php
require_once "../includes/auth.php";
require_once "../config/db.php";
require_once '../includes/header.php';

require_once '../includes/functions.php';


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);

    if ($name) {
        $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->execute([$name]);
        header("Location: category.php");
        exit;
    }
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Categories</title>
</head>
<body>
<div class="container">
<h2>Add Category</h2>

<form method="post">
    <input type="text" name="name" placeholder="Category name" required>
    <button type="submit">Add</button>
</form>

<hr>

<h3>Existing Categories</h3>
<ul>
<?php foreach ($categories as $c): ?>
    <li><?= htmlspecialchars($c['name']) ?></li>
<?php endforeach; ?>
</ul>
</div>
</body>
</html>
