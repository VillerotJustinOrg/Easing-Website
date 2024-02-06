<?php

class Database {

    private string $servername = "localhost";
    private string $username = "admin";
    private string $password = "admin";
    private string $database = "logement";
    private mysqli $conn;

    // Constructor
    public function __construct() {

//        // Load database credentials from .env file
//        $dotenv = parse_ini_file('../.env');
//
//        $this->servername = $dotenv['DB_SERVERNAME'];
//        $this->username = $dotenv['DB_USERNAME'];
//        $this->password = $dotenv['DB_PASSWORD'];
//        $this->database = $dotenv['DB_DATABASE'];

//        echo "|".$this->servername . ", " . $this->username . ", " . $this->password . ", " . $this->database."|";

        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        // Create connection
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->database);

        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
//        echo "Connected successfully";

        # neo4j Connection

    }

    // Method to get the database connection
    public function getConnection(): mysqli
    {
        return $this->conn;
    }
}