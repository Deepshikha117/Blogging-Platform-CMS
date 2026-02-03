<?php
$host = 'localhost';
$dbname = 'np03cs4a240322';
$username = 'np03cs4a240322';
$password = 'xqtnvXuArp'; 
//$host = 'localhost';
//$dbname = 'blog_db';
//$username = 'root';
//$password = ''; 

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $username,
        $password
    );
//Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
