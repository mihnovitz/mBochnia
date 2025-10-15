<?php /** @var array $posts */ ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>mBochnia — News Feed</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

    <header class="header">
        <h1>mBochnia — City Feed</h1>
        <div class="nav-buttons">
            <a href="/account" class="btn btn-light">Konto</a>
            <a href="/logout" class="btn btn-light">Wyloguj się</a>
        </div>
    </header>

    <main class="container">
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <article class="post">
                    <h2><?= htmlspecialchars($post['title']) ?></h2>
                    <div class="meta">
                        Posted on <?= htmlspecialchars($post['created_at']) ?>
                        <?php if (!empty($post['author_name'])): ?>
                            by <?= htmlspecialchars($post['author_name']) ?>
                        <?php endif; ?>
                    </div>
                    <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty center">Jeszcze nie ma wpisów. Sprawdź ponownie później.</p>
        <?php endif; ?>
    </main>

</body>
</html>

