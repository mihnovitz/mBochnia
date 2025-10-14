<?php
// app/views/admin/dashboard.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="/css/style.css">
    <meta charset="UTF-8">
    <title>Welcome to mBochnia</title>
</head>

<body>

<h1>Admin Dashboard</h1>

<p>Welcome, <strong><?= htmlspecialchars($_SESSION['user']['first_name']) ?></strong>!</p>

<nav>
    <ul>
        <li><a href="/admin/posts">📰 Manage Posts</a></li>
        <li><a href="/admin/users">👥 Manage Users</a></li>
    </ul>
</nav>

<hr>

<h2>Create New Admin User</h2>

<form method="post" action="/admin/users/create">
    <label>First Name:</label>
    <input type="text" name="first_name" required>

    <label>Last Name:</label>
    <input type="text" name="last_name" required>

    <label>Address:</label>
    <input type="text" name="address" required>

    <label>Phone:</label>
    <input type="text" name="phone" required>

    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Password:</label>
    <input type="password" name="password" required>

    <button type="submit">Create Admin</button>
</form>

<hr>

<p><a href="/feed">← Back to Feed</a> | <a href="/logout">Logout</a></p>
</body>
