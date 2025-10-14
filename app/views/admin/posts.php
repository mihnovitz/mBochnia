<?php /** @var array $posts */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="/css/style.css">
    <meta charset="UTF-8">
    <title>Admin Panel — Manage Posts</title>
</head>
<body>
<div class="container">
    <h1>Manage Posts</h1>

    <h2>Create New Post</h2>
    <form method="POST" action="/admin/posts/create">
        <label>Title</label>
        <input type="text" name="title" required>

        <label>Content</label>
        <textarea name="content" rows="5" required></textarea>

        <button type="submit">Create Post</button>
    </form>

    <h2>Existing Posts</h2>
    <?php if (!empty($posts)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author ID</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td><?= htmlspecialchars($post['id']) ?></td>
                        <td><?= htmlspecialchars($post['title']) ?></td>
                        <td><?= htmlspecialchars($post['author_id'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($post['created_at']) ?></td>
                        <td>
                            <form method="POST" action="/admin/posts/delete" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($post['id']) ?>">
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No posts yet.</p>
    <?php endif; ?>

    <a href="/admin" class="back">← Back to Dashboard</a>
</div>
</body>
</html>
