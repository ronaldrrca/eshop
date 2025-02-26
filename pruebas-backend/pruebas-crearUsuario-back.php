

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="../backend/users/crear-usuario-back.php" METHOD="POST">
        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="">
        <label for="usuario">Usuario</label>
        <input type="text" name="usuario" id="usuario">
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
        <label for="password_confirmacion">Password_confirmación</label>
        <input type="password" name="password_confirmacion" id="">
        <label for="rol">Rol usuario</label>
        <input type="text" name="rol" id="rol">
        
        <button type="submit">Registrar</button>
    </form>
</body>
</html>