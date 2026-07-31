<?php

session_start();

if(
!isset(
$_SESSION['user']
)
){
header(
"Location: /financecoach"
);
exit();
}

?>

<!DOCTYPE html>

<html>

<head>

<title>
Dashboard
</title>

<link
rel="stylesheet"
href="public/css/dashboard.css">

</head>

<body>

<div class="dashboard">

<h1>

Hola
<?=

$_SESSION['user']['name']

?>

👋

</h1>

<p>




<div class="card">

Metas

</div>

<div class="card">

Coach

</div>

</div>

</div>

</body>

</html>