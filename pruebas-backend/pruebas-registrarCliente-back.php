

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="../backend/clients/registrar-clientes-back.php" METHOD="POST">
        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="">
        <label for="email">Email</label>
        <input type="email" name="email" id="email">
        <label for="email_confirmacion">Repita el email</label>
        <input type="email" name="email_confirmacion" id="email_confirmacion">
        <label for="telefono">Teléfono</label>
        <input type="tel" name="telefono" id="telefono">
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
        <label for="password_confirmacion">Password_confirmación</label>
        <input type="password" name="password_confirmacion" id="">
        
        <button type="submit">Registrar</button>
    </form>
</body>
</html>