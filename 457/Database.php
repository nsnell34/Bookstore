<?php

class Database
{
    private $user = "nicholas.s.snell@undcsmysql";
    private $password = "****";
    private $database = "nicholas_s_snell";
    private $host = "localhost";
    public $conn;

    public function __construct()
    {
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

}