<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="./backend/cliets/registro-de-clientes.php" METHOD="POST">
        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="">
        <label for="email">Email</label>
        <input type="email" name="email" id="">
        <label for="email_confirmacion">Repita el email</label>
        <input type="email" name="email_confirmacion" id="">
        <label for="telefono">Teléfono</label>
        <input type="tel" name="telefono" id="">
        <label for="direccion">Dirección</label>
        <input type="text" name="direccion" id="">
        <label for="password">Password</label>
        <input type="password" name="password" id="">
        <label for="password_confirmacion">Password_confirmación</label>
        <input type="password" name="password_confirmacion" id="">
        <button type="submit">Registrar</button>
    </form>
</body>
</html>