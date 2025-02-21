<?php
session_start();
$_SESSION['id_cliente'] = "2";

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="../backend/clients/eliminar-itemCarrito-back..php" METHOD="POST">
        <label for="id_cliente">Id cliente</label>
        <input type="number" name="id_cliente" id="id_cliente" value="2">
        <label for="id_producto">Id producto</label>
        <input type="number" name="id_producto" id="id_producto">
               
        <button type="submit">Registrar</button>
    </form>
</body>
</html>