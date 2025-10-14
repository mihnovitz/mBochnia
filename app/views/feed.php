<?php /** @var array $posts */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="/css/style.css">
    <meta charset="UTF-8">
    <title>mBochnia — News Feed</title>
</head>
<body>

<header>
    <h1>mBochnia — City Feed</h1>
    <div class="nav-buttons">
        <a href="/account">My Account</a>
        <a href="/logout">Logout</a>
    </div>
</header>

<main>
    <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <h2><?= htmlspecialchars($post['title']) ?></h2>
                <div class="meta">
                    Posted on <?= htmlspecialchars($post['created_at']) ?>
                    <?php if (!empty($post['author_name'])): ?>
                        by <?= htmlspecialchars($post['author_name']) ?>
                    <?php endif; ?>
                </div>
                <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="empty">No posts available yet. Please check back later.</p>
    <?php endif; ?>
</main>

</body>
</html>

