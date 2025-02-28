<?php
session_start();
$_SESSION['rol_usuario'] = "superadmin";

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="../backend/products/ver-productos-back.php" method="post">
        <input type="submit" value="Ver productos">
    </form>
</body>
</html>