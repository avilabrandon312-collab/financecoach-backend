<?php

include 'config.php';

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

$encryptedPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users(name,email,password)
VALUES('$name','$email','$encryptedPassword')";

$query = mysqli_query($conn, $sql);

if($query){
    echo "Usuario registrado";
}else{
    echo "Error";
}

?>