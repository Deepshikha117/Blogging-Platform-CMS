<?php
require_once '../includes/auth.php';
require_once '../config/db.php';



if (!isset($_GET['id'])) {
    header('Location: manage.php');
    exit;
}

$post_id = $_GET['id'];

// Delete post
$stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
$stmt->execute([$post_id]);

header('Location: manage.php');
exit;
