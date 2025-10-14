<?php

class User
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

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO users (first_name, last_name, address, phone, email, password, is_admin)
            VALUES (:first_name, :last_name, :address, :phone, :email, :password, FALSE)
        ");
        return $stmt->execute($data);
    }
    
    public function getAll(): array
    {
    	$stmt = $this->db->query("SELECT id, first_name, last_name, email, is_admin FROM users ORDER BY id ASC");
    	return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete(int $id): bool
    {
    	$stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
    	return $stmt->execute(['id' => $id]);
    }
    
}
