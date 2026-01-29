<?php
require_once "../includes/auth.php";
require_once "../config/db.php";
require_once '../includes/header.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);

    if ($name) {
        $stmt = $pdo->prepare("INSERT INTO tags (name) VALUES (?)");
        $stmt->execute([$name]);
        header("Location: tag.php");
        exit;
    }
}

$tags = $pdo->query("SELECT * FROM tags ORDER BY name")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Tags</title>
</head>
<body>

<h2>Add Tag</h2>

<form method="post">
    <input type="text" name="name" placeholder="Tag name" required>
    <button type="submit">Add</button>
</form>

<hr>

<h3>Existing Tags</h3>
<ul>
<?php foreach ($tags as $t): ?>
    <li><?= htmlspecialchars($t['name']) ?></li>
<?php endforeach; ?>
</ul>

</body>
</html>
<?php require_once '../includes/footer.php'; ?>