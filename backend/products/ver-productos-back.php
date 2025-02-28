<?php
session_start();
require_once '../conexion.php';
header('Content-Type: application/json');

$respuesta = [];

// Validemos que se cuente con los privilegios necesario con rol de admin o superadmin
if (isset($_SESSION['rol_usuario']) && $_SESSION['rol_usuario'] == 'superadmin' || $_SESSION['rol_usuario'] == ' admin') {

    // Preparar la consulta
    $stmt = $conexion->prepare("CALL verTodosLosProductos()");
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Inicializar array del data
    $data = [];
    while ($fila = $resultado->fetch_assoc()) {
        $data[] = $fila;
    }

    $respuesta = [
        "mensaje" => "Datos recibidos con éxito.",
        "status" => "success",
        "data" => $data
    ];

    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
        // header("Location: formulario-registro-cliente.php");
        exit();


} else {
    $respuesta = [
        "mensaje" => "No tiene suficientes privilegio.",
        "status" => "error"
    ];

    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
        // header("Location: formulario-registro-cliente.php");
        exit();
}



?>