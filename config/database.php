<?php

class Database {

    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "online_food_ordering";

    public $conn;

    // Database Connection
    public function connect() {

        $this->conn = new mysqli(
            $this->host,
            $this->username,
            $this->password,
            $this->database
        );

        // Check Connection
        if ($this->conn->connect_error) {
            die("Database Connection Failed: " . $this->conn->connect_error);
        }

        // Set Character Encoding
        $this->conn->set_charset("utf8mb4");

        return $this->conn;
    }
}

?>