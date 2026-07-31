<?php

class Database
{
    private $host = "localhost";
    private $database = "financecoach";
    private $user = "root";
    private $password = "";

    public function connect()
    {
        try {

            $conn = new PDO(
                "mysql:host={$this->host};dbname={$this->database}",
                $this->user,
                $this->password
            );

            $conn->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            return $conn;

        } catch (PDOException $e) {

            die(
                "Error conexión: " .
                $e->getMessage()
            );

        }
    }
}