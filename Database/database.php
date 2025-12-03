<?php
// Database connection class
// Uses PDO(PHP Data Objects) for secure database interactions
class Database
{
    private $host = 'localhost';
    private $db_name = 'accessibility_finder';
    private $username = 'root';
    private $password = '';
    private $conn;
    // Constructor to initialize the database connection
    public function __construct()
    {
        $this->connect();
    }
    // Get the database connection
    public function connect()
    {
        $this->conn = null;
        // Set up the PDO connection
        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Handle connection errors
        } catch (PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }

        return $this->conn;
    }

}

