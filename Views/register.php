<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
</head>
<body>

<h1>Crear cuenta</h1>

<form action="../php/register-user.php" method="POST">

    <input type="text" name="name" placeholder="Nombre">

    <br><br>

    <input type="email" name="email" placeholder="Correo">

    <br><br>

    <input type="password" name="password" placeholder="Contraseña">

    <br><br>

    <button type="submit">
        Registrarme
    </button>

</form>

</body>
</html>