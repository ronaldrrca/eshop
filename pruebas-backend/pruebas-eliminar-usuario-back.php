<?php
session_start();
$_SESSION['id_usuario'] = "1";


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="../backend/users/eliminar-usuario-back.php" METHOD="POST">
        <label for="id">Id</label>
        <input type="number" name="id" id="id" value="2">
        
        <button type="submit">Registrar</button>
    </form>
</body>
</html>