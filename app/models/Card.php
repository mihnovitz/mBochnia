<?php
// app/models/Card.php

class Card {
    protected $db;

    public function __construct() {
        $this->db = new Database();
    }

    // MKA Card methods
    public function getUserMkaCard($pesel) {
        $sql = "SELECT *, TO_CHAR(data_waznosci, 'DD.MM.YYYY') as formatted_expiry 
                FROM mka_card_doc 
                WHERE pesel = ? AND status_karty = true 
                LIMIT 1";
        return $this->db->fetch($sql, [$pesel]);
    }

    public function createMkaCard($cardData) {
        $sql = "INSERT INTO mka_card_doc (id_karty, pesel, data_waznosci, typ_karty, status_karty, strefa) 
                VALUES (?, ?, ?, ?, true, ?)";

        return $this->db->query($sql, [
            $cardData['id_karty'],
            $cardData['pesel'],
            $cardData['data_waznosci'],
            $cardData['typ_karty'],
            $cardData['strefa']
        ]);
    }

    public function updateMkaCard($cardData) {
        $sql = "UPDATE mka_card_doc SET typ_karty = ?, strefa = ? 
                WHERE id_karty = ? AND pesel = ?";

        return $this->db->query($sql, [
            $cardData['typ_karty'],
            $cardData['strefa'],
            $cardData['id_karty'],
            $cardData['pesel']
        ]);
    }

    // RPK Card methods
    public function getUserRpkCard($pesel) {
        $sql = "SELECT *, TO_CHAR(data_waznosci, 'DD.MM.YYYY') as formatted_expiry 
                FROM rpk_card_doc 
                WHERE pesel = ? AND status_karty = true 
                LIMIT 1";
        return $this->db->fetch($sql, [$pesel]);
    }

    public function createRpkCard($cardData) {
        $sql = "INSERT INTO rpk_card_doc (id_karty, pesel, data_waznosci, typ_karty, status_karty) 
                VALUES (?, ?, ?, ?, true)";

        return $this->db->query($sql, [
            $cardData['id_karty'],
            $cardData['pesel'],
            $cardData['data_waznosci'],
            $cardData['typ_karty']
        ]);
    }

    public function updateRpkCard($cardData) {
        $sql = "UPDATE rpk_card_doc SET typ_karty = ? 
                WHERE id_karty = ? AND pesel = ?";

        return $this->db->query($sql, [
            $cardData['typ_karty'],
            $cardData['id_karty'],
            $cardData['pesel']
        ]);
    }

    // RES Card methods
    public function getUserResCard($pesel) {
        $sql = "SELECT *, TO_CHAR(data_zam, 'DD.MM.YYYY') as formatted_registration 
                FROM res_card_doc 
                WHERE pesel = ? 
                LIMIT 1";
        return $this->db->fetch($sql, [$pesel]);
    }

    public function createResCard($cardData) {
        $sql = "INSERT INTO res_card_doc (pesel, id_karty, data_zam, osiedle, ulica, nr_domu, nr_mieszkania) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        return $this->db->query($sql, [
            $cardData['pesel'],
            $cardData['id_karty'],
            $cardData['data_zam'],
            $cardData['osiedle'],
            $cardData['ulica'],
            $cardData['nr_domu'],
            $cardData['nr_mieszkania']
        ]);
    }

    public function updateResCard($cardData) {
        $sql = "UPDATE res_card_doc SET data_zam = ?, osiedle = ?, ulica = ?, nr_domu = ?, nr_mieszkania = ? 
                WHERE id_karty = ? AND pesel = ?";

        return $this->db->query($sql, [
            $cardData['data_zam'],
            $cardData['osiedle'],
            $cardData['ulica'],
            $cardData['nr_domu'],
            $cardData['nr_mieszkania'],
            $cardData['id_karty'],
            $cardData['pesel']
        ]);
    }

    // Utility methods
    public function generateCardId() {
        return rand(100000000, 999999999);
    }

    public function userHasAnyCard($pesel) {
        $mka = $this->getUserMkaCard($pesel);
        $rpk = $this->getUserRpkCard($pesel);
        $res = $this->getUserResCard($pesel);

        return ($mka !== false) || ($rpk !== false) || ($res !== false);
    }
}
?>