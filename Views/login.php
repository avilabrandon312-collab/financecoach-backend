<?php
session_start();

if(isset($_SESSION['user_id'])){
    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | Finance Coach</title>

    <link rel="stylesheet" href="../css/login.css">

</head>
<body>

    <div class="container">

        <h1>
            Bienvenido 👋
        </h1>

        <p>
            Inicia sesión en Finance Coach
        </p>

        <form action="../php/login-user.php" method="POST">

            <input
                type="email"
                name="email"
                placeholder="Correo electrónico"
                required
            >

            <input
                type="password"
                name="password"
                placeholder="Contraseña"
                required
            >

            <button type="submit">
                Iniciar sesión
            </button>

        </form>

        <div class="links">

            <a href="register.php">
                ¿No tienes cuenta? Crear cuenta
            </a>

            <br>

            <a href="../index.php" class="back">
                ← Volver al inicio
            </a>

        </div>

    </div>

</body>
</html>