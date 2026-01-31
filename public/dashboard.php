<?php
require_once '../includes/auth.php';
require_once '../includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Blog CMS</title>
    
</head>
<body>

<div class="container">
    <h2 class="page-title">Dashboard</h2>
    <h3>
    <span class="material-icons">add_circle</span>
    Add New Post
</h3>

    <div class="dashboard-grid">

        <div class="dashboard-card">
            <h3>Add New Post</h3>
            <p>Create a new blog post</p>
            <a href="add_post.php" class="btn btn-success">Add Post</a>
        </div>

        <div class="dashboard-card">
            <h3>Manage Posts</h3>
            <p>Edit or delete posts</p>
            <a href="manage.php" class="btn btn-warning">Manage</a>
        </div>

        <div class="dashboard-card">
            <h3>Logout</h3>
            <p>Securely exit dashboard</p>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>

    </div>
</div>


</body>

</html>
<?php require_once '../includes/footer.php'; ?>