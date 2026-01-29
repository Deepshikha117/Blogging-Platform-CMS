<?php
// includes/auth.php
require_once '../includes/header.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

