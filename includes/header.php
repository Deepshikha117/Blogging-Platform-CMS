<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600&family=Inter:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <meta charset="UTF-8">
    <title>Blogging Platform CMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<header class="main-header">

    <div class="header-left">
        <img src="../assets/images/LogoCMS.png" alt="Blog CMS Logo" class="logo">
        <h1 class="app-title">Blogging Platform CMS</h1>
    </div>

    <!-- Hamburger -->
    <button class="hamburger">
    <i class="fas fa-bars"></i>
</button>


    <!-- Navigation -->
    <nav id="mainNav">
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="../public/category.php"><i class="fa-solid fa-layer-group"></i> Categories</a>
            <a href="../public/tag.php"><i class="fa-solid fa-tags"></i> Tags</a>
        <?php endif; ?>

        <a href="../public/dashboard.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
        <a href="../public/add_post.php"><i class="fa-solid fa-plus"></i> Add Post</a>
        <a href="../public/manage.php"><i class="fa-solid fa-list"></i> Manage Posts</a>
        <a href="../public/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </nav>
</header>
<script src="../assets/js/fetch.js" defer></script>

<div class="wrapper">

<script>
document.addEventListener("DOMContentLoaded", function () {
    const hamburger = document.querySelector(".hamburger");
    const nav = document.getElementById("mainNav");

    if (hamburger && nav) {
        hamburger.addEventListener("click", function () {
            nav.classList.toggle("open");
        });
    }
});
</script>

