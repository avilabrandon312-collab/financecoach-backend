<!DOCTYPE html>
<html>

<head>

<title>FinanceCoach</title>

<link
rel="stylesheet"
href="public/css/auth.css">


</head>

<body>

<div class="container">

<div class="left">

<h1>FinanceCoach</h1>

<p>
Tu compañero financiero.
</p>

</div>

<div class="card">

<h2>Crear Cuenta</h2>

<p>
Empieza a construir hábitos.
</p>

<form
method="POST"
action="/financecoach/index.php?action=register">

<input
type="text"
name="name"
placeholder="Nombre"
required>

<input
type="email"
name="email"
placeholder="Correo"
required>

<input
type="password"
name="password"
placeholder="Contraseña"
required>

<button>

Crear cuenta

</button>

</form>

<a href="#">

Ya tengo cuenta

</a>

</div>

</div>

</body>

</html>