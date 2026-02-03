<?php
require_once "../config/db.php";

header('Content-Type: application/json; charset=UTF-8');

$q = $_GET['q'] ?? '';

if ($q) {
    $search = "%$q%";
    $stmt = $pdo->prepare("
        SELECT id, title 
        FROM posts 
        WHERE title LIKE ?
        ORDER BY created_at DESC 
        LIMIT 7
    ");
    $stmt->execute([$search]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
    exit;
}

echo json_encode([]);
