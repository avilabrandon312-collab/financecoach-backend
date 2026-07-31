<?php

session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
}

?>
<link rel="stylesheet" href="../css/dashboard.css">
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>

<h1>
    Bienvenido,
    <?php echo $_SESSION['user_name']; ?>
</h1>

<p>
    Ya estás dentro de Finance Coach 🚀
</p>

<a href="../php/logout.php">
    Cerrar sesión
</a>

</body>
</html>