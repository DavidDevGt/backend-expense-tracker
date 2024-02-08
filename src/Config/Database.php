<?php
namespace App\Config;

use mysqli;

class Database {
    private $host = $_ENV['DB_HOST'];
    private $db = $_ENV['DB_NAME'];
    private $user = $_ENV['DB_USER'];
    private $pass = $_ENV['DB_PASS'];
    private $conn;

    public function connect() {
        $this->conn = null;

        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        return $this->conn;
    }
}
