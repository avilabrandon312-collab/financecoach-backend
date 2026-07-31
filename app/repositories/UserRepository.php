<?php

require_once __DIR__ . '/../../config/database.php';

class UserRepository
{
    private $conn;

    public function __construct()
    {
        $database = new Database();

        $this->conn =
            $database->connect();
    }

    public function create($user)
    {
        $sql = "
        INSERT INTO users
        (
            name,
            email,
            password
        )
        VALUES
        (
            :name,
            :email,
            :password
        )
        ";

        $stmt =
            $this->conn
            ->prepare($sql);

        return $stmt->execute([

            ':name' =>
            $user->name,

            ':email' =>
            $user->email,

            ':password' =>
            password_hash(
                $user->password,
                PASSWORD_DEFAULT
            )

        ]);
    }
}