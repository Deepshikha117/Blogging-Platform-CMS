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
                <?= htmlspecialchars($post['author']) ?>
                <?= date('M d, Y', strtotime($post['created_at'])) ?>
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

<!-- LIVE SEARCH AJAX -->
<script>
const searchInput = document.getElementById("liveSearch");
const resultBox = document.getElementById("liveResults");

searchInput.addEventListener("keyup", function () {
    let q = this.value.trim();
    if (q.length < 2) {
        resultBox.innerHTML = "";
        return;
    }

    fetch("search_ajax.php?q=" + encodeURIComponent(q))
        .then(res => res.json())
        .then(data => {
            let html = "<ul>";
            data.forEach(p => {
                html += `<li><a href="post.php?id=${p.id}">${p.title}</a></li>`;
            });
            html += "</ul>";
            resultBox.innerHTML = html;
        });
});
</script>

<!-- LOAD MORE -->
<script>
let offset = 6;
document.getElementById("loadMore")?.addEventListener("click", function () {
    fetch("load.php?offset=" + offset)
        .then(r => r.text())
        .then(data => {
            if (data.trim() === "") {
                this.innerText = "No more posts";
                this.disabled = true;
            } else {
                document.getElementById("posts").insertAdjacentHTML("beforeend", data);
                offset += 3;
            }
        });
});
</script>

<?php require_once '../includes/footer.php'; ?>
