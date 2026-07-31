<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../repositories/UserRepository.php';

class AuthService
{
    private $userRepository;

    public function __construct()
    {
        $this->userRepository =
            new UserRepository();
    }

    public function register(
        $name,
        $email,
        $password
    )
    {

        if (
            empty($name) ||
            empty($email) ||
            empty($password)
        ) {

            throw new Exception(
                "Todos los campos son obligatorios"
            );

        }

        if (
            !filter_var(
                $email,
                FILTER_VALIDATE_EMAIL
            )
        ) {

            throw new Exception(
                "Correo inválido"
            );

        }

        $user =
            new User(
                $name,
                $email,
                $password
            );

        return $this
            ->userRepository
            ->create(
                $user
            );

    }
}