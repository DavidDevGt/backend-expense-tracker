<?php
namespace App\Config;

use mysqli;

class Database {
    private $host = 'localhost';
    private $db = 'money_tracker_db';
    private $user = 'root';
    private $pass = '';
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
