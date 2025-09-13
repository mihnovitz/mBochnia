<?php
// app/models/Announcement.php

class Announcement {
    protected $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAll() {
        $sql = "SELECT *, TO_CHAR(data, 'DD.MM.YYYY') as formatted_date 
                FROM announcements 
                ORDER BY data DESC";

        return $this->db->fetchAll($sql);
    }

    public function create($data) {
        $sql = "INSERT INTO announcements (data, watek, autor, tresc) 
                VALUES (?, ?, ?, ?)";

        return $this->db->query($sql, [
            $data['data'],
            $data['watek'],
            $data['autor'],
            $data['tresc']
        ]);
    }

    // We'll migrate from flat file to database
    public function migrateFromFile($filename = 'announcements.txt') {
        if (!file_exists($filename)) {
            return false;
        }

        $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if (!$lines) {
            return false;
        }

        // Clear existing data
        $this->db->query("DELETE FROM announcements");

        // Import from file
        for ($i = 0; $i < count($lines); $i += 5) {
            if (isset($lines[$i]) && isset($lines[$i+1]) && isset($lines[$i+2]) && isset($lines[$i+3]) && isset($lines[$i+4])) {
                $this->create([
                    'data' => trim($lines[$i+1]),
                    'watek' => trim($lines[$i+2]),
                    'autor' => trim($lines[$i+3]),
                    'tresc' => trim($lines[$i+4])
                ]);
            }
        }

        return true;
    }
}
?>