<!DOCTYPE html>

<html>

<head>

<title>Nuevo Movimiento</title>

<link
rel="stylesheet"
href="public/css/transaction.css">

</head>

<body>

<div class="wrapper">

<div class="sidebar">

<h2>
FinanceCoach
</h2>

<a href="#">
Dashboard
</a>

<a href="#">
Movimientos
</a>

<a href="#">
Metas
</a>

<a href="#">
Coach
</a>

</div>

<div class="content">

<h1>

Nuevo Movimiento

</h1>

<form
method="POST"
action="index.php?action=transaction">

<select
name="type"
required>

<option value="">
Seleccionar
</option>

<option value="income">
Ingreso
</option>

<option value="expense">
Gasto
</option>

</select>

<input
name="category"
placeholder="Categoría">

<input
type="number"
step="0.01"
name="amount"
placeholder="Monto">

<textarea
name="description"
placeholder="Descripción">
</textarea>

<button>

Guardar Movimiento

</button>

</form>

</div>

</div>

</body>

</html>