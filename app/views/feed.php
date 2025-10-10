<h1>Welcome to mBochnia</h1>

<p>Hello, <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>!</p>

<?php if ($user['is_admin']): ?>
    <p>You are logged in as <strong>Admin</strong>.</p>
<?php else: ?>
    <p>You are logged in as <strong>User</strong>.</p>
<?php endif; ?>

<p><a href="/logout">Logout</a></p>

