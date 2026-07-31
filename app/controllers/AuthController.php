<?php

require_once __DIR__ . '/../services/AuthService.php';

class AuthController
{
    private $authService;

    public function __construct()
    {
        session_start();

        $this->authService =
            new AuthService();
    }

    public function register()
    {

        try {

            $name =
            $_POST['name'];

            $email =
            $_POST['email'];

            $password =
            $_POST['password'];

            $this
            ->authService
            ->register(
                $name,
                $email,
                $password
            );

            $_SESSION['user']=

            [
                'name'=>$name,
                'email'=>$email
            ];

            header(
                "Location: index.php?action=dashboard"
            );

            exit();

        }

        catch(Exception $e)
        {

            echo $e->getMessage();

        }

    }

}