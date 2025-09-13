<?php
// app/models/Application.php

class Application {
    protected $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAll() {
        $sql = "SELECT *, TO_CHAR(data, 'DD.MM.YYYY') as formatted_date 
                FROM applications 
                ORDER BY data DESC";

        return $this->db->fetchAll($sql);
    }

    public function getUserApplications($pesel) {
        $sql = "SELECT *, TO_CHAR(data, 'DD.MM.YYYY') as formatted_date 
                FROM applications 
                WHERE autor = ? 
                ORDER BY data DESC";

        return $this->db->fetchAll($sql, [$pesel]);
    }

    public function getById($id) {
        $sql = "SELECT * FROM applications WHERE id = ?";
        return $this->db->fetch($sql, [$id]);
    }

    public function create($applicationData) {
        $sql = "INSERT INTO applications (data, tresc, autor, informacja_zwrotna, status) 
                VALUES (?, ?, ?, ?, ?)";

        return $this->db->query($sql, [
            $applicationData['data'],
            $applicationData['tresc'],
            $applicationData['autor'],
            $applicationData['informacja_zwrotna'],
            $applicationData['status']
        ]);
    }

    public function updateResponse($id, $response) {
        $sql = "UPDATE applications 
                SET informacja_zwrotna = ?, status = 'answered', updated_at = CURRENT_TIMESTAMP 
                WHERE id = ?";

        return $this->db->query($sql, [$response, $id]);
    }

    public function migrateFromFile($filename = 'wnioski.txt') {
        if (!file_exists($filename)) {
            return false;
        }

        $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if (!$lines) {
            return false;
        }

        // Clear existing data
        $this->db->query("DELETE FROM applications");

        // Import from file
        for ($i = 0; $i < count($lines); $i += 5) {
            if (isset($lines[$i]) && isset($lines[$i+1]) && isset($lines[$i+2]) && isset($lines[$i+3]) && isset($lines[$i+4])) {
                $this->create([
                    'data' => trim($lines[$i+1]),
                    'tresc' => trim($lines[$i+2]),
                    'autor' => trim($lines[$i+3]),
                    'informacja_zwrotna' => trim($lines[$i+4]),
                    'status' => !empty(trim($lines[$i+4])) ? 'answered' : 'pending'
                ]);
            }
        }

        return true;
    }
}
?>