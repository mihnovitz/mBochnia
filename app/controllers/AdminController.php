<?php

class AdminController
{
    public function __construct()
    {
        // session_start();

        // Require admin privileges for every admin action
        if (empty($_SESSION['user']) || $_SESSION['user']['is_admin'] != true) {
            http_response_code(403);
            echo "Access denied. Admins only.";
            exit;
        }

        // Optional: define constant for admin views
        if (!defined('ADMIN_VIEWS')) {
            define('ADMIN_VIEWS', APP_PATH . '/views/admin');
        }

        require_once APP_PATH . '/models/Post.php';
        require_once APP_PATH . '/models/User.php';
    }

    /**
     * GET /admin
     * Admin dashboard
     */
    public function index()
    {
        require ADMIN_VIEWS . '/dashboard.php';
    }

    /**
     * GET /admin/posts
     * List all posts + form to create new ones
     */
    /*public function posts()
    {
    	$postModel = new Post();
    	$posts = $postModel->getAll();  // ✅ instance call
    	require ADMIN_VIEWS . '/posts.php';
    }*/
    public function posts()
{
    $postModel = new Post();
    $posts = $postModel->getAll();
    require ADMIN_VIEWS . '/posts.php';
}

    /**
     * POST /admin/posts/create
     * Create new post
     */
    /*public function createPost()
    {
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $author_id = $_SESSION['user']['id'] ?? null;

        if ($title === '' || $content === '') {
            echo "Title and content are required.";
            return;
        }

        Post::create($title, $content, $author_id);
        header('Location: /admin/posts');
        exit;
    }*/
    public function createPost()
{
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $author_id = $_SESSION['user']['id'] ?? null;

    if ($title === '' || $content === '') {
        echo "Title and content are required.";
        return;
    }

    $postModel = new Post();
    $postModel->create($title, $content, $author_id);

    header('Location: /admin/posts');
    exit;
}


    /**
     * POST /admin/posts/delete
     * Delete a post by ID
     */
    /*public function deletePost()
    {
        $id = intval($_POST['id'] ?? 0);

        if ($id > 0) {
            Post::delete($id);
        }

        header('Location: /admin/posts');
        exit;
    }*/
    
    // In deletePost()
public function deletePost()
{
    $id = intval($_POST['id'] ?? 0);

    if ($id > 0) {
        $postModel = new Post();
        $postModel->delete($id);
    }

    header('Location: /admin/posts');
    exit;
}

    /**
     * GET /admin/users
     * List all users (excluding current admin)
     */
    public function users()
    {
        //$users = User::getAll();
        $userModel = new User();
	$users = $userModel->getAll();
        require ADMIN_VIEWS . '/users.php';
        
    }

    /**
     * POST /admin/users/delete
     * Delete a user by ID
     */
    public function deleteUser()
    {
	$id = intval($_POST['id'] ?? 0);

   	// Prevent admin from deleting themselves
   	if ($id > 0 && $id != $_SESSION['user']['id']) {
        $userModel = new User();
        $userModel->delete($id); // ✅ Correct usage
    }

    header('Location: /admin/users');
    exit;
}
    
    public function createAdmin()
{
    $data = [
        'first_name' => trim($_POST['first_name'] ?? ''),
        'last_name' => trim($_POST['last_name'] ?? ''),
        'address' => trim($_POST['address'] ?? ''),
        'phone' => trim($_POST['phone'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'password' => $_POST['password'] ?? '',
    ];

    // Basic validation
    foreach ($data as $key => $value) {
        if ($value === '') {
            echo "All fields are required.";
            return;
        }
    }

    // Hash password
    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

    // Create new admin manually via SQL
    $config = require BASE_PATH . '/config/database.php';
    $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";
    $db = new PDO($dsn, $config['user'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    $stmt = $db->prepare("
        INSERT INTO users (first_name, last_name, address, phone, email, password, is_admin)
        VALUES (:first_name, :last_name, :address, :phone, :email, :password, TRUE)
    ");
    $stmt->execute($data);

    header('Location: /admin');
    exit;
}

    
}
