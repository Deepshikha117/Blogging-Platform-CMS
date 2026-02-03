<?php
require_once "../config/db.php";
require_once '../includes/header.php';

// Fetch authors for dropdown
$authorStmt = $pdo->query("SELECT id, username FROM users ORDER BY username");
$authors = $authorStmt->fetchAll(PDO::FETCH_ASSOC);

/* ===== ADVANCED SEARCH QUERY ===== */
$sql = "
    SELECT posts.id, posts.title, posts.created_at, users.username AS author
    FROM posts
    JOIN users ON posts.author_id = users.id
    WHERE 1
";

$params = [];

if (!empty($_GET['keyword'])) {
    $sql .= " AND posts.title LIKE :keyword";
    $params[':keyword'] = '%' . $_GET['keyword'] . '%';
}

if (!empty($_GET['author'])) {
    $sql .= " AND users.id = :author";
    $params[':author'] = $_GET['author'];
}

$sql .= " ORDER BY posts.created_at DESC LIMIT 6";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
$hasResults = count($posts) > 0;
?>

<?php if (!empty($_SESSION['success_message'])): ?>
<div class="alert success">
    <?= htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
</div>
<?php endif; ?>

<div class="container">
    <h1 class="page-title">Blogging Platform CMS</h1>

    <!-- SEARCH SECTION -->
    <div class="search-wrapper">
    <form method="GET" action="index.php" class="advanced-search-bar card">
        <div class="search-item">
            <span class="material-icons">tune</span>
            <strong>Filters:</strong>
        </div>
        
        <div class="search-item flex-grow">
            <input type="text" name="keyword" placeholder="Search by title..." value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>">
        </div>

        <div class="search-item">
            <select name="author">
                <option value="">All Authors</option>
                <?php foreach ($authors as $a): ?>
                    <option value="<?= $a['id'] ?>" <?= (($_GET['author'] ?? '') == $a['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($a['username']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button class="btn btn-primary">Apply</button>
    </form>

    <div class="live-search-container">
        <div class="live-search-input-wrapper">
            <span class="material-icons">bolt</span>
            <input type="text" id="liveSearch" placeholder="Quick search posts..." autocomplete="off">
        </div>
        <div id="liveResults"></div>
    </div>
</div>

    <!-- POSTS -->
    <h2 class="section-title">Latest Posts</h2>
    <div id="posts" class="post-grid">
    <?php foreach ($posts as $post): ?>
    <article class="post-card">
    <div class="post-header">
        <h3 class="post-title"><?= htmlspecialchars($post['title']) ?></h3>
        <span class="post-badge">Blog</span>
    </div>

    <div class="post-meta">
        <span class="material-icons">person</span>
        <?= htmlspecialchars($post['author'] ?? 'Admin') ?>
        <span class="material-icons" style="margin-left:10px">calendar_today</span>
        <?= date('M d, Y', strtotime($post['created_at'] ?? date('Y-m-d'))) ?>
    </div>

    <a class="read-more" href="post.php?id=<?= $post['id'] ?>">
        Continue reading
    </a>
</article>
    <?php endforeach; ?>
</div>

    <?php if ($hasResults && count($posts) === 6): ?>
        <button id="loadMore" class="btn btn-success">Load More</button>
    <?php endif; ?>
</div>


<?php require_once '../includes/footer.php'; ?>
