<?php

namespace Jc\EventManagement;

use PDO;
use PDOException;

class Database {
    private $host = 'localhost:3306';
    private $db_name = 'event_management';
    private $username = 'root';
    private $password = '19023';
    public $conn;
    

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
