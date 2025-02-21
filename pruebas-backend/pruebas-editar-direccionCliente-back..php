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
    <form action="./backend/clients/editar-direccionCliente-back.php" METHOD="POST">
        <label for="id">Id</label>
        <input type="number" name="id" id="id" value="2">
        <label for="nombre">Departamento</label>
        <input type="text" name="departamento" id="departamento">
        <label for="ciudad">Ciudad</label>
        <input type="text" name="ciudad" id="ciudad">
        <label for="direccion">Dirección</label>
        <input type="text" name="direccion" id="direccion">
        <label for="barrio">Barrio</label>
        <input type="text" name="barrio" id="barrio">
     
        <button type="submit">Registrar</button>
    </form>
</body>
</html>