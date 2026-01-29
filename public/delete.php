<?php
require_once '../includes/auth.php';
require_once '../config/db.php';
require_once '../includes/header.php';


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
<?php require_once '../includes/footer.php'; ?>