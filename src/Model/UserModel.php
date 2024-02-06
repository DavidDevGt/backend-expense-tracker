<?php
namespace App\Model;

use mysqli;

class UserModel {
    private $db;
    
    public function __construct(mysqli $db) {
        $this->db = $db;
    }

    public function findUserByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function createUser($username, $hashedPassword) {
        $stmt = $this->db->prepare("INSERT INTO users (username, hashed_password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashedPassword);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function loginUser($username, $hashedPassword) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ? AND hashed_password = ?");
        $stmt->bind_param("ss", $username, $hashedPassword);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
