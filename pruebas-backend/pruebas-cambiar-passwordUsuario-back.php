<?php
session_start();
$_SESSION['id_usuario'] = "3";

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="../backend/users/cambiar-passwordUsuario-back.php" METHOD="POST">
        <label for="id">Id</label>
        <input type="number" name="id" id="id" value="3">
        <!-- <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id=""> -->
        <label for="usuario">Usuario</label>
        <input type="text" name="usuario" id="">
        <!-- <label for="email_actual">Email</label> -->
        <!-- <input type="email" name="email" id="email_actual" hidden value="emailpruebas2_edit@gmail.com"> -->
        <!-- <label for="email_nuevo">Nuevo email</label>
        <input type="email" name="email_nuevo" id="email_nuevo"> -->
        <!-- <label for="email_confirmacion">Repita el email</label>
        <input type="email" name="email_confirmacion" id="email_confirmacion">
        <label for="telefono">Teléfono</label>
        <input type="tel" name="telefono" id="telefono">
        <label for="direccion">Dirección</label>
        <input type="text" name="direccion" id="direccion"> -->
        <label for="password_actual">Password Actual</label>
        <input type="password" name="password_actual" id="password_actual">
        <label for="password_nuevo">Nuevo password</label>
        <input type="password" name="password_nuevo" id="password_nuevo">
        <label for="password_confirmacion">Password_confirmación</label>
        <input type="password" name="password_confirmacion" id="">
        <!-- <label for="rol">Rol</label>
        <input type="text" name="rol" id=""> -->
        <button type="submit">Registrar</button>
    </form>
</body>
</html>