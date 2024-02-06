<?php
namespace App\Model;

use mysqli;

class TransactionModel {
    private $db;
    
    public function __construct(mysqli $db) {
        $this->db = $db;
    }

    public function getTransactionsByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM transactions WHERE user_id = ? AND active = TRUE");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function createTransaction($userId, $text, $amount) {
        $stmt = $this->db->prepare("INSERT INTO transactions (user_id, text, amount) VALUES (?, ?, ?)");
        $stmt->bind_param("isd", $userId, $text, $amount);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function getTransactionById($id) {
        $stmt = $this->db->prepare("SELECT * FROM transactions WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateTransaction($id, $text, $amount) {
        $stmt = $this->db->prepare("UPDATE transactions SET text = ?, amount = ? WHERE id = ?");
        $stmt->bind_param("sdi", $text, $amount, $id);
        $stmt->execute();
        return $stmt->affected_rows;
    }

    public function deactivateTransaction($id) {
        $stmt = $this->db->prepare("UPDATE transactions SET active = FALSE WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->affected_rows;
    }
}
