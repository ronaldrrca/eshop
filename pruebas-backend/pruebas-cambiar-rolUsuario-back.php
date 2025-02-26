<?php
session_start();
$_SESSION['id_usuario'] = "1";
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
    <form action="../backend/users/cambiar-rolUsuario-back.php" METHOD="POST">
        <label for="id">Id</label>
        <input type="number" name="id" id="id" value="3">
       
        
        <label for="rol">Rol</label>
        <input type="text" name="rol_nuevo" id="">
        <button type="submit">Registrar</button>
    </form>
</body>
</html>