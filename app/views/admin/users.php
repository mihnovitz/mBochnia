<?php
/** @var array $users */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Panel administratora</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>

<header class="header container">
    <h1>Zarządzaj użytkownikami</h1>
</header>

<main class="container">
    <section class="card">
        <h2>Lista użytkowników</h2>

        <?php if (!empty($users)): ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Imie</th>
                            <th>Email</th>
                            <th>Admin</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['id']) ?></td>
                                <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= $user['is_admin'] ? 'Tak' : 'Nie' ?></td>
                                <td>
                                    <?php if ($user['id'] != $_SESSION['user']['id']): ?>
                                        <form method="POST" action="/admin/users/delete" class="inline-form" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
                                            <button type="submit" class="btn btn-light btn-small">Usuń</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted">Ty</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="empty">brak użytkowników</p>
        <?php endif; ?>
    </section>

    <div class="center">
        <a href="/admin" class="btn btn-secondary">← Wróć do strony głównej</a>
        <a href="/logout" class="btn btn-danger">Wylosuj się</a>
    </div>
</main>

<footer class="footer container">
    <p>&copy; <?= date('Y') ?> mBochnia</p>
</footer>

</body>
</html>

