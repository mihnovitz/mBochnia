<?php /** @var array $posts */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel — Manage Posts</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<header class="header container">
    <h1>Manage Posts</h1>
</header>

<main class="container">
    <!-- Create Post Section -->
    <section class="card">
        <h2>Create New Post</h2>
        <form method="POST" action="/admin/posts/create" class="form">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content" rows="6" required></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create Post</button>
            </div>
        </form>
    </section>

    <!-- Existing Posts Section -->
    <section class="card">
        <h2>Existing Posts</h2>

        <?php if (!empty($posts)): ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $post): ?>
                            <tr>
                                <td><?= htmlspecialchars($post['id']) ?></td>
                                <td><?= htmlspecialchars($post['title']) ?></td>
                                <td><?= htmlspecialchars($post['author_id'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars(date('Y-m-d H:i', strtotime($post['created_at']))) ?></td>
                                <td>
                                    <form method="POST" action="/admin/posts/delete" class="inline-form" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($post['id']) ?>">
                                        <button type="submit" class="btn btn-light btn-small">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="empty">No posts available yet.</p>
        <?php endif; ?>
    </section>

    <div class="center">
        <a href="/admin" class="btn btn-secondary">← Back to Dashboard</a>
    </div>
</main>

<footer class="footer container">
    <p>&copy; <?= date('Y') ?> mBochnia</p>
</footer>

</body>
</html>

