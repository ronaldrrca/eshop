<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="../backend/authentications/login-clientes-back.php" METHOD="POST">
        <label for="email_actual">Email</label>
        <input type="email" name="email" id="email">
        <label for="id">Password</label>
        <input type="password" name="password" id="pasword">
       
        <button type="submit">Registrar</button>
    </form>
</body>
</html>