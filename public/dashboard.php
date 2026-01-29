<?php
require_once '../includes/auth.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Blog CMS</title>
    <style>
        body {
            background-color:#CBDCB8;
            color: #000000;
            font-family: Oswald;
        }

/* Dashboard container */
.dashboard {
    max-width: 1100px;
    margin: 40px auto;
    padding: 20px;
}

.dashboard h2 {
    margin-bottom: 30px;
    color: #0A1931;
}

/* Cards grid */
.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 20px;
}

/* Individual card */
.card {
    background-color: #FBF9E4;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}

.card h3 {
    margin-top: 0;
}

.card p {
    font-size: 14px;
    color: #444;
}

.btn {
    display: inline-block;
    padding: 10px 16px;
    border-radius: 4px;
    font-size: 14px;
    color: black;
    text-decoration: none;
    margin-top: 10px;
}



.btn:focus {
    outline: 1px solid #000;
}

    </style>
</head>
<body>

<div class="dashboard">
    <h2>Welcome, Eujina</h2>

    <div class="dashboard-cards">
        <div class="card">
            <h3>Add New Post</h3>
            <p>Create a new blog post</p>
            <a href="add_post.php" class="btn btn-success">Add Post</a>
        </div>

        <div class="card">
            <h3>Manage Posts</h3>
            <p>Edit or delete posts</p>
            <a href="manage.php" class="btn btn-warning">Manage</a>
        </div>

        <div class="card">
            <h3>Logout</h3>
            <p>Securely exit dashboard</p>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</div>

</body>

</html>
<?php require_once '../includes/footer.php'; ?>