<?php
// Datos de la conexión
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "eshop";

// Crear conexión
$conexion = new mysqli($servidor, $usuario, $password, $base_datos);

// Verificando errores de conexión
if ($conexion->connect_error) {
    die("Error de conexión:" - $conexion->connect_error);
} 

// Estableciendo codificación de caracteres
$conexion->set_charset("utf8");
?>