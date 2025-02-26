<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="../backend/authentications/login-usuarios-back.php" METHOD="POST">
        <label for="usuario">Usuario</label>
        <input type="text" name="usuario" id="usuario">
        <label for="id">Password</label>
        <input type="password" name="password" id="pasword">
       
        <button type="submit">Registrar</button>
    </form>
</body>
</html>