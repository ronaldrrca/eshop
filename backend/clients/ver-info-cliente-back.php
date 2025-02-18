<?php
session_start();
require_once '../conexion.php';
header("Content-Type: application/json");

$respuesta = [];

// Verificar conexión
if ($conexion->connect_error) {
    echo json_encode(["mensaje" => "Error de conexión a la base de datos", "status" => "error"]);
    exit;
}

// Determinar el ID del cliente
$id = (isset($_GET['id']) ? trim($_GET['id']) : "");

// Preparar la consulta
$stmt = $conexion->prepare("CALL verInfoCliente(?)");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$cliente = $resultado->fetch_assoc();

if ($cliente) {
    $respuesta = [
        "mensaje" => "Cliente encontrado.",
        "status" => "ok",
        "nombre" => $cliente['nombre_cliente'],
        "email" => $cliente['email_cliente'],
        "telefono" => $cliente['telefono_cliente'],
        "direccion" => $cliente['direccion_cliente']
    ];
} else {
    $respuesta = [
        "mensaje" => "No se encontró el cliente.",
        "status" => "error",
    ];
}

// Enviar respuesta en JSON
echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);

// Cerrar conexión
$stmt->close();
$conexion->close();
?>

?>
