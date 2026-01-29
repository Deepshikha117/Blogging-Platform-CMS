<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request");
}

if (!verifyToken($_POST['csrf_token'] ?? '')) {
    die("CSRF validation failed");
}

$post_id = (int)$_POST['post_id'];
$name = trim($_POST['name']);
$comment = trim($_POST['comment']);

if ($post_id && $name && $comment) {
    $stmt = $pdo->prepare(
        "INSERT INTO comments (post_id, name, comment, created_at)
         VALUES (?, ?, ?, NOW())"
    );
    $stmt->execute([$post_id, $name, $comment]);
}

header("Location: post.php?id=" . $post_id);
exit;
