<?php

class Post
{
    private PDO $db;

    public function __construct()
    {
        $config = require BASE_PATH . '/config/database.php';
        $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";
        $this->db = new PDO($dsn, $config['user'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    }

    /**
     * Fetch all posts (latest first)
     */
     /*
    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT p.id, p.title, p.content, p.created_at,
                   u.first_name || ' ' || u.last_name AS author_name
            FROM posts p
            JOIN users u ON p.author_id = u.id
            ORDER BY p.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    */
    public function getAll(): array
{
    $stmt = $this->db->query("
        SELECT id, title, content, author_id, created_at
        FROM posts
        ORDER BY created_at DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    /**
     * Create new post
     */
    public function create(string $title, string $content, int $author_id): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO posts (title, content, author_id)
            VALUES (:title, :content, :author_id)
        ");
        return $stmt->execute([
            'title' => $title,
            'content' => $content,
            'author_id' => $author_id,
        ]);
    }

    /**
     * Delete post by ID
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM posts WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
