<?php
session_start();
$_SESSION['rol_usuario'] = "superadmin";


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="../backend/sales/registrar-venta-back.php" method="post">
        <label for="id_cliente">ID cliente</label>
        <input type="number" name="id_cliente" id="id_cliente">
        <label for="medio_pago">Medio de pago</label>
        <input type="text" name="medio_pago" id="medio_pago">
        <label for="numero_referencia_pago">Número de referencia del pago</label>
        <input type="text" name="numero_referencia_pago" id="numero_referencia_pago">
        <label for="id_producto">ID producto</label>
        <input type="number" name="id_producto[]" id="id_producto">
        <label for="cantidad">Cantidad</label>
        <input type="number" name="cantidad[]" id="cantidad">
        <label for="precio">Precio</label>
        <input type="number" name="precio[]" id="precio">
        <input type="submit" value="Registrar">
    </form>
</body>
</html>