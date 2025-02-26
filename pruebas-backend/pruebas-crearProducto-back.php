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
    <form action="../backend/products/crear-producto-back.php" method="post">
        <label for="nombre_producto">Nombre producto</label>
        <input type="text" name="nombre_producto" id="nombre_producto">
        
        <label for="descripcion_producto">Descripción</label>
        <input type="text" name="descripcion_producto" id="descripcion_producto">
        
        <label for="categoria_producto">Categoría</label>
        <input type="text" name="categoria_producto" id="categoria_producto">
        
        <label for="codigo_barras_producto">Código de barras</label>
        <input type="text" name="codigo_barras_producto" id="codigo_barras_producto">

        <label for="marca_producto">Marca</label>
        <input type="text" name="marca_producto" id="marca_producto">
        
        <label for="precio_producto">Precio</label>
        <input type="number" name="precio_producto" id="precio_producto">
        
        <label for="stock_producto">Stock</label>
        <input type="number" name="stock_producto" id="stock_producto">

        <input type="submit" value="Crear">
    </form>
    
</body>
</html>