<?php
session_start();
$_SESSION['id_cliente'] = "8";

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="../backend/clients/agregarEditar-direccionCliente-back.php" METHOD="POST">
        <input type="number" name="id" id="id" value="8" hidden>
        <label for="departamento">Departamento</label>
        <input type="text" name="departamento" id="departamento">
        <label for="cuidad">Cuidad o municipio</label>
        <input type="text" name="cuidad" id="cuidad">
        <label for="direccion_envio">Dirección de envío</label>
        <input type="text" name="direccion_envio" id="direccion_envio">
        <label for="barrio">Barrio</label>
        <input type="text" name="barrio" id="barrio">
        <button type="submit">Registrar</button>
    </form>
</body>
</html>