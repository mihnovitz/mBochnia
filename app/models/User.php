<?php
// app/models/User.php

class User {
    protected $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllUsers() {
        $sql = "SELECT *, 
                       CASE WHEN admin = 't' THEN 'TAK' ELSE 'NIE' END as admin_display,
                       TO_CHAR(data_urodzenia, 'DD.MM.YYYY') as formatted_dob,
                       TO_CHAR(saldo, 'FM999,999,990.00') || ' zł' as formatted_saldo
                FROM account_doc 
                ORDER BY nazwisko, imie";

        return $this->db->fetchAll($sql);
    }

    public function create($userData) {
        $sql = "INSERT INTO account_doc (pesel, imie, nazwisko, data_urodzenia, plec, saldo, admin, haslo, email) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        return $this->db->query($sql, [
            $userData['pesel'],
            $userData['imie'],
            $userData['nazwisko'],
            $userData['data_urodzenia'],
            $userData['plec'],
            $userData['saldo'],
            $userData['admin'],
            $userData['haslo'],
            $userData['email']
        ]);
    }

    public function userExists($pesel, $email) {
        $sql = "SELECT COUNT(*) FROM account_doc WHERE pesel = ? OR email = ?";
        $stmt = $this->db->query($sql, [$pesel, $email]);
        return $stmt->fetchColumn() > 0;
    }

    public function getUserByPesel($pesel) {
        $sql = "SELECT * FROM account_doc WHERE pesel = ?";
        return $this->db->fetch($sql, [$pesel]);
    }

    // In your existing User.php class, enhance the deleteUser method:

    public function deleteUser($pesel) {
        try {
            // First, check if user exists
            $user = $this->getUserByPesel($pesel);
            if (!$user) {
                throw new Exception("Użytkownik o podanym PESELu nie istnieje.");
            }

            // Prevent self-deletion (optional security measure)
            $currentUser = Auth::user();
            if ($currentUser['id'] === $pesel) {
                throw new Exception("Nie możesz usunąć własnego konta.");
            }

            $sql = "DELETE FROM account_doc WHERE pesel = ?";
            $stmt = $this->db->query($sql, [$pesel]);

            // Check if any row was actually deleted
            if ($stmt->rowCount() === 0) {
                throw new Exception("Nie udało się usunąć użytkownika.");
            }

            return true;

        } catch (PDOException $e) {
            // Handle database constraints (foreign key issues)
            if (strpos($e->getMessage(), 'foreign key') !== false) {
                throw new Exception("Nie można usunąć użytkownika, ponieważ posiada powiązane dane (karty, wnioski).");
            }
            throw new Exception("Błąd bazy danych: " . $e->getMessage());
        }
    }

    public function update($userData) {
        $sql = "UPDATE account_doc 
            SET imie = ?, nazwisko = ?, data_urodzenia = ?, plec = ?, 
                saldo = ?, admin = ?, email = ?
            WHERE pesel = ?";

        return $this->db->query($sql, [
            $userData['imie'],
            $userData['nazwisko'],
            $userData['data_urodzenia'],
            $userData['plec'],
            $userData['saldo'],
            $userData['admin'],
            $userData['email'],
            $userData['pesel']
        ]);
    }

    public function updateWithPassword($userData) {
        $sql = "UPDATE account_doc 
            SET imie = ?, nazwisko = ?, data_urodzenia = ?, plec = ?, 
                saldo = ?, admin = ?, haslo = ?, email = ?
            WHERE pesel = ?";

        return $this->db->query($sql, [
            $userData['imie'],
            $userData['nazwisko'],
            $userData['data_urodzenia'],
            $userData['plec'],
            $userData['saldo'],
            $userData['admin'],
            $userData['haslo'],
            $userData['email'],
            $userData['pesel']
        ]);
    }

    public function emailExistsForOtherUser($email, $currentPesel) {
        $sql = "SELECT COUNT(*) FROM account_doc WHERE email = ? AND pesel != ?";
        $stmt = $this->db->query($sql, [$email, $currentPesel]);
        return $stmt->fetchColumn() > 0;
    }

    // We'll add more user-related methods here later
}
?>