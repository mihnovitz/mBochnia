<?php

class AdminController
{
    public function __construct()
    {

	if (empty($_SESSION['user']) || $_SESSION['user']['is_admin'] != true) {
	    http_response_code(403);
	    require __DIR__ . '/../views/admin/access_denied.php';
	    exit;
	}

	if (!defined('ADMIN_VIEWS')) {
	    define('ADMIN_VIEWS', APP_PATH . '/views/admin');
	}

	require_once APP_PATH . '/models/Post.php';
	require_once APP_PATH . '/models/User.php';
    }

    public function index()
    {
	require ADMIN_VIEWS . '/dashboard.php';
    }

    public function posts()
    {
    	$postModel = new Post();
    	$posts = $postModel->getAll();
    	require ADMIN_VIEWS . '/posts.php';
    }

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

    public function users()
    {
	$userModel = new User();
	$users = $userModel->getAll();
	require ADMIN_VIEWS . '/users.php';
	
    }

    public function deleteUser()
    {
	$id = intval($_POST['id'] ?? 0);

   	if ($id > 0 && $id != $_SESSION['user']['id']) {
		$userModel = new User();
		$userModel->delete($id);
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
	
	foreach ($data as $key => $value) {
		if ($value === '') {
		    echo "All fields are required.";
		    return;
		}
	}

    	$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

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
