<?php
session_start();
$_SESSION['rol_usuario'] = "superadmin";

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="../backend/products/eliminar-producto-back.php" METHOD="POST">
        <label for="id">Id</label>
        <input type="number" name="id_producto" id="id">
        
        <button type="submit">Eliminar</button>
    </form>
</body>
</html>