<?php /** @var array $_SESSION */ ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard — mBochnia</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

    <header class="header container">
        <h1>Admin Dashboard</h1>
        <p>Welcome, <strong><?= htmlspecialchars($_SESSION['user']['first_name']) ?></strong>!</p>
    </header>

    <main class="container dashboard">
        <!-- Sidebar Navigation -->
        <aside class="dashboard-sidebar">
            <h2>Navigation</h2>
            <nav class="admin-nav">
                <a href="/admin/posts" class="btn btn-primary full-width">📰 Manage Posts</a>
                <a href="/admin/users" class="btn btn-primary full-width">👥 Manage Users</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <section class="dashboard-content">
            <!-- Create New Admin Card -->
            <div class="card">
                <h2>Create New Admin User</h2>
                <form method="POST" action="/admin/users/create" class="form">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" required>
                    </div>

                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" required>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" name="phone" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <div class="form-actions center">
                        <button type="submit" class="btn btn-primary">Create Admin</button>
                    </div>
                </form>
            </div>
        </section>
    </main>
    
    <div class="container center">
	    <div class="action-buttons">
		<a href="/feed" class="btn btn-secondary">Feed</a>
		<a href="/logout" class="btn btn-light">Logout</a>
	    </div>
    </div>


</body>
</html>

