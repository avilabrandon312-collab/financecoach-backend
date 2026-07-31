<?php

session_start();

include 'config.php';

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email='$email'";

$query = mysqli_query($conn, $sql);

if(mysqli_num_rows($query) > 0){

    $user = mysqli_fetch_assoc($query);

    if(password_verify($password, $user['password'])){

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        header("Location: ../views/dashboard.php");

    }else{

        echo "Contraseña incorrecta";

    }

}else{

    echo "Usuario no encontrado";

}

?>